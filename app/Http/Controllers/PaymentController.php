<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\ElectricBill;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) abort(403);

        // admin + staff → full access
        if (in_array($user->role, ['admin', 'staff'])) {
            $payments = Payment::with('customer', 'bill.usage')
                ->oldest()
                ->paginate(10);
        }

        // customer → only own records
        else {
            $customer = Customer::where('user_id', $user->id)->first();

            $payments = Payment::with('customer', 'bill.usage')
                ->when($customer, function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                })
                ->oldest()
                ->paginate(10);
        }

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $user = auth()->user();

        if (!$user) abort(403);

        // customers dropdown
        $customers = Customer::when(
            in_array($user->role, ['user', 'customer']),
            function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }
        )->get();

        // ✅ bills dropdown — load with usage.customer so JS filter works
        $bills = ElectricBill::with('usage.customer')
            ->where('status', 'unpaid')
            ->when(
                in_array($user->role, ['user', 'customer']),
                function ($q) use ($user) {
                    $customer = Customer::where('user_id', $user->id)->first();
                    if ($customer) {
                        $q->whereHas('usage', function ($q2) use ($customer) {
                            $q2->where('customer_id', $customer->id);
                        });
                    }
                }
            )
            ->get();

        return view('payments.create', compact('customers', 'bills'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) abort(403);

        // ONLY admin + staff can add payments
        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'bill_id'     => 'required|exists:electric_bills,id',
            'amount_paid' => 'required|numeric|min:0',
            'date_paid'   => 'required|date',  // ✅ validate date
        ]);

        $bill = ElectricBill::findOrFail($validated['bill_id']);

        Payment::create([
            'customer_id' => $validated['customer_id'],
            'bill_id'     => $validated['bill_id'],
            'amount_paid' => $validated['amount_paid'],
            'date_paid'   => $validated['date_paid'], // ✅ date only, no time
        ]);

        if ($validated['amount_paid'] >= $bill->bill_amount) {
            $bill->update(['status' => 'paid']);
        }

        // ✅ redirect to index with success message
        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    public function edit(string $id)
    {
        $payment = Payment::with('customer', 'bill.usage')->findOrFail($id);

        $this->authorizePayment($payment);

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'date_paid'   => 'required|date',
        ]);

        $payment = Payment::findOrFail($id);

        $this->authorizePayment($payment);

        $payment->update([
            'amount_paid' => $request->amount_paid,
            'date_paid'   => $request->date_paid,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $this->authorizePayment($payment);

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    public function pdf()
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'staff'])) {
            $payments = Payment::with('customer', 'bill.usage')->get();
        } else {
            $customer = Customer::where('user_id', $user->id)->first();

            $payments = Payment::with('customer', 'bill.usage')
                ->when($customer, function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                })
                ->get();
        }

        $pdf = Pdf::loadView('payments.pdf', compact('payments'));

        return $pdf->download('payment-records.pdf');
    }

    // Security Check
    private function authorizePayment($payment)
    {
        $user = auth()->user();

        if (!$user) abort(403);

        // admin + staff full access
        if (in_array($user->role, ['admin', 'staff'])) {
            return;
        }

        // customer restriction via user_id lookup
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer || $payment->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
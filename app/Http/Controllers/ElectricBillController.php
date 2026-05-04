<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricBill;
use App\Models\ElectricUsage;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class ElectricBillController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $query = ElectricBill::with('usage.customer');

        // ✅ customers only see their own bills
        if (in_array($user->role, ['user', 'customer'])) {
            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer) {
                $query->whereHas('usage', function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bill_amount', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhere('due_date', 'like', "%$search%")
                  ->orWhereHas('usage.customer', function ($c) use ($search) {
                      $c->where('name', 'like', "%$search%");
                  });
            });
        }

        $bills = $query->oldest()->paginate(10);

        return view('bills.index', compact('bills', 'search'));
    }

    public function create()
    {
        $user = auth()->user();

        $customer = Customer::where('user_id', $user->id)->first();

        $usages = ElectricUsage::with('customer')
            ->when(in_array($user->role, ['user', 'customer']), function ($q) use ($customer) {
                if ($customer) {
                    $q->where('customer_id', $customer->id);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->get();

        return view('bills.create', compact('usages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usage_id'    => 'required|exists:electric_usages,id',
            'bill_amount' => 'required|numeric',
            'status'      => 'required',
            'due_date'    => 'required',
        ]);

        ElectricBill::create([
            'usage_id'    => $request->usage_id,
            'bill_amount' => $request->bill_amount,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill created successfully');
    }

    public function edit($id)
    {
        $bill = ElectricBill::with('usage')->findOrFail($id);

        $this->authorizeBill($bill);

        return view('bills.edit', compact('bill'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bill_amount' => 'required|numeric',
            'status'      => 'required',
            'due_date'    => 'required',
        ]);

        $bill = ElectricBill::findOrFail($id);

        $this->authorizeBill($bill);

        $bill->update([
            'bill_amount' => $request->bill_amount,
            'status'      => $request->status,
            'due_date'    => $request->due_date,
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully');
    }

    public function destroy($id)
    {
        $bill = ElectricBill::findOrFail($id);

        $this->authorizeBill($bill);

        $bill->delete();

        return redirect()->back()
            ->with('success', 'Bill deleted');
    }

    public function pdf()
    {
        $user = auth()->user();

        $query = ElectricBill::with('usage.customer');

        // ✅ customers only export their own bills
        if (in_array($user->role, ['user', 'customer'])) {
            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer) {
                $query->whereHas('usage', function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $bills = $query->oldest()->get();

        $pdf = Pdf::loadView('bills.pdf', compact('bills'));

        return $pdf->download('electric-bills.pdf');
    }

    private function authorizeBill($bill)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // admin + staff = full access
        if (in_array($user->role, ['admin', 'staff'])) {
            return;
        }

        // ✅ customer restriction using user_id → customer lookup
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer || $bill->usage->customer_id !== $customer->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricBill;
use App\Models\ElectricUsage;
use Barryvdh\DomPDF\Facade\Pdf;

class ElectricBillController extends Controller
{
    /**
     * Display all bills (ROLE-AWARE)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ElectricBill::with('usage.customer');

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

        // ✅ paginate() instead of get()
        $bills = $query->oldest()->paginate(10);

        return view('bills.index', compact('bills', 'search'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $user = auth()->user();

        $usages = ElectricUsage::with('customer')
            ->when($user->role === 'customer', function ($q) use ($user) {
                $q->where('customer_id', $user->customer_id);
            })
            ->get();

        return view('bills.create', compact('usages'));
    }

    /**
     * Store bill
     */
    public function store(Request $request)
    {
        $request->validate([
            'usage_id' => 'required|exists:electric_usages,id',
            'bill_amount' => 'required|numeric',
            'status' => 'required',
            'due_date' => 'required',
        ]);

        ElectricBill::create([
            'usage_id' => $request->usage_id,
            'bill_amount' => $request->bill_amount,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill created successfully');
    }

    /**
     * Edit bill (role protected)
     */
    public function edit($id)
    {
        $bill = ElectricBill::with('usage')->findOrFail($id);

        $this->authorizeBill($bill);

        return view('bills.edit', compact('bill'));
    }

    /**
     * Update bill
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bill_amount' => 'required|numeric',
            'status' => 'required',
            'due_date' => 'required',
        ]);

        $bill = ElectricBill::findOrFail($id);

        $this->authorizeBill($bill);

        $bill->update([
            'bill_amount' => $request->bill_amount,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully');
    }

    /**
     * Delete bill
     */
    public function destroy($id)
    {
        $bill = ElectricBill::findOrFail($id);

        $this->authorizeBill($bill);

        $bill->delete();

        return redirect()->back()
            ->with('success', 'Bill deleted');
    }

    /**
     * PDF export
     */
    public function pdf()
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'staff') {
            $bills = ElectricBill::with('usage.customer')->get();
        } else {
            $bills = ElectricBill::with('usage.customer')
                ->whereHas('usage', function ($q) use ($user) {
                    $q->where('customer_id', $user->customer_id);
                })
                ->get();
        }

        $pdf = Pdf::loadView('bills.pdf', compact('bills'));

        return $pdf->download('electric-bills.pdf');
    }

    /**
     * Role Security Check
     */
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

        // customer restriction
        if ($bill->usage->customer_id !== $user->customer_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::when($search, function ($query, $search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
        })->paginate(5);

        return view('customers.index', compact('customers', 'search'));
    }

    // PDF EXPORT (FIXED)
    public function exportPDF()
    {
        $customers = Customer::all(); // ❗ IMPORTANT FIX

        $pdf = PDF::loadView('customers.pdf', compact('customers'));

        return $pdf->download('customers-report.pdf');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'dob' => 'required|date',
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'dob' => 'required|date',
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
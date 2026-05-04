<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->search;

        $customers = Customer::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            })
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(10); // ✅ changed from 5 to 10

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'    => 'required',
            'address' => 'required',
            'gender'  => 'required',
            'dob'     => 'required|date',
        ]);

        Customer::create([
            'name'    => $data['name'],
            'address' => $data['address'],
            'gender'  => $data['gender'],
            'dob'     => $data['dob'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->authorize($customer);

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorize($customer);

        $customer->update($request->validate([
            'name'    => 'required',
            'address' => 'required',
            'gender'  => 'required',
            'dob'     => 'required|date',
        ]));

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize($customer);

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    private function authorize($customer)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $customer->user_id !== $user->id) {
            abort(403);
        }
    }

    public function exportPDF()
    {
        $user = auth()->user();

        $customers = Customer::when($user->role !== 'admin', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        $pdf = Pdf::loadView('customers.pdf', compact('customers'));

        return $pdf->download('customers-report.pdf');
    }
}
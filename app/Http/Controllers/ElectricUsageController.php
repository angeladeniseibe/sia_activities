<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricUsage;
use App\Models\Customer;
use App\Models\ElectricBill;
use Barryvdh\DomPDF\Facade\Pdf;

class ElectricUsageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $query = ElectricUsage::with('customer');

        if ($user->role === 'customer') {
            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer) {
                $query->where('customer_id', $customer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('month', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $usages = $query->orderBy('id', 'asc')
                        ->paginate(10) // ✅ changed from 5 to 10
                        ->withQueryString();

        return view('usages.index', compact('usages', 'search'));
    }

    public function create()
    {
        $user = auth()->user();

        $customers = Customer::when($user->role === 'customer', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('usages.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) abort(403);

        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'kilowatts_used' => 'required|numeric|min:0',
            'rate_per_kwh'   => 'required|numeric|min:0',
            'month'          => 'required',
            'year'           => 'required|integer',
        ]);

        $usage = ElectricUsage::create($validated);

        $billAmount = $usage->kilowatts_used * $usage->rate_per_kwh;

        ElectricBill::create([
            'usage_id'    => $usage->id,
            'bill_amount' => $billAmount,
            'due_date'    => now()->addDays(15),
        ]);

        return redirect()->route('usages.index')
            ->with('success', 'Usage + Bill created successfully!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        if ($user->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $usage = ElectricUsage::with('customer')->findOrFail($id);
        $customers = Customer::orderBy('id', 'asc')->get();

        return view('usages.edit', compact('usage', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'kilowatts_used' => 'required|numeric',
            'rate_per_kwh'   => 'required|numeric',
            'month'          => 'required',
            'year'           => 'required|integer',
        ]);

        $usage = ElectricUsage::findOrFail($id);
        $usage->update($validated);

        return redirect()->route('usages.index')
            ->with('success', 'Usage updated successfully!');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->role === 'customer') {
            abort(403, 'Unauthorized action.');
        }

        $usage = ElectricUsage::findOrFail($id);
        $usage->delete();

        return redirect()->route('usages.index')
            ->with('success', 'Usage deleted successfully!');
    }

    public function downloadPDF()
    {
        $user = auth()->user();

        $query = ElectricUsage::with('customer');

        if ($user->role === 'customer') {
            $customer = Customer::where('user_id', $user->id)->first();

            if ($customer) {
                $query->where('customer_id', $customer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $usages = $query->orderBy('id', 'asc')->get();

        $pdf = Pdf::loadView('usages.pdf', compact('usages'));

        return $pdf->download('electric_usage_report.pdf');
    }
}
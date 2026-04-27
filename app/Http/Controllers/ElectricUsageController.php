<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectricUsage;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class ElectricUsageController extends Controller
{
    /**
     * 📄 INDEX
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = ElectricUsage::with('customer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('month', 'like', "%$search%")
                  ->orWhere('year', 'like', "%$search%")
                  ->orWhereHas('customer', function ($c) use ($search) {
                      $c->where('name', 'like', "%$search%");
                  });
            });
        }

        $usages = $query->latest()->get();

        return view('usages.index', compact('usages', 'search'));
    }

    /**
     * ➕ CREATE FORM
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('usages.create', compact('customers'));
    }

    /**
     * 💾 STORE USAGE
     */
 public function store(Request $request)
{
    if (!auth()->check()) {
        abort(403, 'You must be logged in');
    }

    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'kilowatts_used' => 'required|numeric',
        'rate_per_kwh' => 'required|numeric',
        'month' => 'required',
        'year' => 'required|integer',
    ]);

    ElectricUsage::create($validated);

    return redirect()->route('usages.index')
        ->with('success', 'Usage created successfully');
}


    /**
     * ✏️ EDIT
     */
    public function edit($id)
    {
        $usage = ElectricUsage::with('customer')->findOrFail($id);

        $this->authorizeUsage($usage);

        $customers = Customer::all();

        return view('usages.edit', compact('usage', 'customers'));
    }

    /**
     * 🔄 UPDATE
     */
   public function update(Request $request, $id)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'kilowatts_used' => 'required|numeric',
        'rate_per_kwh' => 'required|numeric',
        'month' => 'required',
        'year' => 'required|integer',
    ]);

    $usage = ElectricUsage::findOrFail($id);

    $this->authorizeUsage($usage);

    $usage->update($validated);

    return redirect()->route('usages.index')
        ->with('success', 'Usage updated successfully!');
}


    /**
     * ❌ DELETE
     */
    public function destroy($id)
    {
        $usage = ElectricUsage::findOrFail($id);

        $this->authorizeUsage($usage);

        $usage->delete();

        return redirect()->route('usages.index')
            ->with('success', 'Usage deleted successfully!');
    }

    /**
     * 📄 PDF EXPORT
     */
    public function downloadPDF()
    {
        $usages = ElectricUsage::with('customer')->get();

        $pdf = Pdf::loadView('usages.pdf', compact('usages'));

        return $pdf->download('electric_usage_report.pdf');
    }

    /**
     * 🔐 SECURITY CHECK
     */
    private function authorizeUsage($usage)
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'staff'])) {
            return;
        }

        abort(403, 'Unauthorized action.');
    }
}

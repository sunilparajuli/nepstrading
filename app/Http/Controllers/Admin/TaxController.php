<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxRates = TaxRate::orderBy('priority')->get();
        return view('admin.tax.index', compact('taxRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:2',
            'state' => 'nullable|string|max:50',
            'rate' => 'required|numeric|min:0|max:100',
            'priority' => 'integer|min:1',
            'compound' => 'boolean',
            'shipping' => 'boolean',
        ]);

        $validated['compound'] = $request->boolean('compound');
        $validated['shipping'] = $request->boolean('shipping');

        TaxRate::create($validated);

        return redirect()->back()->with('success', 'Tax rate added.');
    }

    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'state' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $taxRate->update($validated);

        return redirect()->back()->with('success', 'Tax rate updated.');
    }

    public function destroy(TaxRate $taxRate)
    {
        $taxRate->delete();
        return redirect()->back()->with('success', 'Tax rate deleted.');
    }
}

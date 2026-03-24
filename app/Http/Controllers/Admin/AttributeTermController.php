<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Attribute;
use App\Models\AttributeTerm;
use Illuminate\Support\Str;

class AttributeTermController extends Controller
{
    public function index(Request $request)
    {
        $attributeId = $request->query('attribute_id');
        $attribute = Attribute::findOrFail($attributeId);
        $terms = $attribute->terms()->orderBy('weight', 'asc')->latest()->paginate(20);
        
        return view('admin.attributes.terms', compact('attribute', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'name' => 'required|max:255',
            'slug' => 'nullable|unique:attribute_terms,slug',
            'value' => 'nullable|max:255',
            'weight' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        AttributeTerm::create($validated);

        return back()->with('success', 'Term added successfully.');
    }

    public function update(Request $request, AttributeTerm $attributeTerm)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:attribute_terms,slug,' . $attributeTerm->id,
            'value' => 'nullable|max:255',
            'weight' => 'nullable|integer',
        ]);

        $attributeTerm->update($validated);

        return back()->with('success', 'Term updated successfully.');
    }

    public function destroy(AttributeTerm $attributeTerm)
    {
        $attributeTerm->delete();
        return back()->with('success', 'Term deleted successfully.');
    }
}

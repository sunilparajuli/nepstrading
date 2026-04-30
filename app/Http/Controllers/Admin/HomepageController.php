<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('order')->get();
        $products = Product::select('id', 'name', 'price', 'image')->get();
        $categories = Category::all();
        
        return view('admin.homepage.index', compact('sections', 'products', 'categories'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:homepage_sections,id'
        ]);

        foreach ($request->ids as $index => $id) {
            HomepageSection::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string',
        ]);

        HomepageSection::create([
            'type' => $request->type,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'order' => HomepageSection::max('order') + 1,
            'data' => []
        ]);

        return back()->with('success', 'Section added successfully');
    }

    public function update(Request $request, HomepageSection $section)
    {
        $data = $request->input('data', []);

        if ($request->hasFile('bg_image_file')) {
            // Delete old image if it exists and is local
            if (isset($section->data['bg_image']) && str_starts_with($section->data['bg_image'], '/storage/banners/')) {
                $oldPath = str_replace('/storage/', '', $section->data['bg_image']);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('bg_image_file')->store('banners', 'public');
            $data['bg_image'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        $section->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'is_active' => $request->has('is_active') ? $request->is_active : $section->is_active,
            'data' => $data,
            'order' => $request->order ?? $section->order
        ]);

        return back()->with('success', 'Section updated successfully');
    }

    public function destroy(HomepageSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section removed successfully');
    }
}

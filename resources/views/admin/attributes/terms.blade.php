@extends('layouts.admin')

@section('title', 'Configure Terms: ' . $attribute->name)

@section('content')
<div class="mb-8 flex items-center space-x-4">
    <a href="{{ route('admin.attributes.index') }}" class="text-sm font-bold text-blue-600 hover:underline">← Back to Attributes</a>
</div>

<div class="flex flex-col lg:flex-row gap-8">
    <!-- Add New Term Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-sm">Add New Term</h3>
            <form action="{{ route('admin.attribute-terms.store') }}" method="POST">
                @csrf
                <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                        <input type="text" name="name" required class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-gray-400 mt-1 italic">Name for the term (e.g. Red, XL).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug (Optional)</label>
                        <input type="text" name="slug" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                    </div>
                    @if($attribute->type == 'color')
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Color Value (Hex)</label>
                        <input type="color" name="value" class="w-full h-10 border-gray-200 rounded-sm p-1 bg-gray-50">
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Weight</label>
                        <input type="number" name="weight" value="0" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-gray-400 mt-1 italic">Display order (lower = first).</p>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-sm text-sm font-bold w-full uppercase tracking-wider hover:bg-black transition-colors">Add New Term</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms List Table -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-center"><input type="checkbox" class="rounded-sm border-gray-300"></th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Value</th>
                        <th class="px-6 py-4 text-center">Weight</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($terms as $term)
                    <tr class="hover:bg-gray-50 group">
                        <td class="px-6 py-4 text-center"><input type="checkbox" class="rounded-sm border-gray-300"></td>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $term->name }}</td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-[10px]">{{ $term->slug }}</td>
                        <td class="px-6 py-4">
                            @if($attribute->type == 'color')
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 rounded-full border border-gray-200" style="background-color: {{ $term->value }}"></div>
                                    <span class="text-[10px] font-mono">{{ $term->value }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">{{ $term->value ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 font-mono text-xs">{{ $term->weight }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.attribute-terms.destroy', $term) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-xs hover:underline uppercase font-bold" onclick="return confirm('Are you sure?')">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No terms found for this attribute.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t border-gray-50">
                {{ $terms->appends(['attribute_id' => $attribute->id])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

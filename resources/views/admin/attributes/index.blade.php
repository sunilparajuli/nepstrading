@extends('layouts.admin')

@section('title', 'Product Attributes')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Add New Attribute Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-sm">Add New Attribute</h3>
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                        <input type="text" name="name" required class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-gray-400 mt-1 italic">Name for the attribute (e.g. Color, Size).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug (Optional)</label>
                        <input type="text" name="slug" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Type</label>
                        <select name="type" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                            <option value="select">Select</option>
                            <option value="color">Color</option>
                            <option value="image">Image</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Weight</label>
                        <input type="number" name="weight" value="0" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
                        <p class="text-[10px] text-gray-400 mt-1 italic">Determines display order (lower = first).</p>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-sm text-sm font-bold w-full uppercase tracking-wider hover:bg-black transition-colors">Add New Attribute</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attribute List Table -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-center"><input type="checkbox" class="rounded-sm border-gray-300"></th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4 text-center">Weight</th>
                        <th class="px-6 py-4">Terms</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($attributes as $attribute)
                    <tr class="hover:bg-gray-50 group">
                        <td class="px-6 py-4 text-center"><input type="checkbox" class="rounded-sm border-gray-300"></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-blue-600 hover:underline">
                                <a href="{{ route('admin.attributes.edit', $attribute) }}">{{ $attribute->name }}</a>
                            </div>
                            <div class="flex space-x-2 text-[10px] opacity-0 group-hover:opacity-100 transition-opacity mt-1">
                                <a href="{{ route('admin.attributes.edit', $attribute) }}" class="text-blue-500">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('admin.attribute-terms.index', ['attribute_id' => $attribute->id]) }}" class="text-primary font-bold">Configure Terms</a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-[10px]">{{ $attribute->slug }}</td>
                        <td class="px-6 py-4 text-gray-400 uppercase text-[10px] font-bold">{{ $attribute->type }}</td>
                        <td class="px-6 py-4 text-center text-gray-500 font-mono text-xs">{{ $attribute->weight }}</td>
                        <td class="px-6 py-4 text-gray-400 font-medium">0</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No attributes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t border-gray-50">
                {{ $attributes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

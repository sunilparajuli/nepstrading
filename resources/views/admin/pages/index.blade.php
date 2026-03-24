@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Pages</h2>
    <a href="{{ route('admin.pages.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition">Add New Page</a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Title</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Footer Section</th>
                <th class="px-6 py-4">Order</th>
                <th class="px-6 py-4">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50 group">
                <td class="px-6 py-4">
                    <div class="font-bold text-blue-600 hover:underline">
                        <a href="{{ route('admin.pages.edit', $page) }}">{{ $page->title }}</a>
                    </div>
                    <div class="flex space-x-2 text-[10px] opacity-0 group-hover:opacity-100 transition-opacity mt-1">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-500">Edit</a>
                        <span class="text-gray-300">|</span>
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <span class="text-gray-300">|</span>
                        <a href="/page/{{ $page->slug }}" target="_blank" class="text-gray-500">View</a>
                    </div>
                </td>
                <td class="px-6 py-4 font-mono text-[10px] text-gray-500">{{ $page->slug }}</td>
                <td class="px-6 py-4">
                    <span class="rounded px-2 py-1 text-[10px] font-bold uppercase {{ $page->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $page->status }}</span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-500 italic">{{ $page->footer_section ?: 'None' }}</td>
                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $page->sort_order }}</td>
                <td class="px-6 py-4 text-xs text-gray-400">{{ $page->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">No pages found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-6 border-t border-gray-50">
        {{ $pages->links() }}
    </div>
</div>
@endsection

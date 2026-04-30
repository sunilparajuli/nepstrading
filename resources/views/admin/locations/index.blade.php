@extends('layouts.admin')

@section('title', 'Locations')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Add New Location Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-xs border-b border-gray-50 pb-2">Add New Location</h3>
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required placeholder="Location name (e.g. Sydney)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Type</label>
                        <select name="type" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none" required>
                            <option value="state">State</option>
                            <option value="city" selected>City/Suburb</option>
                            <option value="postcode">Postcode</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Code (Optional)</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="e.g. NSW or 2000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Parent Location</label>
                        <select name="parent_id" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                            <option value="">None (Top Level)</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ old('parent_id') == $state->id ? 'selected' : '' }}>{{ $state->name }} ({{ $state->code }})</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-2 italic">Select the State if adding a City, or City if adding a Postcode.</p>
                    </div>
                    <button type="submit" class="bg-primary text-black px-6 py-4 rounded-sm text-xs font-black w-full uppercase tracking-widest hover:bg-black hover:text-primary transition-all shadow-sm">Add New Location</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Location List Table -->
    <div class="w-full lg:w-2/3">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black border-b border-gray-100 tracking-widest">
                    <tr>
                        <th class="px-6 py-5">Name</th>
                        <th class="px-6 py-5">Type</th>
                        <th class="px-6 py-5">Code</th>
                        <th class="px-6 py-5">Parent</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        $sortedLocations = $allLocations->sortBy(function($loc) {
                            if($loc->type == 'state') return $loc->name;
                            return ($loc->parent ? $loc->parent->name : '') . $loc->name;
                        });
                    @endphp
                    @forelse($sortedLocations as $location)
                    <tr class="hover:bg-blue-50/30 group transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">
                                @if($location->type != 'state')
                                    <span class="text-gray-300 font-normal mr-1">—</span>
                                @endif
                                {{ $location->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $location->type == 'state' ? 'text-primary' : 'text-gray-400' }}">
                                {{ $location->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-[10px] text-gray-500">
                            {{ $location->code ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($location->parent)
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-sm text-[10px] font-bold uppercase">{{ $location->parent->name }}</span>
                            @else
                                <span class="text-gray-300 italic text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($location->is_enabled)
                                <span class="w-2 h-2 rounded-full bg-green-500 inline-block" title="Enabled"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-gray-300 inline-block" title="Disabled"></span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-3 text-[10px]">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="text-blue-500 font-bold hover:underline">Edit</a>
                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No locations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

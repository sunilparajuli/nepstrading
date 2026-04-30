@extends('layouts.admin')

@section('title', 'Edit Location')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-xs border-b border-gray-50 pb-2">Edit Location: {{ $location->name }}</h3>
        
        <form action="{{ route('admin.locations.update', $location) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $location->name) }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Type</label>
                    <select name="type" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none" required>
                        <option value="state" {{ $location->type == 'state' ? 'selected' : '' }}>State</option>
                        <option value="city" {{ $location->type == 'city' ? 'selected' : '' }}>City/Suburb</option>
                        <option value="postcode" {{ $location->type == 'postcode' ? 'selected' : '' }}>Postcode</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Code (Optional)</label>
                    <input type="text" name="code" value="{{ old('code', $location->code) }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="e.g. NSW or 2000">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Parent Location</label>
                    <select name="parent_id" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                        <option value="">None (Top Level)</option>
                        @foreach($states as $state)
                            @if($state->id != $location->id)
                                <option value="{{ $state->id }}" {{ old('parent_id', $location->parent_id) == $state->id ? 'selected' : '' }}>{{ $state->name }} ({{ $state->code }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status</label>
                    <select name="is_enabled" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                        <option value="1" {{ $location->is_enabled ? 'selected' : '' }}>Enabled</option>
                        <option value="0" {{ !$location->is_enabled ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-primary text-black px-6 py-4 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-black hover:text-primary transition-all shadow-sm flex-1">Update Location</button>
                    <a href="{{ route('admin.locations.index') }}" class="bg-gray-100 text-gray-600 px-6 py-4 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all text-center flex-1">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

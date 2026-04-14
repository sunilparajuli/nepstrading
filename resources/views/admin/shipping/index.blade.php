@extends('layouts.admin')

@section('title', 'Shipping Settings')

@section('content')
<div class="space-y-8">
    <!-- Add Zone Form -->
    <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-sm">Add New Shipping Zone</h3>
        <form action="{{ route('admin.shipping.zones.store') }}" method="POST" class="flex gap-4">
            @csrf
            <input type="text" name="name" placeholder="Zone Name (e.g. Sydney Metro)" class="flex-grow border border-gray-200 rounded-sm text-sm p-2 bg-gray-50 outline-none focus:border-blue-400" required>
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-sm text-sm font-bold uppercase hover:bg-black transition">Create Zone</button>
        </form>
    </div>

    <!-- Zones List -->
    @foreach($zones as $zone)
    <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h4 class="font-bold text-gray-700">{{ $zone->name }}</h4>
            <form action="{{ route('admin.shipping.zones.destroy', $zone) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-500 text-xs hover:underline" onclick="return confirm('Delete this zone?')">Delete Zone</button>
            </form>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Locations -->
            <div>
                <h5 class="text-xs font-bold text-gray-400 uppercase mb-4">Locations</h5>
                <ul class="space-y-2 mb-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($zone->locations as $loc)
                    <li class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded text-sm text-gray-600">
                        <span>{{ $loc->type }}: {{ $loc->code }}</span>
                        <form action="{{ route('admin.shipping.locations.destroy', $loc) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500">×</button>
                        </form>
                    </li>
                    @empty
                    <li class="text-xs text-gray-400 italic">No locations added.</li>
                    @endforelse
                </ul>
                <form action="{{ route('admin.shipping.locations.store', $zone) }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="type" class="text-xs border border-gray-200 p-1 rounded bg-gray-50">
                        <option value="state">State</option>
                        <option value="country">Country</option>
                        <option value="postcode">Postcode</option>
                    </select>
                    <input type="text" name="code" placeholder="Code (e.g. NSW)" class="flex-grow text-xs border border-gray-200 p-1 rounded bg-gray-50">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs">Add</button>
                </form>
            </div>

            <!-- Rates -->
            <div>
                <h5 class="text-xs font-bold text-gray-400 uppercase mb-4">Rates</h5>
                <ul class="space-y-2 mb-4">
                    @forelse($zone->rates as $rate)
                    <li class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded text-sm text-gray-600">
                        <span>{{ $rate->name }}: <strong>${{ number_format($rate->cost, 2) }}</strong></span>
                        <form action="{{ route('admin.shipping.rates.destroy', $rate) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500">×</button>
                        </form>
                    </li>
                    @empty
                    <li class="text-xs text-gray-400 italic">No rates added.</li>
                    @endforelse
                </ul>
                <form action="{{ route('admin.shipping.rates.store', $zone) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Rate Name" class="flex-grow text-xs border border-gray-200 p-1 rounded bg-gray-50">
                    <input type="number" name="cost" step="0.01" placeholder="Cost" class="w-16 text-xs border border-gray-200 p-1 rounded bg-gray-50">
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Add</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

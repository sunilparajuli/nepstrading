@extends('layouts.admin')

@section('title', 'Tax Rates')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Tax Rate -->
    <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-sm mb-4">Add Tax Rate</h3>
        <form action="{{ route('admin.tax.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                <input type="text" name="name" placeholder="e.g. GST" class="w-full border border-gray-200 rounded-sm p-2 text-sm" required>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Country</label>
                    <input type="text" name="country" value="AU" maxlength="2" class="w-full border border-gray-200 rounded-sm p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">State</label>
                    <input type="text" name="state" placeholder="All states" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rate (%)</label>
                    <input type="number" step="0.01" name="rate" class="w-full border border-gray-200 rounded-sm p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Priority</label>
                    <input type="number" name="priority" value="1" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                </div>
            </div>
            <div class="flex gap-4 mb-4">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="compound" value="1" class="rounded border-gray-300"> Compound
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="shipping" value="1" class="rounded border-gray-300"> Apply to Shipping
                </label>
            </div>
            <button type="submit" class="bg-primary text-black px-4 py-2 rounded-sm text-sm font-bold w-full">Add Tax Rate</button>
        </form>
    </div>

    <!-- Tax Rates List -->
    <div class="lg:col-span-2 bg-white rounded-sm shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-50">
            <h3 class="font-bold text-sm">Tax Rates</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Country</th>
                    <th class="px-6 py-4">State</th>
                    <th class="px-6 py-4">Rate</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($taxRates as $rate)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-bold">{{ $rate->name }}</td>
                    <td class="px-6 py-4">{{ $rate->country }}</td>
                    <td class="px-6 py-4">{{ $rate->state ?: 'All' }}</td>
                    <td class="px-6 py-4 font-mono">{{ $rate->rate }}%</td>
                    <td class="px-6 py-4">
                        @if($rate->is_active)
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Active</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.tax.destroy', $rate) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-[11px] hover:underline" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No tax rates configured. Add one to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

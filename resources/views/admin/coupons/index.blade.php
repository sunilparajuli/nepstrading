@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <a href="{{ route('admin.coupons.create') }}" class="bg-primary text-black px-4 py-2 rounded-sm text-sm font-bold shadow-sm hover:shadow-md transition-shadow">Add Coupon</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Usage</th>
                    <th class="px-6 py-4">Expires</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($coupons as $coupon)
                <tr class="hover:bg-gray-50 group">
                    <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $coupon->code }}</td>
                    <td class="px-6 py-4 capitalize">{{ $coupon->type }}</td>
                    <td class="px-6 py-4 font-bold">
                        {{ $coupon->type === 'percentage' ? $coupon->amount.'%' : '$'.number_format($coupon->amount, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $coupon->usage_count }}{{ $coupon->max_uses ? ' / '.$coupon->max_uses : '' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->is_active)
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Active</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2 text-[11px]">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Delete this coupon?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">{{ $coupons->links() }}</div>
</div>
@endsection

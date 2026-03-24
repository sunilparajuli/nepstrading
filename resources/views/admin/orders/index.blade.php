@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <div class="flex space-x-4 text-sm">
            <a href="{{ route('admin.orders.index') }}" class="uppercase {{ !request('status') || request('status') == 'all' ? 'font-bold text-gray-900 border-b-2 border-primary pb-1' : 'text-gray-500 hover:text-gray-900' }}">All</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="uppercase {{ request('status') == 'processing' ? 'font-bold text-gray-900 border-b-2 border-primary pb-1' : 'text-gray-500 hover:text-gray-900' }}">Processing</a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="uppercase {{ request('status') == 'completed' ? 'font-bold text-gray-900 border-b-2 border-primary pb-1' : 'text-gray-500 hover:text-gray-900' }}">Completed</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 w-10"><input type="checkbox" class="rounded-sm border-gray-300"></th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 group">
                    <td class="px-6 py-4"><input type="checkbox" class="rounded-sm border-gray-300"></td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-blue-600 hover:underline">
                            <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }} {{ $order->billing_first_name }} {{ $order->billing_last_name }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400 uppercase text-[10px] font-bold">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-sm text-[10px] uppercase font-bold 
                            {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 
                               ($order->status == 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-700">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                        @if($order->status == 'pending')
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="processing">
                            <button type="submit" title="Mark as Processing" class="p-2 border border-blue-100 text-blue-600 rounded hover:bg-blue-50 transition-colors text-xs font-bold uppercase">Process</button>
                        </form>
                        @endif

                        @if($order->status == 'processing')
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" title="Mark as Completed" class="p-2 border border-green-100 text-green-600 rounded hover:bg-green-50 transition-colors text-xs font-bold uppercase">Complete</button>
                        </form>
                        @endif

                        <a href="{{ route('admin.orders.show', $order) }}" title="View" class="p-2 border border-gray-200 rounded hover:bg-gray-100 transition-colors">👁️</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-50">
        {{ $orders->links() }}
    </div>
</div>
@endsection

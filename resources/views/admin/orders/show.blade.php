@extends('layouts.admin')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <div class="w-full lg:w-3/4 space-y-8">
        <!-- Order Meta -->
        <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold mb-6 text-gray-800">Order #{{ $order->id }} details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase text-gray-400">General</h4>
                    <p class="text-sm text-gray-600 mb-2">Date created: <br><strong>{{ $order->created_at->format('M d, Y @ H:i') }}</strong></p>
                    <p class="text-sm text-gray-600">Status: <br>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-sm text-[10px] font-bold uppercase">{{ $order->status }}</span>
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase text-gray-400">Billing</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        <strong>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</strong><br>
                        {{ $order->billing_address }}<br>
                        {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                        Phone: {{ $order->billing_phone }}
                    </p>
                    <p class="mt-4 text-sm"><a href="mailto:{{ $order->billing_email }}" class="text-blue-600 hover:underline">{{ $order->billing_email }}</a></p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase text-gray-400">Shipping</h4>
                    @if($order->shipping_address)
                    <p class="text-sm text-gray-600 leading-relaxed">
                        <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postcode }}
                    </p>
                    @else
                    <p class="text-sm text-gray-600 italic">Same as billing</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 uppercase text-[10px] font-bold text-gray-500">
                    <tr>
                        <th class="px-8 py-4">Item</th>
                        <th class="px-8 py-4">Cost</th>
                        <th class="px-8 py-4">Qty</th>
                        <th class="px-8 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-8 py-6 flex items-center space-x-4">
                            <img src="{{ $item->product->image ?? 'https://placehold.co/50x50' }}" class="w-10 h-10 object-cover rounded-sm">
                            <span class="text-blue-600 font-medium">{{ $item->product->name }}</span>
                        </td>
                        <td class="px-8 py-6 text-gray-600">${{ number_format($item->price, 2) }}</td>
                        <td class="px-8 py-6 text-gray-400">× {{ $item->qty }}</td>
                        <td class="px-8 py-6 text-right font-medium">${{ number_format($item->price * $item->qty, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="bg-gray-50 p-8 flex justify-end">
                <div class="w-64 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal:</span>
                        <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Shipping ({{ $order->shipping_name ?? 'Standard' }}):</span>
                        <div class="text-right">
                            <div class="font-medium">${{ number_format($order->shipping_total, 2) }}</div>
                            @php 
                                $shipCity = $order->shipping_city ?: $order->billing_city;
                                $shipState = $order->shipping_state ?: $order->billing_state;
                                $shipPost = $order->shipping_postcode ?: $order->billing_postcode;
                            @endphp
                            <div class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $shipCity }}, {{ $shipState }} {{ $shipPost }}</div>
                        </div>
                    </div>
                    @if($order->tax_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tax ({{ $order->tax_name }}):</span>
                        <span class="font-medium">${{ number_format($order->tax_total, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-200 pt-4 font-bold text-lg">
                        <span>Total:</span>
                        <span class="text-red-500">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="w-full lg:w-1/4 space-y-8">
        <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400">Order Actions</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <select name="status" class="w-full border-gray-200 rounded-sm text-sm p-2 bg-gray-50">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending payment</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="on-hold" {{ $order->status == 'on-hold' ? 'selected' : '' }}>On hold</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="failed" {{ $order->status == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-sm py-2 text-sm font-bold hover:bg-blue-700 transition-colors mb-3">Update Status</button>
            </form>
            <a href="{{ route('admin.orders.invoice', $order) }}" class="block w-full text-center bg-primary text-black rounded-sm py-2 text-sm font-bold hover:shadow-md transition-shadow uppercase tracking-wider">Download PDF Invoice</a>
        </div>

        <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold mb-4 uppercase text-xs tracking-wider text-gray-400">Order Notes</h3>
            <div class="text-xs text-gray-500 bg-gray-50 p-3 rounded-sm italic leading-relaxed">
                {{ $order->order_notes ?: 'No notes found.' }}
            </div>
            <button class="w-full mt-4 border border-gray-200 text-gray-600 rounded-sm py-2 text-xs font-bold hover:bg-gray-50 uppercase">Add Note</button>
        </div>
    </div>
</div>
@endsection

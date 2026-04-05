@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition">
            <svg class="mr-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <div class="border-b border-gray-100 p-6 sm:px-8 sm:py-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-serif font-bold text-gray-900">
                    Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold capitalize
                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'completed') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $order->status }}
            </span>
        </div>

        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Billing -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Billing Address</h3>
                    <address class="not-italic text-sm text-gray-600 leading-relaxed">
                        <span class="font-medium text-gray-900 block mb-1">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</span>
                        {{ $order->billing_address }}<br>
                        {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                        <a href="mailto:{{ $order->billing_email }}" class="text-[hsl(var(--primary))] hover:underline">{{ $order->billing_email }}</a><br>
                        {{ $order->billing_phone }}
                    </address>
                </div>
                
                <!-- Shipping -->
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Shipping Address</h3>
                    @if($order->shipping_first_name || $order->shipping_address)
                        <address class="not-italic text-sm text-gray-600 leading-relaxed">
                            <span class="font-medium text-gray-900 block mb-1">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</span>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_postcode }}
                        </address>
                    @else
                        <p class="text-sm text-gray-500 italic mt-2">Same as billing address</p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <h3 class="text-lg font-bold text-gray-900 mb-4 font-serif border-b border-gray-100 pb-2">Order Items</h3>
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-left">
                    <thead class="text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="pb-3 font-semibold">Product</th>
                            <th class="pb-3 font-semibold text-center">Qty</th>
                            <th class="pb-3 font-semibold text-right">Price</th>
                            <th class="pb-3 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <tr class="text-sm">
                            <td class="py-4">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->image)
                                        <div class="flex-shrink-0 h-10 w-10 mr-4 rounded-md overflow-hidden bg-gray-100 hidden sm:block">
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name ?? 'Product' }}" class="h-full w-full object-cover">
                                        </div>
                                    @endif
                                    <div class="font-medium text-gray-900">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product) }}" class="hover:underline hover:text-[hsl(var(--primary))]">{{ $item->product->name }}</a>
                                        @else
                                            <span class="text-gray-500 italic">Unknown Product (Removed)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-center text-gray-600">{{ $item->qty }}</td>
                            <td class="py-4 text-right text-gray-600">${{ number_format($item->price, 2) }}</td>
                            <td class="py-4 text-right font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary List -->
            <div class="sm:w-1/2 ml-auto">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <dt>Subtotal</dt>
                        <dd class="font-medium">${{ number_format($order->subtotal, 2) }}</dd>
                    </div>
                    
                    @if($order->discount_total > 0)
                    <div class="flex justify-between text-green-600">
                        <dt>Discount {!! $order->coupon_code ? "<span class='text-xs opacity-70'>({$order->coupon_code})</span>" : '' !!}</dt>
                        <dd class="font-medium">-${{ number_format($order->discount_total, 2) }}</dd>
                    </div>
                    @endif

                    <div class="flex justify-between text-gray-600">
                        <dt>Shipping <span class="text-xs bg-gray-100 px-1 py-0.5 rounded text-gray-500">{{ $order->shipping_name }}</span></dt>
                        <dd class="font-medium">${{ number_format($order->shipping_total, 2) }}</dd>
                    </div>

                    @if($order->tax_total > 0)
                    <div class="flex justify-between text-gray-600">
                        <dt>Tax <span class="text-xs bg-gray-100 px-1 py-0.5 rounded text-gray-500">{{ $order->tax_name }}</span></dt>
                        <dd class="font-medium">${{ number_format($order->tax_total, 2) }}</dd>
                    </div>
                    @endif

                    <div class="flex justify-between border-t border-gray-200 pt-3 text-base">
                        <dt class="font-bold text-gray-900 tracking-wide">Total</dt>
                        <dd class="font-bold text-[hsl(var(--primary))]">${{ number_format($order->total, 2) }}</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
</div>
@endsection

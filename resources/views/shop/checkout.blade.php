@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Billing Details -->
            <div class="space-y-6">
                <h2 class="text-xl font-bold border-b pb-4">Billing Details</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="billing_first_name" required value="{{ old('billing_first_name') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        @error('billing_first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="billing_last_name" required value="{{ old('billing_last_name') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        @error('billing_last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="billing_email" required value="{{ old('billing_email') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    @error('billing_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                    <input type="text" name="billing_phone" required value="{{ old('billing_phone') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    @error('billing_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                    <input type="text" name="billing_address" required value="{{ old('billing_address') }}"
                        placeholder="House number and street name"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    @error('billing_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Town / City *</label>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postcode / ZIP *</label>
                        <input type="text" name="billing_postcode" required value="{{ old('billing_postcode', $location['postcode'] ?? '') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        @error('billing_postcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Town / City *</label>
                    <input type="text" name="billing_city" required value="{{ old('billing_city', $location['city'] ?? '') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                    @error('billing_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6">
                    <h2 class="text-xl font-bold border-b pb-4 mb-4">Additional Information</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes (optional)</label>
                        <textarea name="order_notes" rows="4" 
                            placeholder="Notes about your order, e.g. special notes for delivery."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500">{{ old('order_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-gray-50 p-8 rounded-md border border-gray-200">
                    <h2 class="text-xl font-bold mb-6">Your Order</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between font-bold text-sm uppercase tracking-wider text-gray-500 border-b pb-2">
                            <span>Product</span>
                            <span>Subtotal</span>
                        </div>
                        
                        @foreach($cart as $id => $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item['name'] }} <strong class="text-gray-900">× {{ $item['qty'] }}</strong></span>
                            <span class="font-medium">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                        @endforeach
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Shipping</span>
                                <div class="text-right">
                                    <div class="font-medium text-green-600">{{ $shipping['name'] }}</div>
                                    @if($location)
                                        <div class="text-[10px] text-gray-400 italic">To: {{ $location['state'] }}, {{ $location['country'] }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between text-xl font-bold pt-4 border-t">
                                <span>Total</span>
                                <span class="text-yellow-600">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 border border-gray-200 rounded mb-6">
                        <p class="text-sm text-gray-600">
                            Cash on delivery. Pay with cash upon delivery.
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-4 rounded transition duration-200 uppercase tracking-widest">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

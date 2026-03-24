@extends('layouts.admin')

@section('title', isset($coupon) ? 'Edit Coupon' : 'Add Coupon')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-6">
        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Coupon Code</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm uppercase" required>
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Discount Type</label>
                    <select name="type" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                        <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                        <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $coupon->amount ?? '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Minimum Order ($)</label>
                    <input type="number" step="0.01" name="min_order" value="{{ old('min_order', $coupon->min_order ?? '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm" placeholder="No minimum">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Max Uses</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm" placeholder="Unlimited">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Per User Limit</label>
                    <input type="number" name="per_user_limit" value="{{ old('per_user_limit', $coupon->per_user_limit ?? '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm" placeholder="Unlimited">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Applies To</label>
                <select name="applies_to" class="w-full border border-gray-200 rounded-sm p-2 text-sm">
                    <option value="all" {{ old('applies_to', $coupon->applies_to ?? '') === 'all' ? 'selected' : '' }}>All Products</option>
                    <option value="products" {{ old('applies_to', $coupon->applies_to ?? '') === 'products' ? 'selected' : '' }}>Specific Products</option>
                    <option value="categories" {{ old('applies_to', $coupon->applies_to ?? '') === 'categories' ? 'selected' : '' }}>Specific Categories</option>
                </select>
            </div>

            <div class="flex items-center gap-6 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="free_shipping" value="1" {{ old('free_shipping', $coupon->free_shipping ?? false) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-sm">Free Shipping</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="text-sm">Active</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-primary text-black px-6 py-2 rounded-sm text-sm font-bold">{{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}</button>
                <a href="{{ route('admin.coupons.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-sm text-sm font-bold">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

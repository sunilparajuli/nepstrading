@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="md:flex md:items-start md:gap-8">
        
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 mb-8 md:mb-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-full bg-[hsl(var(--primary))] text-white flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[hsl(var(--primary))/5] text-[hsl(var(--primary))] font-medium rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Log Out
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content (Orders) -->
        <div class="w-full md:w-3/4">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Recent Orders</h2>

            @if($orders->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h3 class="text-lg font-medium text-gray-900">No orders yet</h3>
                    <p class="mt-1 text-gray-500">When you place an order, it will show up here.</p>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-[hsl(var(--primary))] shadow-sm transition-opacity hover:opacity-90">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="p-4 font-semibold">Order</th>
                                    <th class="p-4 font-semibold">Date</th>
                                    <th class="p-4 font-semibold">Status</th>
                                    <th class="p-4 font-semibold">Total</th>
                                    <th class="p-4 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4 font-medium text-gray-900">
                                            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="p-4 text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'completed') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="p-4 font-semibold text-gray-900">
                                            ${{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="p-4 text-right">
                                            <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center text-sm font-semibold text-[hsl(var(--primary))] hover:underline">
                                                View <span class="hidden sm:inline">&nbsp;Details</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@extends('errors.layout')

@section('title', 'Maintenance')
@section('code', '503')
@section('heading', 'Freshly Stocking.')
@section('message', "We're currently restocking our digital shelves to serve you better. We'll be back in a few minutes.")

@section('illustration')
    <div class="w-full aspect-square flex items-center justify-center">
        <div class="relative">
            <div class="w-48 h-48 bg-slate-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-24 h-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center text-white shadow-lg animate-bounce">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

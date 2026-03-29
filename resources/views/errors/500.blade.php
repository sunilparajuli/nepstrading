@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('heading', 'Something Cracked.')
@section('message', "Our servers are having a bit of a moment. We're already cleaning up the spill and will be back shortly.")

@section('illustration')
    <div class="w-full aspect-square flex items-center justify-center">
        <div class="relative w-48 h-48">
            <div class="absolute inset-0 border-8 border-slate-200 rounded-full"></div>
            <div class="absolute inset-0 border-8 border-purple-500 rounded-full border-t-transparent animate-spin"></div>
            <div class="absolute inset-4 bg-slate-100 rounded-full flex items-center justify-center">
                <svg class="w-20 h-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

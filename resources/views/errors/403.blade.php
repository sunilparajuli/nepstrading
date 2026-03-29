@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')
@section('heading', 'Staff Only.')
@section('message', "You don't have permission to access this shelf. Please return to the customer area.")

@section('illustration')
    <div class="w-full aspect-square flex items-center justify-center">
        <div class="w-64 h-64 bg-slate-100 rounded-3xl flex items-center justify-center shadow-inner relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent"></div>
            <svg class="w-32 h-32 text-slate-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="flex items-center justify-center min-h-[60vh] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold font-serif text-gray-900 mb-2">Forgot Password?</h2>
            <p class="text-sm text-gray-500">No worries! Just enter your email and we'll send you a link to reset it.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-8 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm">{{ session('status') }}</span>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all sm:text-sm" 
                    placeholder="you@example.com" value="{{ old('email') }}">
                @error('email')
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-xl text-black bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Send Reset Link
                </button>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('login') }}" class="text-sm font-bold text-yellow-600 hover:text-yellow-700 transition-colors">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

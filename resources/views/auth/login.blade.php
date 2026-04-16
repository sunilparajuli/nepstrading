@extends('layouts.app')

@section('title', 'Login - Nepstrading')

@section('content')
<div class="bg-gray-50 min-h-screen py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white p-10 rounded-sm shadow-sm border border-gray-100">
            <h1 class="text-3xl font-black uppercase mb-8 text-center italic">Welcome Back</h1>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 py-2">
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-500">Password</label>
                        <a href="{{ route('password.request') }}" class="text-[10px] text-gray-400 font-bold uppercase hover:text-yellow-600">Forgot?</a>
                    </div>
                    <input type="password" name="password" id="password" required
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors">
                </div>

                <div class="mb-8 flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 border-gray-300 focus:ring-yellow-400 text-yellow-500 rounded-sm">
                    <label for="remember" class="ml-2 text-xs font-medium text-gray-600 uppercase tracking-tight">Remember Me</label>
                </div>

                <button type="submit" class="w-full bg-black text-white py-4 font-bold uppercase text-xs tracking-widest hover:bg-yellow-400 hover:text-black transition-all duration-300">
                    Login
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">Don't have an account?</p>
                <a href="{{ route('register') }}" class="inline-block mt-2 font-bold text-yellow-600 hover:text-black transition-colors uppercase text-xs tracking-widest">Create Account</a>
            </div>
        </div>
    </div>
</div>
@endsection

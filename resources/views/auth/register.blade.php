@extends('layouts.app')

@section('title', 'Register - Nepstrading')

@section('content')
<div class="bg-gray-50 min-h-screen py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white p-10 rounded-sm shadow-sm border border-gray-100">
            <h1 class="text-3xl font-black uppercase mb-8 text-center italic">Join Us</h1>
            
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full border-gray-200 focus:border-yellow-400 focus:ring-0 text-sm py-3 transition-colors">
                </div>

                <button type="submit" class="w-full bg-black text-white py-4 font-bold uppercase text-xs tracking-widest hover:bg-yellow-400 hover:text-black transition-all duration-300">
                    Register
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">Already have an account?</p>
                <a href="{{ route('login') }}" class="inline-block mt-2 font-bold text-yellow-600 hover:text-black transition-colors uppercase text-xs tracking-widest">Login Instead</a>
            </div>
        </div>
    </div>
</div>
@endsection

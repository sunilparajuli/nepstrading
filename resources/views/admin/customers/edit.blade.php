@extends('layouts.admin')

@section('title', 'Edit Customer: ' . $customer->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required 
                        class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required 
                        class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                </div>

                <div class="pt-6 border-t border-gray-50 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-sm text-sm font-bold hover:bg-blue-700 transition-colors shadow-md">Update Customer</button>
                    <a href="{{ route('admin.customers.index') }}" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-sm text-sm font-bold hover:bg-gray-200 transition-colors">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

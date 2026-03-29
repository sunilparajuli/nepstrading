@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold">General Settings</h2>
        <p class="text-gray-500 text-sm">Manage global configurations for your store.</p>
    </div>
    
    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6 max-w-2xl">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="maintenance_mode" name="maintenance_mode" type="checkbox" value="1" {{ $maintenanceMode == '1' ? 'checked' : '' }} class="focus:ring-black h-4 w-4 text-black border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="maintenance_mode" class="font-medium text-gray-700">Enable Maintenance Mode</label>
                    <p class="text-gray-500">When enabled, visitors will see a maintenance page. Administrators will still have full access.</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700">Theme Primary Color</label>
                <div class="flex items-center space-x-4">
                    <input type="color" name="primary_color" value="{{ $primaryColor }}" class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                    <input type="text" id="color_hex" value="{{ $primaryColor }}" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-32">
                </div>
                
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach(['#5eba7d' => 'Forest', '#3b82f6' => 'Blue', '#ef4444' => 'Red', '#8b5cf6' => 'Purple', '#f59e0b' => 'Orange', '#ec4899' => 'Pink', '#111827' => 'Dark'] as $hex => $name)
                        <button type="button" 
                            onclick="document.querySelector('input[name=primary_color]').value = '{{ $hex }}'; document.getElementById('color_hex').value = '{{ $hex }}';"
                            class="flex items-center px-3 py-1 text-xs border border-gray-200 rounded-full hover:bg-gray-50 transition-colors">
                            <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $hex }}"></span>
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
                <p class="text-gray-500 text-xs mt-1">This color will be applied to the frontend shop's logo, buttons, and accents.</p>
            </div>

            <hr class="border-gray-200">

            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">Mobile App Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="app_version" class="block text-sm font-medium text-gray-700">Current App Version</label>
                        <input type="text" name="app_version" id="app_version" value="{{ $appVersion }}" placeholder="e.g. 1.0.0" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        <p class="text-xs text-gray-500">The latest version available in stores.</p>
                    </div>
                    <div class="space-y-2">
                        <label for="min_app_version" class="block text-sm font-medium text-gray-700">Minimum Required Version</label>
                        <input type="text" name="min_app_version" id="min_app_version" value="{{ $minAppVersion }}" placeholder="e.g. 1.0.0" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        <p class="text-xs text-gray-500">Older versions will be forced to update.</p>
                    </div>
                </div>
                <div class="mt-4 space-y-2">
                    <label for="app_update_url" class="block text-sm font-medium text-gray-700">App Store / Play Store URL</label>
                    <input type="url" name="app_update_url" id="app_update_url" value="{{ $appUpdateUrl ?? '' }}" placeholder="https://play.google.com/store/apps/details?id=..." class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                    <p class="text-xs text-gray-500">The URL users will be redirected to when tapping 'Update'.</p>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition-colors">
                    Save Changes
                </button>
            </div>

            <script>
                document.querySelector('input[name=primary_color]').addEventListener('input', function(e) {
                    document.getElementById('color_hex').value = e.target.value.toUpperCase();
                });
            </script>
        </div>
    </form>
</div>
@endsection

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

            <hr class="border-gray-200">

            <hr class="border-gray-200">

            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">Footer Configuration</h3>
                <p class="text-xs text-gray-500">Manage the content and links displayed in the store footer.</p>
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="footer_about_text" class="block text-sm font-medium text-gray-700">About US Text</label>
                        <textarea name="footer_about_text" id="footer_about_text" rows="3" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">{{ $footerAboutText }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="footer_address" class="block text-sm font-medium text-gray-700">Business Address</label>
                            <input type="text" name="footer_address" id="footer_address" value="{{ $footerAddress }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        </div>
                        <div class="space-y-2">
                            <label for="footer_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <input type="text" name="footer_phone" id="footer_phone" value="{{ $footerPhone }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="footer_facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                            <input type="text" name="footer_facebook_url" id="footer_facebook_url" value="{{ $footerFacebookUrl }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        </div>
                        <div class="space-y-2">
                            <label for="footer_instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                            <input type="text" name="footer_instagram_url" id="footer_instagram_url" value="{{ $footerInstagramUrl }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        </div>
                        <div class="space-y-2">
                            <label for="footer_youtube_url" class="block text-sm font-medium text-gray-700">YouTube URL</label>
                            <input type="text" name="footer_youtube_url" id="footer_youtube_url" value="{{ $footerYoutubeUrl }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="footer_copyright_text" class="block text-sm font-medium text-gray-700">Copyright Text</label>
                        <input type="text" name="footer_copyright_text" id="footer_copyright_text" value="{{ $footerCopyrightText }}" class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-black focus:border-black sm:text-sm">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-gray-900">Payment Methods</h3>
                <p class="text-xs text-gray-500">Enable or disable payment methods shown in the footer and checkout.</p>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20.067 8.178c-.652-3.1-3.69-4.116-6.6-4.116H7.135a.611.611 0 0 0-.613.518L4 19.336a.417.417 0 0 0 .411.486h3.193a.611.611 0 0 0 .611-.518l.844-5.343a.611.611 0 0 1 .61-.518h1.613c3.21 0 5.728-1.305 6.467-5.11.23-.974.282-1.851.318-2.655zM17.6 8.527c-.452 2.336-2.107 3.32-4.526 3.32h-1.34a1.222 1.222 0 0 0-1.221 1.036l-.16.945-.443 2.801h-2.5l2.67-16.94c.03-.189.196-.33.388-.33h5.729c1.92 0 3.754.409 4.316 2.842.13.565.178 1.13.15 1.636-.08 1.489-.481 2.946-1.063 4.69z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">PayPal</h4>
                                <p class="text-xs text-gray-500">Show PayPal icon in the footer</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="payment_paypal_enabled" value="1" {{ $paypalEnabled == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Bank Transfer</h4>
                                    <p class="text-xs text-gray-500">Show Bank icon and account details</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="payment_bank_enabled" value="1" {{ $bankEnabled == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        <div class="mt-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bank Account / Payment Details</label>
                            <textarea name="payment_bank_details" rows="2" class="block w-full border border-gray-200 rounded-md shadow-sm p-2 text-xs focus:ring-green-500 focus:border-green-500" placeholder="Bank: Example Bank&#10;AC: 123456789&#10;BSB: 000-000">{{ $bankDetails }}</textarea>
                        </div>
                    </div>
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

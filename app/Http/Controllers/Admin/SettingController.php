<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $maintenanceMode = SiteSetting::getValue('maintenance_mode', '0');
        $primaryColor = SiteSetting::getValue('primary_color', '#5eba7d');
        $appVersion = SiteSetting::getValue('app_version', '1.0.0');
        $minAppVersion = SiteSetting::getValue('min_app_version', '1.0.0');
        $appUpdateUrl = SiteSetting::getValue('app_update_url', 'https://play.google.com/store/apps/details?id=com.sunil.nepstrading.ecommerce.app');
        $siteLogo = SiteSetting::getValue('site_logo', '');

        
        $paypalEnabled = SiteSetting::getValue('payment_paypal_enabled', '0');
        $bankEnabled = SiteSetting::getValue('payment_bank_enabled', '0');
        $bankDetails = SiteSetting::getValue('payment_bank_details', '');

        // Footer Settings
        $footerAboutText = SiteSetting::getValue('footer_about_text', 'Premium neighborhood grocery for artisanal produce, daily essentials, and unique global finds.');
        $footerAddress = SiteSetting::getValue('footer_address', '123 Market St, Sydney NSW 2000');
        $footerPhone = SiteSetting::getValue('footer_phone', '+61 4XX XXX XXX');
        $footerFacebookUrl = SiteSetting::getValue('footer_facebook_url', '#');
        $footerInstagramUrl = SiteSetting::getValue('footer_instagram_url', '#');
        $footerYoutubeUrl = SiteSetting::getValue('footer_youtube_url', '#');
        $footerCopyrightText = SiteSetting::getValue('footer_copyright_text', 'Nepstrading. Built for Excellence.');

        return view('admin.settings.index', compact(
            'maintenanceMode', 'primaryColor', 'appVersion', 'minAppVersion', 'appUpdateUrl', 'siteLogo',
            'paypalEnabled', 'bankEnabled', 'bankDetails',
            'footerAboutText', 'footerAddress', 'footerPhone', 'footerFacebookUrl', 'footerInstagramUrl', 'footerYoutubeUrl', 'footerCopyrightText'
        ));
    }

    public function update(Request $request)
    {
        SiteSetting::setValue('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');
        
        if ($request->has('primary_color')) {
            SiteSetting::setValue('primary_color', $request->primary_color);
        }

        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('site', 'public');
            SiteSetting::setValue('site_logo', $path);
        }


        if ($request->has('app_version')) {
            SiteSetting::setValue('app_version', $request->app_version);
        }

        if ($request->has('min_app_version')) {
            SiteSetting::setValue('min_app_version', $request->min_app_version);
        }

        if ($request->has('app_update_url')) {
            SiteSetting::setValue('app_update_url', $request->app_update_url);
        }

        SiteSetting::setValue('payment_paypal_enabled', $request->has('payment_paypal_enabled') ? '1' : '0');
        SiteSetting::setValue('payment_bank_enabled', $request->has('payment_bank_enabled') ? '1' : '0');
        SiteSetting::setValue('payment_bank_details', $request->payment_bank_details ?? '');

        // Footer Settings
        SiteSetting::setValue('footer_about_text', $request->footer_about_text ?? '');
        SiteSetting::setValue('footer_address', $request->footer_address ?? '');
        SiteSetting::setValue('footer_phone', $request->footer_phone ?? '');
        SiteSetting::setValue('footer_facebook_url', $request->footer_facebook_url ?? '');
        SiteSetting::setValue('footer_instagram_url', $request->footer_instagram_url ?? '');
        SiteSetting::setValue('footer_youtube_url', $request->footer_youtube_url ?? '');
        SiteSetting::setValue('footer_copyright_text', $request->footer_copyright_text ?? '');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}

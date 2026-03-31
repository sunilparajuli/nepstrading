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
        
        // Payment Methods
        $paypalEnabled = SiteSetting::getValue('payment_paypal_enabled', '0');
        $bankEnabled = SiteSetting::getValue('payment_bank_enabled', '0');
        $bankDetails = SiteSetting::getValue('payment_bank_details', '');

        return view('admin.settings.index', compact(
            'maintenanceMode', 'primaryColor', 'appVersion', 'minAppVersion', 'appUpdateUrl',
            'paypalEnabled', 'bankEnabled', 'bankDetails'
        ));
    }

    public function update(Request $request)
    {
        SiteSetting::setValue('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');
        
        if ($request->has('primary_color')) {
            SiteSetting::setValue('primary_color', $request->primary_color);
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

        // Payment Methods
        SiteSetting::setValue('payment_paypal_enabled', $request->has('payment_paypal_enabled') ? '1' : '0');
        SiteSetting::setValue('payment_bank_enabled', $request->has('payment_bank_enabled') ? '1' : '0');
        SiteSetting::setValue('payment_bank_details', $request->payment_bank_details ?? '');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}

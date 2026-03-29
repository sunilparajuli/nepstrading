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
        
        return view('admin.settings.index', compact('maintenanceMode', 'primaryColor', 'appVersion', 'minAppVersion', 'appUpdateUrl'));
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

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}

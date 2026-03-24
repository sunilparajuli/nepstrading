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
        $primaryColor = SiteSetting::getValue('primary_color', '#5eba7d'); // Default green
        return view('admin.settings.index', compact('maintenanceMode', 'primaryColor'));
    }

    public function update(Request $request)
    {
        SiteSetting::setValue('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');
        
        if ($request->has('primary_color')) {
            SiteSetting::setValue('primary_color', $request->primary_color);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class SettingsApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'store_name' => 'Nepstrading',
            'currency' => 'AUD',
            'currency_symbol' => '$',
            'primary_color' => SiteSetting::getValue('primary_color', '#5eba7d'),
            'maintenance_mode' => SiteSetting::getValue('maintenance_mode', false),
        ]);
    }
}

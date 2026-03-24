<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance = \App\Models\SiteSetting::getValue('maintenance_mode', '0');

        if ($maintenance === '1') {
            $user = $request->user();
            
            // Bypass for admins
            if ($user && $user->is_admin) {
                return $next($request);
            }

            // Exclude login/logout/register/admin routes
            if ($request->is('login') || $request->is('logout') || $request->is('register') || $request->is('admin*')) {
                return $next($request);
            }

            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}

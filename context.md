# Project Context & Architecture

This file serves as a memory store for future AI sessions to immediately understand the current state of the application without starting from scratch.

## Infrastructure & Server
- **Server Address**: `170.64.175.81`
- **User**: `root`
- **Password**: `OnePiece@2026!`
- **Root Directory**: `/var/www/nepstrading`
- **Web Server**: Nginx (Replaced Apache to resolve high memory usage and crashes)
- **PHP Version**: 8.3
- **Process Manager**: Systemd (`octane.service` manages the Laravel API)
- **High-Performance API**: **Laravel Octane (RoadRunner)** is deployed. Nginx (`/etc/nginx/sites-available/nepstrading`) acts as a reverse proxy forwarding requests to `http://127.0.0.1:8000`.
- **Caching**: Memcached is installed and running.

## Application Architecture

### 1. Web Frontend (Laravel Blade)
- **Theme**: Premium custom UI tailored to modern e-commerce standards, heavy use of `hsl()` CSS variables and glassmorphism.
- **Navigation**:
  - Desktop uses a rich mega-menu.
  - Mobile features an animated off-canvas "Hamburger" drawer menu for pristine responsiveness.
- **Custom Error Pages**: Designed custom `404`, `500`, `403`, and `503` templates based on a shared, premium `layout.blade.php`.

### 2. Mobile App (Flutter)
- **SDK**: Flutter v3.32.8 (ARM) - located at `~/sdks/flutter_arm-3.32.8/`
- **Location**: `mobile_app/` directory.
- **UI Elements**: Uses `google_nav_bar` for an animated, fluid bottom navigation bar on the `HomeView`.
- **App Lifecycle & Updates**: 
  - The `SplashController` intercepts app load, querying `/api/settings`.
  - It compares the native app version against `min_app_version` and `app_version` configured in the backend.
  - Presents mandatory (non-dismissible) or optional (dismissible) update dialogs, redirecting to `app_update_url`.

### 3. Play Store Publishing Status
- **Store Identity**: 
  - **Package Name**: `com.nepstrading.ecommerce` (Successfully refactored from `mobile_app`).
  - **App Label**: "Nepstrading".
- **Assets**: 
  - High-res Logo, Feature Graphic (1024x500), and UI Mockups generated and stored in `mobile_app/assets/play_store/`.
  - Adaptive launcher icons generated via `flutter_launcher_icons`.
- **Release Signing**: 
  - **Keystore**: `/Users/hisoka/flutter_apps/keystore/all.keystore`.
  - **Config**: Configured via `mobile_app/android/key.properties` (secured/ignored).
  - **Fingerprints (SHA-1)**: `6C:0C:7E:37:38:32:72:5C:99:9B:39:6E:CA:88:87:BB:10:46:A6:74`.
- **Build Status**: Production App Bundle (`app-release.aab`) successfully built and verified for upload.
- **Pending Compliance**: 
  - Requires a **Privacy Policy URL** (mandated by Google).
  - Needs **Data Safety Declaration** in the Play Console.

### 4. Backend (Laravel API & Admin)
- **Version**: Laravel 12.56.0
- **Admin Panel**: Re-secured. Routes are mapped to `/adminpanel` for obfuscation.
- **Middleware**: Guarded endpoints. Orders (`OrderApiController`) are strictly rejected with HTTP `503` when `maintenance_mode` is enabled.
- **Settings Store**: `SiteSetting` model handles dynamic app variables (`maintenance_mode`, `app_version`, `primary_color`, `logo`, etc.).

## Recent System Maintenance Logs
- Recovered from crashed MySQL tables (`users`, `oauth_auth_codes`) via manual `myisamchk` repairs.
- Patched an Nginx double-direction infinite loop by modifying Laravel's `trustProxies(at: '*')` middleware and forcing the HTTPS scheme.
- Fixed 500 server errors on the frontend shop caused by missing JSON-LD `@@context` Blade escaping.
- Backed up Nginx & Systemd config files manually to `server-configs/` folder on GitHub.
- **Mobile Prep (2024-04-21)**: Configured production signing, generated branding assets, and successfully built the first release App Bundle.

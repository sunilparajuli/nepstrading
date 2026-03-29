import 'package:get/get.dart';
import 'package:flutter/material.dart';
import 'package:package_info_plus/package_info_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/services/settings_service.dart';
import '../../core/theme/app_theme.dart';
import '../../routes/app_routes.dart';

class SplashController extends GetxController {
  @override
  void onInit() {
    super.onInit();
    _initializeApp();
  }

  void _initializeApp() async {
    // 1. Fetch Global Settings (Theme & Maintenance)
    final settingsService = Get.find<SettingsService>();
    await settingsService.fetchSettings();

    // 2. Apply Dynamic Theme
    final primaryColor = AppTheme.hexToColor(settingsService.primaryColorHex);
    Get.changeTheme(AppTheme.getTheme(primaryColor: primaryColor));

    // 3. Artificial delay for branding
    await Future.delayed(const Duration(seconds: 1));

    // 4. Check for Maintenance Mode
    if (settingsService.isMaintenanceMode) {
      Get.offAllNamed(Routes.MAINTENANCE);
      return;
    }

    // 5. Check App Version
    await _checkAppVersion(settingsService);
  }

  Future<void> _checkAppVersion(SettingsService settings) async {
    final targetVersion = settings.appVersion;
    final minVersion = settings.minAppVersion;
    final updateUrl = settings.appUpdateUrl;

    if (targetVersion == '1.0.0' && minVersion == '1.0.0') {
      _navigateToNext();
      return;
    }

    try {
      final packageInfo = await PackageInfo.fromPlatform();
      final currentVersion = packageInfo.version;

      int cmpTarget = _compareVersions(currentVersion, targetVersion);
      int cmpMin = _compareVersions(currentVersion, minVersion);

      if (cmpMin < 0) {
        // Mandatory update
        _showUpdateDialog(true, updateUrl);
        return;
      } else if (cmpTarget < 0) {
        // Optional update
        _showUpdateDialog(false, updateUrl);
        return;
      }
    } catch (e) {
      print('Version Check Error: $e');
    }

    // No update needed
    _navigateToNext();
  }

  void _navigateToNext() {
    Get.offAllNamed(Routes.HOME);
  }

  int _compareVersions(String v1, String v2) {
    List<int> p1 = v1.split('.').map((e) => int.tryParse(e) ?? 0).toList();
    List<int> p2 = v2.split('.').map((e) => int.tryParse(e) ?? 0).toList();
    for (int i = 0; i < 3; i++) {
      int a = i < p1.length ? p1[i] : 0;
      int b = i < p2.length ? p2[i] : 0;
      if (a < b) return -1;
      if (a > b) return 1;
    }
    return 0;
  }

  void _showUpdateDialog(bool isMandatory, String url) {
    Get.dialog(
      PopScope(
        canPop: !isMandatory,
        child: AlertDialog(
          title: Text(isMandatory ? 'Update Required' : 'Update Available'),
          content: Text(
            isMandatory 
             ? 'A new version of the app is required to continue using our services. Please update to the latest version.'
             : 'A new version of the app is available with new features and improvements. Would you like to update now?'
          ),
          actions: [
            if (!isMandatory)
              TextButton(
                onPressed: () {
                  Get.back();
                  _navigateToNext();
                },
                child: const Text('Later'),
              ),
            ElevatedButton(
              onPressed: () async {
                final uri = Uri.parse(url);
                if (await canLaunchUrl(uri)) {
                  await launchUrl(uri, mode: LaunchMode.externalApplication);
                }
              },
              child: const Text('Update Now'),
            ),
          ],
        ),
      ),
      barrierDismissible: !isMandatory,
    );
  }
}

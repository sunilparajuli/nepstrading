import 'package:get/get.dart';
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

    // 5. Navigate to Home
    Get.offAllNamed(Routes.HOME);
  }
}

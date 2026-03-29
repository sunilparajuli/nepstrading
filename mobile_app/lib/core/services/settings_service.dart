import 'package:get/get.dart';
import '../models/settings_model.dart';
import '../network/dio_client.dart';

class SettingsService extends GetxService {
  final settings = Rxn<AppSettings>();
  final isLoading = true.obs;

  Future<SettingsService> init() async {
    await fetchSettings();
    return this;
  }

  Future<void> fetchSettings() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/settings');
      if (res.statusCode == 200) {
        settings.value = AppSettings.fromJson(res.data);
      }
    } catch (e) {
      print('Fetch Settings Error: $e');
      // Use defaults if fetch fails
      settings.value = AppSettings(
        storeName: 'Nepstrading',
        currency: 'AUD',
        currencySymbol: '\$',
        primaryColor: '#F15F22',
        maintenanceMode: false,
        appVersion: '1.0.0',
        minAppVersion: '1.0.0',
        appUpdateUrl: 'https://play.google.com/store/apps/details?id=com.sunil.nepstrading.ecommerce.app',
      );
    } finally {
      isLoading.value = false;
    }
  }

  bool get isMaintenanceMode => settings.value?.maintenanceMode ?? false;
  String get primaryColorHex => settings.value?.primaryColor ?? '#F15F22';
  String get appVersion => settings.value?.appVersion ?? '1.0.0';
  String get minAppVersion => settings.value?.minAppVersion ?? '1.0.0';
  String get appUpdateUrl => settings.value?.appUpdateUrl ?? '';
}

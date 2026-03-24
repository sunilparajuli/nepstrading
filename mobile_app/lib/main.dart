import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../core/theme/app_theme.dart';
import '../routes/app_pages.dart';
import '../core/network/dio_client.dart';
import '../modules/cart/cart_controller.dart';
import '../core/services/auth_service.dart';
import '../core/services/settings_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // 1. Initialize Essential Services
  Get.put(DioClient(), permanent: true);
  
  // 2. Initialize Settings & Auth
  await Get.putAsync(() => SettingsService().init());
  await Get.putAsync(() => AuthService().init());
  
  // 3. Initialize Global Controllers
  Get.put(CartController(), permanent: true);

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      title: 'Nepstrading',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      initialRoute: AppPages.INITIAL,
      getPages: AppPages.routes,
      defaultTransition: Transition.cupertino,
    );
  }
}

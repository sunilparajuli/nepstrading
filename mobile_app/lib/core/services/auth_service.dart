import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../constants/app_constants.dart';
import '../models/user_model.dart';

class AuthService extends GetxService {
  final isLoggedIn = false.obs;
  final isGuest = false.obs;
  final user = Rxn<UserModel>();

  Future<AuthService> init() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(AppConstants.tokenKey);
    
    if (token != null) {
      isLoggedIn.value = true;
      isGuest.value = false;
      // In a real app, you might load the user from cache or API here
    } else {
      isLoggedIn.value = false;
      isGuest.value = true;
    }
    return this;
  }

  void login(String token, Map<String, dynamic> userData) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConstants.tokenKey, token);
    user.value = UserModel.fromJson(userData);
    isLoggedIn.value = true;
    isGuest.value = false;
    Get.offAllNamed('/home');
  }

  void logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(AppConstants.tokenKey);
    await prefs.remove(AppConstants.userKey);
    isLoggedIn.value = false;
    isGuest.value = true;
    user.value = null;
    Get.offAllNamed('/auth');
  }

  void skipLogin() {
    isLoggedIn.value = false;
    isGuest.value = true;
    Get.offAllNamed('/home');
  }
}

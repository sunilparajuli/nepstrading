import 'package:get/get.dart';
import '../../core/models/user_model.dart';
import '../../core/network/dio_client.dart';
import '../../core/services/auth_service.dart';

class ProfileController extends GetxController {
  final _authService = Get.find<AuthService>();
  final isLoading = true.obs;
  final user = Rxn<UserModel>();

  bool get isGuest => _authService.isGuest.value;

  @override
  void onInit() {
    super.onInit();
    if (!isGuest) {
      fetchProfile();
    } else {
      isLoading.value = false;
    }
  }

  Future<void> fetchProfile() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/user/profile');
      if (res.statusCode == 200) {
        user.value = UserModel.fromJson(res.data);
      }
    } catch (e) {
      print('Fetch Profile Error: $e');
    } finally {
      isLoading.value = false;
    }
  }

  void logout() {
    _authService.logout();
  }

  Future<void> deleteAccount() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      await dio.delete('/user/account');
      _authService.logout();
      Get.snackbar('Success', 'Your account and data have been deleted.', snackPosition: SnackPosition.BOTTOM);
    } catch (e) {
      Get.snackbar('Error', 'Failed to delete account. Please try again.', snackPosition: SnackPosition.BOTTOM);
    } finally {
      isLoading.value = false;
    }
  }
}

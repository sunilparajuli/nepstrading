import 'package:get/get.dart';
import '../../core/models/wishlist_model.dart';
import '../../core/network/dio_client.dart';
import '../../core/services/auth_service.dart';

class WishlistController extends GetxController {
  final _authService = Get.find<AuthService>();
  final isLoading = true.obs;
  final wishlistItems = <WishlistModel>[].obs;

  bool get isGuest => _authService.isGuest.value;

  @override
  void onInit() {
    super.onInit();
    if (!isGuest) {
      fetchWishlist();
    } else {
      isLoading.value = false;
    }
  }

  Future<void> fetchWishlist() async {
    if (isGuest) return;
    
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/wishlist');
      if (res.statusCode == 200) {
        wishlistItems.assignAll(
          (res.data as List).map((e) => WishlistModel.fromJson(e)).toList()
        );
      }
    } catch (e) {
      print('Fetch Wishlist Error: $e');
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> removeFromWishlist(int id) async {
    if (isGuest) return;
    
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.delete('/wishlist/$id');
      if (res.statusCode == 200) {
        wishlistItems.removeWhere((item) => item.id == id);
        Get.snackbar('Success', 'Removed from wishlist', snackPosition: SnackPosition.BOTTOM);
      }
    } catch (e) {
      Get.snackbar('Error', 'Could not remove item from wishlist.', snackPosition: SnackPosition.BOTTOM);
    }
  }

  void showLoginPrompt() {
    Get.defaultDialog(
      title: 'Login Required',
      middleText: 'Please login to add items to your wishlist.',
      textConfirm: 'Login',
      textCancel: 'Cancel',
      onConfirm: () => Get.offAllNamed('/auth'),
    );
  }
}

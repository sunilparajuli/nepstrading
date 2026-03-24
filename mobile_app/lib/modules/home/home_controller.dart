import 'package:get/get.dart';
import '../../core/models/product_model.dart';
import '../../core/models/category_model.dart';
import '../../core/network/dio_client.dart';
import '../../core/services/auth_service.dart';

class HomeController extends GetxController {
  final _authService = Get.find<AuthService>();
  final isLoading = true.obs;
  
  final categories = <Category>[].obs;
  final products = <Product>[].obs;
  
  final searchQuery = ''.obs;
  final selectedCategoryId = Rxn<int>();

  @override
  void onInit() {
    super.onInit();
    fetchHomeData();
    debounce(searchQuery, (_) => fetchProducts(), time: const Duration(milliseconds: 500));
  }

  Future<void> fetchHomeData() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      
      final categoryRes = await dio.get('/categories');
      if (categoryRes.statusCode == 200) {
        categories.assignAll((categoryRes.data as List)
            .map((e) => Category.fromJson(e))
            .toList());
      }

      await fetchProducts(showLoader: false);
      
    } catch (e) {
      print('API Fetch Error: $e');
      Get.snackbar('Error', 'Could not sync with server.');
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> fetchProducts({bool showLoader = true}) async {
    if (showLoader) isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final Map<String, dynamic> queryParams = {};
      
      if (searchQuery.value.isNotEmpty) {
        queryParams['search'] = searchQuery.value;
      }
      if (selectedCategoryId.value != null) {
        queryParams['category'] = selectedCategoryId.value.toString();
      }

      final productRes = await dio.get('/products', queryParameters: queryParams);
      if (productRes.statusCode == 200) {
        final List items = productRes.data['data'] ?? [];
        products.assignAll(items.map((e) => Product.fromJson(e)).toList());
      }
    } catch (e) {
      print('API Fetch Product Error: $e');
    } finally {
      if (showLoader) isLoading.value = false;
    }
  }

  void onCategorySelected(int? id) {
    selectedCategoryId.value = id;
    fetchProducts();
  }

  void toggleWishlist(Product product) async {
    if (_authService.isGuest.value) {
      Get.defaultDialog(
        title: 'Login Required',
        middleText: 'Please login to save your favorite items.',
        textConfirm: 'Login',
        textCancel: 'Later',
        onConfirm: () => Get.offAllNamed('/auth'),
      );
      return;
    }

    try {
      final dio = Get.find<DioClient>().dio;
      // Optimistically add/remove or just call API
      final res = await dio.post('/wishlist', data: {'product_id': product.id});
      if (res.statusCode == 201 || res.statusCode == 200) {
        Get.snackbar('Success', '${product.name} added to wishlist', snackPosition: SnackPosition.BOTTOM);
      }
    } catch (e) {
      Get.snackbar('Error', 'Could not update wishlist.', snackPosition: SnackPosition.BOTTOM);
    }
  }
}

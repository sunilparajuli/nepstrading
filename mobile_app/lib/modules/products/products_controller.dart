import 'package:get/get.dart';
import 'package:dio/dio.dart';
import '../../core/models/product_model.dart';
import '../../core/models/category_model.dart';
import '../../core/network/dio_client.dart';
import '../../core/services/auth_service.dart';

class ProductsController extends GetxController {
  final products = <Product>[].obs;
  final categories = <Category>[].obs;
  final isLoading = false.obs;

  // Filter States
  final selectedCategoryId = Rxn<int>();
  final minPrice = Rxn<double>();
  final maxPrice = Rxn<double>();
  final sortBy = 'featured'.obs; // featured, name_asc, price_asc, price_desc
  final searchQuery = ''.obs;
  final stockStatus = 'all'.obs; // all, in, out

  @override
  void onInit() {
    super.onInit();
    // Load arguments if passed from Home (e.g. selected category)
    if (Get.arguments is int) {
      selectedCategoryId.value = Get.arguments;
    }
    fetchInitialData();
    
    // Auto-fetch on filter changes
    debounce(searchQuery, (_) => fetchProducts(), time: const Duration(milliseconds: 500));
    ever(selectedCategoryId, (_) => fetchProducts());
    ever(sortBy, (_) => fetchProducts());
    ever(stockStatus, (_) => fetchProducts());
  }

  Future<void> fetchInitialData() async {
    isLoading.value = true;
    await Future.wait([
      fetchCategories(),
      fetchProducts(),
    ]);
    isLoading.value = false;
  }

  Future<void> fetchCategories() async {
    try {
      final dio = Get.find<DioClient>().dio;
      final response = await dio.get('/categories');
      if (response.statusCode == 200) {
        final List data = response.data;
        categories.assignAll(data.map((e) => Category.fromJson(e)).toList());
      }
    } catch (e) {
      print('Error fetching categories: $e');
    }
  }

  Future<void> fetchProducts() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final queryParams = <String, dynamic>{};
      
      if (selectedCategoryId.value != null) queryParams['category'] = selectedCategoryId.value;
      if (minPrice.value != null) queryParams['min_price'] = minPrice.value;
      if (maxPrice.value != null) queryParams['max_price'] = maxPrice.value;
      if (sortBy.value != 'featured') queryParams['sort'] = sortBy.value;
      if (searchQuery.value.isNotEmpty) queryParams['search'] = searchQuery.value;
      if (stockStatus.value != 'all') queryParams['stock'] = stockStatus.value;

      final response = await dio.get('/products', queryParameters: queryParams);
      if (response.statusCode == 200) {
        final List data = response.data['data'];
        products.assignAll(data.map((e) => Product.fromJson(e)).toList());
      }
    } catch (e) {
      print('Error fetching products: $e');
    } finally {
      isLoading.value = false;
    }
  }

  void resetFilters() {
    selectedCategoryId.value = null;
    minPrice.value = null;
    maxPrice.value = null;
    sortBy.value = 'featured';
    searchQuery.value = '';
    stockStatus.value = 'all';
    fetchProducts();
  }

  void toggleWishlist(Product product) async {
    final authService = Get.find<AuthService>();
    if (authService.isGuest.value) {
      Get.defaultDialog(
        title: 'Login Required',
        middleText: 'Please login to add items to your wishlist.',
        textConfirm: 'Login',
        textCancel: 'Later',
        onConfirm: () => Get.offAllNamed('/auth'),
      );
      return;
    }

    try {
      final dio = Get.find<DioClient>().dio;
      final response = await dio.post('/wishlist/toggle', data: {'product_id': product.id});
      if (response.statusCode == 200) {
        Get.snackbar('Success', response.data['message'] ?? 'Wishlist updated');
      }
    } catch (e) {
      print('Wishlist Toggle Error: $e');
    }
  }
}

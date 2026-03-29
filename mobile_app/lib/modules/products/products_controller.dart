import 'package:flutter/material.dart';
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
  final isMoreLoading = false.obs;
  final isSearchVisible = false.obs;
  final ScrollController scrollController = ScrollController();

  // Pagination State
  final currentPage = 1.obs;
  final lastPage = 1.obs;
  final totalProducts = 0.obs;
  final perPage = 12;

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
    
    // Setup Scroll Listener
    scrollController.addListener(() {
      if (scrollController.position.pixels >= scrollController.position.maxScrollExtent - 200) {
        loadMore();
      }
    });

    // Auto-fetch on filter changes
    debounce(searchQuery, (_) => resetAndFetch(), time: const Duration(milliseconds: 500));
    ever(selectedCategoryId, (_) => resetAndFetch());
    ever(sortBy, (_) => resetAndFetch());
    ever(stockStatus, (_) => resetAndFetch());
  }

  @override
  void onClose() {
    scrollController.dispose();
    super.onClose();
  }

  void resetAndFetch() {
    currentPage.value = 1;
    products.clear();
    fetchProducts();
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

  Future<void> fetchProducts({bool isLoadMore = false}) async {
    if (isLoadMore) {
      isMoreLoading.value = true;
    } else {
      isLoading.value = true;
    }

    try {
      final dio = Get.find<DioClient>().dio;
      final queryParams = <String, dynamic>{
        'page': currentPage.value,
        'per_page': perPage,
      };
      
      if (selectedCategoryId.value != null) queryParams['category'] = selectedCategoryId.value;
      if (minPrice.value != null) queryParams['min_price'] = minPrice.value;
      if (maxPrice.value != null) queryParams['max_price'] = maxPrice.value;
      if (sortBy.value != 'featured') queryParams['sort'] = sortBy.value;
      if (searchQuery.value.isNotEmpty) queryParams['search'] = searchQuery.value;
      if (stockStatus.value != 'all') queryParams['stock'] = stockStatus.value;

      final response = await dio.get('/products', queryParameters: queryParams);
      if (response.statusCode == 200) {
        final List data = response.data['data'];
        final newProducts = data.map((e) => Product.fromJson(e)).toList();
        
        if (isLoadMore) {
          products.addAll(newProducts);
        } else {
          products.assignAll(newProducts);
        }
        
        currentPage.value = response.data['current_page'];
        lastPage.value = response.data['last_page'];
        totalProducts.value = response.data['total'];
      }
    } catch (e) {
      print('Error fetching products: $e');
    } finally {
      isLoading.value = false;
      isMoreLoading.value = false;
    }
  }

  void loadMore() {
    if (currentPage.value < lastPage.value && !isMoreLoading.value && !isLoading.value) {
      currentPage.value++;
      fetchProducts(isLoadMore: true);
    }
  }

  void toggleSearch() {
    isSearchVisible.value = !isSearchVisible.value;
    if (!isSearchVisible.value) {
      searchQuery.value = '';
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

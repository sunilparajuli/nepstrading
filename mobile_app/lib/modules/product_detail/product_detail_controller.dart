import 'package:get/get.dart';
import '../../core/models/product_model.dart';
import '../cart/cart_controller.dart';
import '../../core/services/auth_service.dart';
import '../../core/network/dio_client.dart';
import 'package:flutter/material.dart';

class ProductDetailController extends GetxController {
  final _authService = Get.find<AuthService>();
  final product = Rxn<Product>();
  final isLoading = false.obs;
  
  // Dynamic Attributes
  final groupedAttributes = <String, List<AttributeTermModel>>{}.obs;
  final selectedAttributes = <String, String>{}.obs;
  
  final quantity = 1.obs;

  @override
  void onInit() {
    super.onInit();
    if (Get.arguments is Product) {
      product.value = Get.arguments;
      fetchProductDetails(product.value!.id.toString());
    }
  }

  Future<void> fetchProductDetails(String idOrSlug) async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/products/$idOrSlug');
      if (res.statusCode == 200) {
        product.value = Product.fromJson(res.data);
        _groupAttributes();
      }
    } catch (e) {
      print('Fetch Product Detail Error: $e');
    } finally {
      isLoading.value = false;
    }
  }

  void _groupAttributes() {
    if (product.value?.attributes == null) return;
    
    final groups = <String, List<AttributeTermModel>>{};
    for (var term in product.value!.attributes!) {
      final attrName = term.attribute?.name ?? 'Options';
      groups.putIfAbsent(attrName, () => []).add(term);
    }
    groupedAttributes.assignAll(groups);
    
    // Initialize selections with first available term for each attribute
    final initialSelections = <String, String>{};
    groups.forEach((key, value) {
      if (value.isNotEmpty) {
        initialSelections[key] = value.first.name;
      }
    });
    selectedAttributes.assignAll(initialSelections);
  }

  void selectAttribute(String attrName, String termName) {
    selectedAttributes[attrName] = termName;
  }

  void incrementQuantity() {
    quantity.value++;
  }

  void decrementQuantity() {
    if (quantity.value > 1) {
      quantity.value--;
    }
  }

  void toggleWishlist() async {
    if (product.value == null) return;
    
    if (_authService.isGuest.value) {
      Get.defaultDialog(
        title: 'Login Required',
        middleText: 'Please login to save this item to your wishlist.',
        textConfirm: 'Login',
        textCancel: 'Cancel',
        confirmTextColor: Colors.white,
        onConfirm: () => Get.offAllNamed('/auth'),
      );
      return;
    }

    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.post('/wishlist', data: {'product_id': product.value!.id});
      if (res.statusCode == 201 || res.statusCode == 200) {
        Get.snackbar('Success', 'Wishlist updated', snackPosition: SnackPosition.BOTTOM);
      }
    } catch (e) {
      Get.snackbar('Error', 'Could not update wishlist.', snackPosition: SnackPosition.BOTTOM);
    }
  }

  void addToCart() {
    if (product.value != null) {
      Get.find<CartController>().addItem(
        product.value!,
        qty: quantity.value,
      );
    }
  }
}

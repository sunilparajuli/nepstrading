import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:dio/dio.dart';
import '../cart/cart_controller.dart';
import '../../core/network/dio_client.dart';

class CheckoutController extends GetxController {
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final addressController = TextEditingController();
  final cityController = TextEditingController();
  final phoneController = TextEditingController();
  
  final isLoading = false.obs;
  
  @override
  void onClose() {
    nameController.dispose();
    emailController.dispose();
    addressController.dispose();
    cityController.dispose();
    phoneController.dispose();
    super.onClose();
  }

  void placeOrder() async {
    if (nameController.text.isEmpty || emailController.text.isEmpty || addressController.text.isEmpty || cityController.text.isEmpty || phoneController.text.isEmpty) {
      Get.snackbar('Error', 'Please fill all shipping details');
      return;
    }
    isLoading.value = true;
    try {
      final cart = Get.find<CartController>().cartItems;
      if (cart.isEmpty) {
        Get.snackbar('Error', 'Your cart is empty');
        return;
      }
      
      final dio = Get.find<DioClient>().dio;
      
      // Sync local cart to Laravel Backend Cart
      for (var item in cart) {
        try {
          await dio.post('/cart/add', data: {
            'product_id': item.product.id,
            'qty': item.qty,
          });
        } catch (e) {
          print('Cart sync warning: $e');
        }
      }

      final name = nameController.text.trim();
      final parts = name.split(' ');
      final firstName = parts.isNotEmpty ? parts.first : 'User';
      final lastName = parts.length > 1 ? parts.sublist(1).join(' ') : (name.isEmpty ? 'Name' : name);

      final response = await dio.post('/checkout', data: {
        'billing_first_name': firstName,
        'billing_last_name': lastName,
        'billing_email': emailController.text.trim(),
        'billing_address': addressController.text.trim(),
        'billing_city': cityController.text.trim(),
        'billing_postcode': '0000', 
        'billing_phone': phoneController.text.trim(),
      });
      
      if (response.statusCode == 200 || response.statusCode == 201) {
        Get.find<CartController>().clearCart();
        Get.snackbar('Success', 'Order placed successfully!');
        Get.offAllNamed('/home');
      } else {
        Get.snackbar('Error', 'Failed to place order: ${response.statusCode}');
      }
    } catch (e) {
      print('Checkout Error: $e');
      String msg = 'An unexpected error occurred';
      try {
        final dynamic err = e;
        if (err.response != null) {
          msg = err.response.data['message'] ?? 'Check your connection or login';
        }
      } catch (_) {}
      Get.snackbar('Checkout Failed', msg);
    } finally {
      isLoading.value = false;
    }
  }
}

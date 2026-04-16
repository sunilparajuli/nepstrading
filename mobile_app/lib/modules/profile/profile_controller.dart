import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../core/network/dio_client.dart';

class ProfileController extends GetxController {
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final phoneController = TextEditingController();
  final addressController = TextEditingController();
  final cityController = TextEditingController();
  final stateController = TextEditingController();
  final postcodeController = TextEditingController();
  
  final isLoading = false.obs;
  final isSaving = false.obs;
  
  final ordersCount = 0.obs;
  final totalSpent = 0.0.obs;

  @override
  void onInit() {
    super.onInit();
    fetchProfile();
  }

  @override
  void onClose() {
    nameController.dispose();
    emailController.dispose();
    phoneController.dispose();
    addressController.dispose();
    cityController.dispose();
    stateController.dispose();
    postcodeController.dispose();
    super.onClose();
  }

  Future<void> fetchProfile() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/user/profile');
      if (res.statusCode == 200) {
        final data = res.data;
        nameController.text = data['name'] ?? '';
        emailController.text = data['email'] ?? '';
        phoneController.text = data['phone'] ?? '';
        addressController.text = data['address'] ?? '';
        cityController.text = data['city'] ?? '';
        stateController.text = data['state'] ?? '';
        postcodeController.text = data['postcode'] ?? '';
        
        ordersCount.value = data['orders_count'] ?? 0;
        totalSpent.value = (data['total_spent'] as num).toDouble();
      }
    } catch (e) {
      print('Fetch Profile Error: $e');
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> updateProfile() async {
    isSaving.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.post('/user/profile/update', data: {
        'name': nameController.text.trim(),
        'email': emailController.text.trim(),
        'phone': phoneController.text.trim(),
        'address': addressController.text.trim(),
        'city': cityController.text.trim(),
        'state': stateController.text.trim(),
        'postcode': postcodeController.text.trim(),
      });
      
      if (res.statusCode == 200) {
        Get.snackbar('Success', 'Profile updated successfully!');
      }
    } catch (e) {
      print('Update Profile Error: $e');
      Get.snackbar('Error', 'Failed to update profile');
    } finally {
      isSaving.value = false;
    }
  }
}

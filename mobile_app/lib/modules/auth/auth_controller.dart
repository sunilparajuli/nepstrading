import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:dio/dio.dart';
import '../../core/network/dio_client.dart';
import '../../core/constants/app_constants.dart';
import '../../core/services/auth_service.dart';

class AuthController extends GetxController {
  final DioClient _dioClient = Get.find<DioClient>();
  final AuthService _authService = Get.find<AuthService>();
  
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  final nameController = TextEditingController();
  
  final isLoading = false.obs;

  @override
  void onClose() {
    emailController.dispose();
    passwordController.dispose();
    nameController.dispose();
    super.onClose();
  }

  Future<void> login() async {
    if (emailController.text.isEmpty || passwordController.text.isEmpty) {
      Get.snackbar('Error', 'Please fill all fields');
      return;
    }

    isLoading.value = true;
    try {
      final response = await _dioClient.dio.post('/login', data: {
        'email': emailController.text,
        'password': passwordController.text,
      });

      if (response.statusCode == 200 && response.data['token'] != null) {
        _authService.login(response.data['token'], response.data['user']);
      } else {
        Get.snackbar('Error', 'Invalid credentials');
      }
    } on DioException catch (e) {
      Get.snackbar('Login Failed', e.response?.data['message'] ?? 'An error occurred');
    } finally {
      isLoading.value = false;
    }
  }

  void skipLogin() {
    _authService.skipLogin();
  }

  Future<void> register() async {
    if (nameController.text.isEmpty || emailController.text.isEmpty || passwordController.text.isEmpty) {
      Get.snackbar('Error', 'Please fill all fields');
      return;
    }

    isLoading.value = true;
    try {
      final response = await _dioClient.dio.post('/register', data: {
        'name': nameController.text,
        'email': emailController.text,
        'password': passwordController.text,
        'password_confirmation': passwordController.text,
      });

      if (response.statusCode == 201 || response.statusCode == 200) {
        Get.snackbar('Success', 'Account created successfully');
        Get.offNamed('/auth');
      }
    } on DioException catch (e) {
      Get.snackbar('Registration Failed', e.response?.data['message'] ?? 'An error occurred');
    } finally {
      isLoading.value = false;
    }
  }
}

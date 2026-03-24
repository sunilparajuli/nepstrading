import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../core/models/order_model.dart';
import '../../core/network/dio_client.dart';

class OrdersController extends GetxController {
  final orders = <OrderModel>[].obs;
  final isLoading = false.obs;

  @override
  void onInit() {
    super.onInit();
    fetchOrders();
  }

  Future<void> fetchOrders() async {
    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final response = await dio.get('/orders');
      if (response.statusCode == 200) {
        final List data = response.data['data'] ?? [];
        orders.assignAll(data.map((e) => OrderModel.fromJson(e)).toList());
      }
    } catch (e) {
      print('Error fetching orders: $e');
      Get.snackbar('Error', 'Failed to load order history', snackPosition: SnackPosition.BOTTOM);
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> cancelOrder(OrderModel order) async {
    if (order.status != 'pending') {
      Get.snackbar('Error', 'Only pending orders can be cancelled', snackPosition: SnackPosition.BOTTOM);
      return;
    }

    Get.defaultDialog(
      title: 'Cancel Order',
      middleText: 'Are you sure you want to cancel this order?',
      textConfirm: 'Yes, Cancel',
      textCancel: 'No',
      confirmTextColor: Colors.white,
      buttonColor: Colors.red,
      onConfirm: () async {
        Get.back(); // close dialog
        isLoading.value = true;
        try {
          final dio = Get.find<DioClient>().dio;
          final response = await dio.post('/orders/${order.id}/cancel');
          if (response.statusCode == 200) {
            Get.snackbar('Success', 'Order cancelled successfully', snackPosition: SnackPosition.BOTTOM);
            fetchOrders(); // Refresh list
          }
        } catch (e) {
          print('Error cancelling order: $e');
          Get.snackbar('Error', 'Failed to cancel order', snackPosition: SnackPosition.BOTTOM);
        } finally {
          isLoading.value = false;
        }
      },
    );
  }
}

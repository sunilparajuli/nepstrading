import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'checkout_controller.dart';
import '../cart/cart_controller.dart';
import '../../core/theme/app_theme.dart';

class CheckoutView extends GetView<CheckoutController> {
  const CheckoutView({super.key});

  @override
  Widget build(BuildContext context) {
    final cartController = Get.find<CartController>();

    return Scaffold(
      backgroundColor: Colors.grey.shade50,
      appBar: AppBar(
        title: const Text('Checkout'),
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildSectionHeader(LucideIcons.truck, 'Shipping Address'),
            const SizedBox(height: 15),
            _buildInputCard([
              _buildTextField(
                controller: controller.nameController,
                label: 'Full Name',
                icon: LucideIcons.user,
              ),
              _buildTextField(
                controller: controller.emailController,
                label: 'Email Address',
                icon: LucideIcons.mail,
                keyboardType: TextInputType.emailAddress,
              ),
              _buildTextField(
                controller: controller.addressController,
                label: 'Street Address',
                icon: LucideIcons.home,
              ),
              _buildTextField(
                controller: controller.cityController,
                label: 'City',
                icon: LucideIcons.building,
              ),
              _buildTextField(
                controller: controller.phoneController,
                label: 'Phone Number',
                icon: LucideIcons.phone,
                keyboardType: TextInputType.phone,
              ),
            ]),
            const SizedBox(height: 30),
            _buildSectionHeader(LucideIcons.shoppingBag, 'Order Summary'),
            const SizedBox(height: 15),
            _buildOrderSummary(cartController),
            const SizedBox(height: 40),
            ElevatedButton(
                  onPressed: () => controller.isLoading.value ? null : controller.placeOrder(),
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: Obx(() => controller.isLoading.value
                      ? const SizedBox(
                          height: 20,
                          width: 20,
                          child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                        )
                      : const Text(
                          'CONFIRM ORDER',
                          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                        )),
                ),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(IconData icon, String title) {
    return Row(
      children: [
        Icon(icon, size: 20, color: AppTheme.primaryColor),
        const SizedBox(width: 10),
        Text(
          title,
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),
      ],
    );
  }

  Widget _buildInputCard(List<Widget> children) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4)),
        ],
      ),
      child: Column(children: children.expand((w) => [w, const SizedBox(height: 12)]).toList()..removeLast()),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    TextInputType? keyboardType,
  }) {
    return TextField(
      controller: controller,
      keyboardType: keyboardType,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, size: 20),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
        filled: true,
        fillColor: Colors.grey.shade50,
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      ),
    );
  }

  Widget _buildOrderSummary(CartController cart) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppTheme.primaryColor.withOpacity(0.05),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.primaryColor.withOpacity(0.1)),
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Items Count', style: TextStyle(color: Colors.grey)),
              Obx(() => Text('${cart.cartItems.length}', style: const TextStyle(fontWeight: FontWeight.bold))),
            ],
          ),
          const Divider(height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Grand Total', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              Obx(() => Text(
                '\$${cart.totalAmount.toStringAsFixed(2)}',
                style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppTheme.primaryColor),
              )),
            ],
          ),
        ],
      ),
    );
  }
}

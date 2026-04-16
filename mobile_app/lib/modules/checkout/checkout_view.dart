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
        title: const Text('Checkout', style: TextStyle(fontWeight: FontWeight.bold)),
        centerTitle: true,
        backgroundColor: Colors.white,
        elevation: 0,
        foregroundColor: AppTheme.textColor,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Shipping Threshold Progress
            Obx(() {
              final subtotal = cartController.totalAmount;
              final threshold = controller.minOrderThreshold;
              final met = subtotal >= threshold;
              
              return Container(
                padding: const EdgeInsets.all(16),
                margin: const EdgeInsets.only(bottom: 24),
                decoration: BoxDecoration(
                  color: met ? Colors.green.shade50 : Colors.blue.shade50,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: met ? Colors.green.shade100 : Colors.blue.shade100),
                ),
                child: Column(
                  children: [
                    Row(
                      children: [
                        Icon(
                          met ? LucideIcons.checkCircle2 : LucideIcons.info, 
                          color: met ? Colors.green : Colors.blue, 
                          size: 20
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Text(
                            met 
                              ? 'Congratulations! You qualify for FREE shipping.' 
                              : 'Spend \$${(threshold - subtotal).toStringAsFixed(2)} more to get FREE shipping!',
                            style: TextStyle(
                              fontWeight: FontWeight.bold, 
                              color: met ? Colors.green.shade800 : Colors.blue.shade800,
                              fontSize: 13
                            ),
                          ),
                        ),
                      ],
                    ),
                    if (!met) ...[
                      const SizedBox(height: 12),
                      ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: LinearProgressIndicator(
                          value: subtotal / threshold,
                          backgroundColor: Colors.blue.shade100,
                          valueColor: const AlwaysStoppedAnimation<Color>(Colors.blue),
                          minHeight: 8,
                        ),
                      ),
                    ]
                  ],
                ),
              );
            }),

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
              
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildTextField(
                    controller: controller.addressController,
                    label: 'Street Address',
                    icon: LucideIcons.home,
                  ),
                  Obx(() {
                    if (controller.addressSuggestions.isEmpty) return const SizedBox();
                    return Container(
                      margin: const EdgeInsets.only(top: 4),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.grey.shade200),
                        boxShadow: [
                          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)
                        ],
                      ),
                      child: Column(
                        children: controller.addressSuggestions.map((suggestion) {
                          return ListTile(
                            leading: const Icon(LucideIcons.mapPin, size: 16),
                            title: Text(suggestion['display_name'], style: const TextStyle(fontSize: 12)),
                            dense: true,
                            onTap: () => controller.selectAddress(suggestion),
                          );
                        }).toList(),
                      ),
                    );
                  }),
                ],
              ),

              _buildTextField(
                controller: controller.cityController,
                label: 'City / Suburb',
                icon: LucideIcons.building,
              ),

              Row(
                children: [
                  Expanded(
                    child: Obx(() => _buildDropdown(
                      label: 'State',
                      icon: LucideIcons.map,
                      value: controller.selectedState.value,
                      items: controller.states,
                      onChanged: (val) {
                        if (val != null) {
                          controller.selectedState.value = val;
                          controller.fetchPostcodes(val);
                        }
                      },
                    )),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Obx(() => _buildDropdown(
                      label: 'Postcode',
                      icon: LucideIcons.navigation,
                      value: controller.selectedPostcode.value,
                      items: controller.postcodes,
                      onChanged: (val) {
                        if (val != null) {
                          controller.selectedPostcode.value = val;
                          controller.calculateShipping();
                        }
                      },
                    )),
                  ),
                ],
              ),

              _buildTextField(
                controller: controller.phoneController,
                label: 'Phone Number',
                icon: LucideIcons.phone,
                keyboardType: TextInputType.phone,
              ),
            ]),

            const SizedBox(height: 30),
            _buildSectionHeader(LucideIcons.creditCard, 'Payment Method'),
            const SizedBox(height: 15),
            _buildPaymentSelection(),

            const SizedBox(height: 30),
            _buildSectionHeader(LucideIcons.shoppingBag, 'Order Summary'),
            const SizedBox(height: 15),
            _buildOrderSummary(cartController),
            
            const SizedBox(height: 40),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => controller.isLoading.value ? null : controller.placeOrder(),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primaryColor,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 18),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                  elevation: 0,
                ),
                child: Obx(() => controller.isLoading.value
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                      )
                    : const Text(
                        'PLACE ORDER',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, letterSpacing: 1),
                      )),
              ),
            ),
            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(IconData icon, String title) {
    return Row(
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: AppTheme.primaryColor.withOpacity(0.1),
            shape: BoxShape.circle,
          ),
          child: Icon(icon, size: 18, color: AppTheme.primaryColor),
        ),
        const SizedBox(width: 12),
        Text(
          title,
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textColor),
        ),
      ],
    );
  }

  Widget _buildInputCard(List<Widget> children) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 20, offset: const Offset(0, 8)),
        ],
      ),
      child: Column(children: children.expand((w) => [w, const SizedBox(height: 16)]).toList()..removeLast()),
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
      style: const TextStyle(fontSize: 14),
      decoration: InputDecoration(
        labelText: label,
        labelStyle: TextStyle(color: Colors.grey.shade600, fontSize: 13),
        prefixIcon: Icon(icon, size: 18, color: Colors.grey.shade500),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(15), borderSide: BorderSide.none),
        filled: true,
        fillColor: Colors.grey.shade50,
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      ),
    );
  }

  Widget _buildDropdown({
    required String label,
    required IconData icon,
    required String? value,
    required List<String> items,
    required Function(String?) onChanged,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.grey.shade50,
        borderRadius: BorderRadius.circular(15),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          value: value,
          hint: Text(label, style: const TextStyle(fontSize: 13, color: Colors.grey)),
          icon: const Icon(LucideIcons.chevronDown, size: 16),
          isExpanded: true,
          items: items.map((String item) {
            return DropdownMenuItem<String>(
              value: item,
              child: Text(item, style: const TextStyle(fontSize: 14)),
            );
          }).toList(),
          onChanged: onChanged,
        ),
      ),
    );
  }

  Widget _buildPaymentSelection() {
    return Obx(() => Column(
      children: [
        _buildPaymentOption(
          'Direct Bank Transfer',
          'Make your payment directly into our bank account. Please use your Order ID as the payment reference.',
          LucideIcons.banknote,
          controller.isBankTransfer.value,
          () => controller.isBankTransfer.value = true,
        ),
        const SizedBox(height: 12),
        if (controller.isBankTransfer.value)
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.blue.shade50,
              borderRadius: BorderRadius.circular(15),
              border: Border.all(color: Colors.blue.shade100),
            ),
            child: const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Bank details:', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.blue, fontSize: 13)),
                SizedBox(height: 8),
                Text('Bank Name: NAB (National Australia Bank)', style: TextStyle(fontSize: 12, color: Colors.black87)),
                Text('BSB: 083-004', style: TextStyle(fontSize: 12, color: Colors.black87)),
                Text('Account Number: 93-851-4190', style: TextStyle(fontSize: 12, color: Colors.black87)),
                Text('Account Name: NEPS TRADING PTY LTD', style: TextStyle(fontSize: 12, color: Colors.black87)),
              ],
            ),
          ),
        const SizedBox(height: 12),
        _buildPaymentOption(
          'Cash on Delivery',
          'Pay with cash upon delivery of your items.',
          LucideIcons.truck,
          !controller.isBankTransfer.value,
          () => controller.isBankTransfer.value = false,
        ),
      ],
    ));
  }

  Widget _buildPaymentOption(String title, String subtitle, IconData icon, bool isSelected, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.primaryColor.withOpacity(0.05) : Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: isSelected ? AppTheme.primaryColor : Colors.grey.shade200),
        ),
        child: Row(
          children: [
            Icon(icon, color: isSelected ? AppTheme.primaryColor : Colors.grey, size: 20),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                  Text(subtitle, style: const TextStyle(fontSize: 11, color: Colors.grey)),
                ],
              ),
            ),
            if (isSelected) const Icon(LucideIcons.checkCircle2, color: AppTheme.primaryColor, size: 18),
          ],
        ),
      ),
    );
  }

  Widget _buildOrderSummary(CartController cart) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.grey.shade100),
      ),
      child: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Subtotal', style: TextStyle(color: Colors.grey, fontSize: 14)),
              Obx(() => Text('\$${cart.totalAmount.toStringAsFixed(2)}', style: const TextStyle(fontWeight: FontWeight.bold))),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Shipping', style: TextStyle(color: Colors.grey, fontSize: 14)),
              Obx(() => Text(
                controller.isFreeShipping.value ? 'FREE' : '\$${controller.shippingCost.value.toStringAsFixed(2)}',
                style: TextStyle(
                  fontWeight: FontWeight.bold, 
                  color: controller.isFreeShipping.value ? Colors.green : AppTheme.textColor
                ),
              )),
            ],
          ),
          const Divider(height: 32),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Grand Total', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              Obx(() {
                final total = cart.totalAmount + controller.shippingCost.value;
                return Text(
                  '\$${total.toStringAsFixed(2)}',
                  style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppTheme.primaryColor),
                );
              }),
            ],
          ),
        ],
      ),
    );
  }
}

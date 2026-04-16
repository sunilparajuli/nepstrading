import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'profile_controller.dart';
import '../../core/theme/app_theme.dart';

class ProfileView extends GetView<ProfileController> {
  const ProfileView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey.shade50,
      appBar: AppBar(
        title: const Text('My Profile', style: TextStyle(fontWeight: FontWeight.bold)),
        centerTitle: true,
        backgroundColor: Colors.white,
        elevation: 0,
        foregroundColor: AppTheme.textColor,
      ),
      body: Obx(() {
        if (controller.isLoading.value) {
          return const Center(child: CircularProgressIndicator());
        }
        
        return SingleChildScrollView(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Profile Stats
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [AppTheme.primaryColor, Color(0xFF1B5E20)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: [
                    BoxShadow(color: AppTheme.primaryColor.withOpacity(0.3), blurRadius: 20, offset: const Offset(0, 10))
                  ],
                ),
                child: Row(
                  children: [
                    _buildStat('Orders', controller.ordersCount.value.toString()),
                    Container(height: 40, width: 1, color: Colors.white24, margin: const EdgeInsets.symmetric(horizontal: 24)),
                    _buildStat('Spent', '\$${controller.totalSpent.value.toStringAsFixed(2)}'),
                  ],
                ),
              ),
              
              const SizedBox(height: 32),
              _buildSectionHeader(LucideIcons.user, 'Personal Information'),
              const SizedBox(height: 16),
              _buildInputGroup([
                _buildTextField(controller.nameController, 'Full Name', LucideIcons.user),
                _buildTextField(controller.emailController, 'Email Address', LucideIcons.mail, enabled: false),
                _buildTextField(controller.phoneController, 'Phone Number', LucideIcons.phone),
              ]),
              
              const SizedBox(height: 32),
              _buildSectionHeader(LucideIcons.home, 'Default Shipping Address'),
              const SizedBox(height: 16),
              _buildInputGroup([
                _buildTextField(controller.addressController, 'Street Address', LucideIcons.mapPin),
                _buildTextField(controller.cityController, 'City / Suburb', LucideIcons.building),
                Row(
                  children: [
                    Expanded(child: _buildTextField(controller.stateController, 'State', LucideIcons.map)),
                    const SizedBox(width: 16),
                    Expanded(child: _buildTextField(controller.postcodeController, 'Postcode', LucideIcons.navigation)),
                  ],
                ),
              ]),
              
              const SizedBox(height: 40),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () => controller.isSaving.value ? null : controller.updateProfile(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.primaryColor,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 18),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                    elevation: 5,
                    shadowColor: AppTheme.primaryColor.withOpacity(0.4),
                  ),
                  child: Obx(() => controller.isSaving.value
                      ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                      : const Text('SAVE CHANGES', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, letterSpacing: 1.2))),
                ),
              ),
              const SizedBox(height: 40),
            ],
          ),
        );
      }),
    );
  }

  Widget _buildStat(String label, String value) {
    return Expanded(
      child: Column(
        children: [
          Text(value, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
          const SizedBox(height: 4),
          Text(label, style: const TextStyle(color: Colors.white70, fontSize: 13)),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(IconData icon, String title) {
    return Row(
      children: [
        Icon(icon, size: 20, color: AppTheme.primaryColor),
        const SizedBox(width: 12),
        Text(title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textColor)),
      ],
    );
  }

  Widget _buildInputGroup(List<Widget> children) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 20, offset: const Offset(0, 8))
        ],
      ),
      child: Column(
        children: children.expand((w) => [w, const SizedBox(height: 16)]).toList()..removeLast(),
      ),
    );
  }

  Widget _buildTextField(TextEditingController controller, String label, IconData icon, {bool enabled = true}) {
    return TextField(
      controller: controller,
      enabled: enabled,
      decoration: InputDecoration(
        labelText: label,
        labelStyle: TextStyle(color: enabled ? Colors.grey.shade600 : Colors.grey.shade400, fontSize: 13),
        prefixIcon: Icon(icon, size: 18, color: enabled ? AppTheme.primaryColor.withOpacity(0.7) : Colors.grey.shade400),
        filled: true,
        fillColor: enabled ? Colors.grey.shade50 : Colors.grey.shade100,
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(15), borderSide: BorderSide.none),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      ),
      style: TextStyle(fontSize: 14, color: enabled ? AppTheme.textColor : Colors.grey.shade500),
    );
  }
}

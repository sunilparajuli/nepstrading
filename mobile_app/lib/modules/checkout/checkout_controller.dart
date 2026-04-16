import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:dio/dio.dart' as dio_pkg;
import '../cart/cart_controller.dart';
import '../../core/network/dio_client.dart';
import 'dart:async';

class CheckoutController extends GetxController {
  final nameController = TextEditingController();
  final emailController = TextEditingController();
  final addressController = TextEditingController();
  final cityController = TextEditingController();
  final phoneController = TextEditingController();
  
  // Locations Data
  final states = <String>[].obs;
  final postcodes = <String>[].obs;
  final selectedState = Rxn<String>();
  final selectedPostcode = Rxn<String>();
  
  // Shipping Data
  final shippingCost = 0.0.obs;
  final shippingName = 'Calculated at checkout'.obs;
  final isFreeShipping = false.obs;
  final minOrderThreshold = 69.0;
  
  final addressSuggestions = <Map<String, dynamic>>[].obs;
  final isSearchingAddress = false.obs;
  Timer? _debounceTimer;

  final isLoading = false.obs;
  final isBankTransfer = true.obs;

  @override
  void onInit() {
    super.onInit();
    fetchStates();
    addressController.addListener(_onAddressChanged);
    
    // Listen for subtotal changes from cart
    ever(Get.find<CartController>().cartItems, (_) => calculateShipping());
  }

  @override
  void onClose() {
    nameController.dispose();
    emailController.dispose();
    addressController.dispose();
    cityController.dispose();
    phoneController.dispose();
    _debounceTimer?.cancel();
    super.onClose();
  }

  void _onAddressChanged() {
    if (_debounceTimer?.isActive ?? false) _debounceTimer!.cancel();
    _debounceTimer = Timer(const Duration(milliseconds: 500), () {
      if (addressController.text.length > 3) {
        searchAddress(addressController.text);
      } else {
        addressSuggestions.clear();
      }
    });
  }

  Future<void> fetchStates() async {
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/locations/states');
      if (res.statusCode == 200) {
        states.assignAll(List<String>.from(res.data));
      }
    } catch (e) {
      print('Fetch States Error: $e');
    }
  }

  Future<void> fetchPostcodes(String state) async {
    selectedPostcode.value = null;
    postcodes.clear();
    shippingCost.value = 0.0; // Reset
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.get('/locations/postcodes', queryParameters: {'state': state});
      if (res.statusCode == 200) {
        postcodes.assignAll(List<String>.from(res.data));
      }
    } catch (e) {
      print('Fetch Postcodes Error: $e');
    }
  }

  Future<void> calculateShipping() async {
    if (selectedState.value == null || selectedPostcode.value == null) return;
    
    final subtotal = Get.find<CartController>().totalAmount;
    
    try {
      final dio = Get.find<DioClient>().dio;
      final res = await dio.post('/locations/calculate-shipping', data: {
        'state': selectedState.value,
        'postcode': selectedPostcode.value,
        'subtotal': subtotal,
      });
      
      if (res.statusCode == 200) {
        shippingCost.value = (res.data['shipping_cost'] as num).toDouble();
        shippingName.value = res.data['shipping_name'];
        isFreeShipping.value = res.data['is_free'];
      }
    } catch (e) {
      print('Calculate Shipping Error: $e');
    }
  }

  Future<void> searchAddress(String query) async {
    isSearchingAddress.value = true;
    try {
      final response = await dio_pkg.Dio().get(
        'https://nominatim.openstreetmap.org/search',
        queryParameters: {
          'q': '$query, Australia',
          'format': 'json',
          'addressdetails': 1,
          'limit': 5,
        },
      );
      if (response.statusCode == 200) {
        addressSuggestions.assignAll(List<Map<String, dynamic>>.from(response.data));
      }
    } catch (e) {
      print('Nominatim Error: $e');
    } finally {
      isSearchingAddress.value = false;
    }
  }

  void selectAddress(Map<String, dynamic> suggestion) {
    final address = suggestion['address'];
    final road = address['road'] ?? '';
    final houseNumber = address['house_number'] ?? '';
    
    addressController.removeListener(_onAddressChanged);
    addressController.text = houseNumber != '' ? '$houseNumber $road' : road;
    addressController.addListener(_onAddressChanged);
    
    cityController.text = address['city'] ?? address['town'] ?? address['suburb'] ?? '';
    
    final stateCode = _mapState(address['state'] ?? '');
    if (stateCode != null) {
      selectedState.value = stateCode;
      fetchPostcodes(stateCode).then((_) {
        final pc = address['postcode'];
        if (pc != null && postcodes.contains(pc)) {
          selectedPostcode.value = pc;
          calculateShipping(); // Trigger calculation
        }
      });
    }
    
    addressSuggestions.clear();
  }

  String? _mapState(String stateName) {
    final s = stateName.toLowerCase();
    if (s.contains('new south wales')) return 'NSW';
    if (s.contains('victoria')) return 'VIC';
    if (s.contains('queensland')) return 'QLD';
    if (s.contains('south australia')) return 'SA';
    if (s.contains('western australia')) return 'WA';
    if (s.contains('tasmania')) return 'TAS';
    if (s.contains('northern territory')) return 'NT';
    if (s.contains('australian capital territory')) return 'ACT';
    final codes = ['NSW', 'VIC', 'QLD', 'SA', 'WA', 'TAS', 'NT', 'ACT'];
    final upper = stateName.toUpperCase();
    if (codes.contains(upper)) return upper;
    return null;
  }

  void placeOrder() async {
    if (nameController.text.isEmpty || 
        emailController.text.isEmpty || 
        addressController.text.isEmpty || 
        selectedState.value == null || 
        selectedPostcode.value == null || 
        cityController.text.isEmpty || 
        phoneController.text.isEmpty) {
      Get.snackbar('Error', 'Please fill all shipping details including State and Postcode');
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
      
      // Sync cart
      for (var item in cart) {
        try {
          await dio.post('/cart/add', data: {
            'product_id': item.product.id,
            'qty': item.qty,
          });
        } catch (e) {}
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
        'billing_state': selectedState.value,
        'billing_postcode': selectedPostcode.value,
        'billing_phone': phoneController.text.trim(),
        'payment_method': isBankTransfer.value ? 'bacs' : 'cod',
        'shipping_total': shippingCost.value, // Send shipping total
      });
      
      if (response.statusCode == 200 || response.statusCode == 201) {
        Get.find<CartController>().clearCart();
        Get.snackbar('Success', 'Order placed successfully!');
        Get.offAllNamed('/home');
      }
    } catch (e) {
      print('Checkout Error: $e');
      Get.snackbar('Checkout Failed', 'Please check your connection and login.');
    } finally {
      isLoading.value = false;
    }
  }
}

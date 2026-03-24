import 'dart:convert';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../core/models/product_model.dart';
import '../../core/services/auth_service.dart';

class CartItem {
  final Product product;
  int qty;
  CartItem({required this.product, this.qty = 1});

  Map<String, dynamic> toJson() => {
    'product': product.toJson(),
    'qty': qty,
  };

  factory CartItem.fromJson(Map<String, dynamic> json) => CartItem(
    product: Product.fromJson(json['product']),
    qty: json['qty'],
  );
}

class CartController extends GetxController {
  final _authService = Get.find<AuthService>();
  final cartItems = <CartItem>[].obs;
  static const String _cartKey = 'cart_items';

  double get totalAmount => cartItems.fold(0, (sum, item) => sum + (item.product.price * item.qty));
  bool get isGuest => _authService.isGuest.value;

  @override
  void onInit() {
    super.onInit();
    _loadCart();
  }

  Future<void> _loadCart() async {
    final prefs = await SharedPreferences.getInstance();
    final String? cartData = prefs.getString(_cartKey);
    if (cartData != null) {
      final List decoded = jsonDecode(cartData);
      cartItems.assignAll(decoded.map((e) => CartItem.fromJson(e)).toList());
    }
  }

  Future<void> _saveCart() async {
    final prefs = await SharedPreferences.getInstance();
    final String encoded = jsonEncode(cartItems.map((e) => e.toJson()).toList());
    await prefs.setString(_cartKey, encoded);
  }

  void addItem(Product product) {
    var existingItem = cartItems.firstWhereOrNull((item) => item.product.id == product.id);
    if (existingItem != null) {
      existingItem.qty++;
      cartItems.refresh();
    } else {
      cartItems.add(CartItem(product: product));
    }
    _saveCart();
    Get.snackbar('Cart', '${product.name} added to cart', snackPosition: SnackPosition.BOTTOM);
  }

  void removeItem(int index) {
    cartItems.removeAt(index);
    _saveCart();
  }

  void clearCart() {
    cartItems.clear();
    _saveCart();
  }

  void proceedToCheckout() {
    if (isGuest) {
      Get.defaultDialog(
        title: 'Login Required',
        middleText: 'You need to be logged in to proceed to checkout.',
        textConfirm: 'Login',
        textCancel: 'Later',
        onConfirm: () => Get.offAllNamed('/auth'),
      );
    } else {
      Get.toNamed('/checkout');
    }
  }
}

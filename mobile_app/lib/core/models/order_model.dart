import 'product_model.dart';

class OrderItem {
  final int id;
  final int orderId;
  final int productId;
  final int qty;
  final double price;
  final double total;
  final Product? product;

  OrderItem({
    required this.id,
    required this.orderId,
    required this.productId,
    required this.qty,
    required this.price,
    required this.total,
    this.product,
  });

  factory OrderItem.fromJson(Map<String, dynamic> json) {
    return OrderItem(
      id: json['id'] ?? 0,
      orderId: json['order_id'] ?? 0,
      productId: json['product_id'] ?? 0,
      qty: json['qty'] ?? 0,
      price: double.tryParse(json['price']?.toString() ?? '0') ?? 0.0,
      total: double.tryParse(json['total']?.toString() ?? '0') ?? 0.0,
      product: json['product'] != null ? Product.fromJson(json['product']) : null,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'order_id': orderId,
    'product_id': productId,
    'qty': qty,
    'price': price,
    'total': total,
    'product': product?.toJson(),
  };
}

class OrderModel {
  final int id;
  final int userId;
  final String status;
  final double subtotal;
  final double shippingTotal;
  final double taxTotal;
  final double discountTotal;
  final double total;
  final String currency;
  final String? paymentMethod;
  final String? paymentStatus;
  final String billingFirstName;
  final String billingLastName;
  final String billingAddress;
  final String billingCity;
  final String billingPhone;
  final String billingEmail;
  final DateTime createdAt;
  final List<OrderItem> items;

  OrderModel({
    required this.id,
    required this.userId,
    required this.status,
    required this.subtotal,
    required this.shippingTotal,
    required this.taxTotal,
    required this.discountTotal,
    required this.total,
    required this.currency,
    this.paymentMethod,
    this.paymentStatus,
    required this.billingFirstName,
    required this.billingLastName,
    required this.billingAddress,
    required this.billingCity,
    required this.billingPhone,
    required this.billingEmail,
    required this.createdAt,
    required this.items,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      status: json['status'] ?? 'pending',
      subtotal: double.tryParse(json['subtotal']?.toString() ?? '0') ?? 0.0,
      shippingTotal: double.tryParse(json['shipping_total']?.toString() ?? '0') ?? 0.0,
      taxTotal: double.tryParse(json['tax_total']?.toString() ?? '0') ?? 0.0,
      discountTotal: double.tryParse(json['discount_total']?.toString() ?? '0') ?? 0.0,
      total: double.tryParse(json['total']?.toString() ?? '0') ?? 0.0,
      currency: json['currency'] ?? 'AUD',
      paymentMethod: json['payment_method'],
      paymentStatus: json['payment_status'],
      billingFirstName: json['billing_first_name'] ?? '',
      billingLastName: json['billing_last_name'] ?? '',
      billingAddress: json['billing_address'] ?? '',
      billingCity: json['billing_city'] ?? '',
      billingPhone: json['billing_phone'] ?? '',
      billingEmail: json['billing_email'] ?? '',
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : DateTime.now(),
      items: json['items'] != null
          ? (json['items'] as List).map((i) => OrderItem.fromJson(i)).toList()
          : [],
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'user_id': userId,
    'status': status,
    'subtotal': subtotal,
    'shipping_total': shippingTotal,
    'tax_total': taxTotal,
    'discount_total': discountTotal,
    'total': total,
    'currency': currency,
    'payment_method': paymentMethod,
    'payment_status': paymentStatus,
    'billing_first_name': billingFirstName,
    'billing_last_name': billingLastName,
    'billing_address': billingAddress,
    'billing_city': billingCity,
    'billing_phone': billingPhone,
    'billing_email': billingEmail,
    'created_at': createdAt.toIso8601String(),
    'items': items.map((i) => i.toJson()).toList(),
  };
}

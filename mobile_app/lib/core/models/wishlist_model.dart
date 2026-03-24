import 'product_model.dart';

class WishlistModel {
  final int id;
  final int productId;
  final Product? product;
  final DateTime? addedAt;

  WishlistModel({
    required this.id,
    required this.productId,
    this.product,
    this.addedAt,
  });

  factory WishlistModel.fromJson(Map<String, dynamic> json) {
    return WishlistModel(
      id: json['id'],
      productId: json['product_id'],
      product: json['product'] != null ? Product.fromJson(json['product']) : null,
      addedAt: json['added_at'] != null ? DateTime.tryParse(json['added_at']) : null,
    );
  }
}

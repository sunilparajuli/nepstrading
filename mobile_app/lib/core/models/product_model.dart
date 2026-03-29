import 'category_model.dart';

class AttributeModel {
  final int id;
  final String name;
  final String slug;
  final String type;

  AttributeModel({
    required this.id,
    required this.name,
    required this.slug,
    required this.type,
  });

  factory AttributeModel.fromJson(Map<String, dynamic> json) {
    return AttributeModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      type: json['type'] ?? 'select',
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'slug': slug,
    'type': type,
  };
}

class AttributeTermModel {
  final int id;
  final int attributeId;
  final String name;
  final String slug;
  final String? value;
  final AttributeModel? attribute;

  AttributeTermModel({
    required this.id,
    required this.attributeId,
    required this.name,
    required this.slug,
    this.value,
    this.attribute,
  });

  factory AttributeTermModel.fromJson(Map<String, dynamic> json) {
    return AttributeTermModel(
      id: json['id'] ?? 0,
      attributeId: json['attribute_id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      value: json['value'],
      attribute: json['attribute'] != null ? AttributeModel.fromJson(json['attribute']) : null,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'attribute_id': attributeId,
    'name': name,
    'slug': slug,
    'value': value,
    'attribute': attribute?.toJson(),
  };
}

class Product {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final double price;
  final double? salePrice;
  final String sku;
  final int qty;
  final bool isFeatured;
  final String? image;
  final List<Category>? categories;
  final List<AttributeTermModel>? attributes;

  Product({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    required this.price,
    this.salePrice,
    required this.sku,
    required this.qty,
    required this.isFeatured,
    this.image,
    this.categories,
    this.attributes,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description'],
      price: double.tryParse(json['price']?.toString() ?? '0') ?? 0.0,
      salePrice: json['sale_price'] != null ? double.tryParse(json['sale_price'].toString()) : null,
      sku: json['sku'] ?? '',
      qty: json['qty'] ?? 0,
      isFeatured: json['is_featured'] == 1 || json['is_featured'] == true,
      image: _buildImageUrl(json['image']),
      categories: json['categories'] != null 
          ? (json['categories'] as List).map((i) => Category.fromJson(i)).toList()
          : null,
      attributes: json['attributes'] != null 
          ? (json['attributes'] as List).map((i) => AttributeTermModel.fromJson(i)).toList()
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'slug': slug,
    'description': description,
    'price': price,
    'sale_price': salePrice,
    'sku': sku,
    'qty': qty,
    'is_featured': isFeatured,
    'image': image,
    'categories': categories?.map((i) => i.toJson()).toList(),
    'attributes': attributes?.map((i) => i.toJson()).toList(),
  };

  static String? _buildImageUrl(String? path) {
    if (path == null || path.isEmpty) return null;
    if (path.startsWith('http')) return path;
    return 'https://new.nepstrading.com.au$path';
  }
}

import 'package:dio/dio.dart';
import 'lib/core/models/category_model.dart';
import 'lib/core/models/product_model.dart';

void main() async {
  final dio = Dio();
  
  try {
    print('Fetching categories...');
    final catRes = await dio.get('http://127.0.0.1:8000/api/categories');
    print('Category Status: \${catRes.statusCode}');
    
    final cats = (catRes.data as List).map((e) => Category.fromJson(e)).toList();
    print('Parsed \${cats.length} categories successfully.');
    
    print('Fetching products...');
    final prodRes = await dio.get('http://127.0.0.1:8000/api/products');
    print('Product Status: \${prodRes.statusCode}');
    
    final List items = prodRes.data['data'] ?? [];
    final prods = items.map((e) => Product.fromJson(e)).toList();
    print('Parsed \${prods.length} products successfully.');
    
  } catch (e, stacktrace) {
    print('FAILED with Error: \$e');
    print(stacktrace);
  }
}

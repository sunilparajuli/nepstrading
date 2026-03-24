class Category {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? image;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.image,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'],
      image: _buildImageUrl(json['image']),
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'name': name,
    'slug': slug,
    'description': description,
    'image': image,
  };

  static String? _buildImageUrl(String? path) {
    if (path == null || path.isEmpty) return null;
    if (path.startsWith('http')) return path;
    return 'http://127.0.0.1:8000/storage/$path';
  }
}

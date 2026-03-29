import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:shimmer/shimmer.dart';
import 'products_controller.dart';
import '../../core/theme/app_theme.dart';
import '../cart/cart_controller.dart';

class ProductsView extends GetView<ProductsController> {
  const ProductsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text('Shop', style: TextStyle(fontWeight: FontWeight.bold)),
        centerTitle: true,
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(LucideIcons.arrowLeft, color: AppTheme.textColor),
          onPressed: () => Get.back(),
        ),
        actions: [
          Obx(() => IconButton(
                icon: Icon(
                  controller.isSearchVisible.value ? LucideIcons.x : LucideIcons.search,
                  color: AppTheme.textColor,
                ),
                onPressed: () => controller.toggleSearch(),
              )),
          Builder(
            builder: (context) => IconButton(
              icon: const Icon(LucideIcons.sliders, color: AppTheme.textColor),
              onPressed: () => Scaffold.of(context).openEndDrawer(),
            ),
          ),
        ],
      ),
      endDrawer: _buildFilterDrawer(),
      body: Column(
        children: [
          Obx(() => Visibility(
                visible: controller.isSearchVisible.value,
                child: _buildSearchField(),
              )),
          _buildSortDropdown(),
          Expanded(
            child: Obx(() {
              if (controller.isLoading.value && controller.products.isEmpty) {
                return _buildShimmerGrid();
              }
              if (controller.products.isEmpty && !controller.isLoading.value) {
                return _buildEmptyState();
              }
              return RefreshIndicator(
                onRefresh: () => controller.fetchProducts(),
                color: AppTheme.primaryColor,
                child: CustomScrollView(
                  controller: controller.scrollController,
                  physics: const AlwaysScrollableScrollPhysics(),
                  slivers: [
                    SliverPadding(
                      padding: const EdgeInsets.all(16),
                      sliver: SliverGrid(
                        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: 2,
                          childAspectRatio: 0.62,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                        ),
                        delegate: SliverChildBuilderDelegate(
                          (context, index) {
                            final product = controller.products[index];
                            return _buildProductCard(product);
                          },
                          childCount: controller.products.length,
                        ),
                      ),
                    ),
                    if (controller.isMoreLoading.value)
                      SliverToBoxAdapter(
                        child: _buildMoreLoadingIndicator(),
                      ),
                    if (!controller.isMoreLoading.value && controller.currentPage.value < controller.lastPage.value && controller.products.isNotEmpty)
                      SliverToBoxAdapter(
                        child: Padding(
                          padding: const EdgeInsets.symmetric(vertical: 20),
                          child: Center(
                            child: OutlinedButton(
                              onPressed: () => controller.loadMore(),
                              style: OutlinedButton.styleFrom(
                                foregroundColor: AppTheme.primaryColor,
                                side: const BorderSide(color: AppTheme.primaryColor),
                                padding: const EdgeInsets.symmetric(horizontal: 30, vertical: 12),
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                              ),
                              child: const Text('Load More Products', style: TextStyle(fontWeight: FontWeight.bold)),
                            ),
                          ),
                        ),
                      ),
                    if (controller.currentPage.value >= controller.lastPage.value && controller.products.isNotEmpty)
                      const SliverToBoxAdapter(
                        child: Padding(
                          padding: EdgeInsets.symmetric(vertical: 20),
                          child: Center(
                            child: Text(
                              'You have reached the end',
                              style: TextStyle(color: Colors.grey, fontSize: 13),
                            ),
                          ),
                        ),
                      ),
                  ],
                ),
              );
            }),
          ),
        ],
      ),
    );
  }

  Widget _buildSearchField() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
      child: Container(
        decoration: BoxDecoration(
          color: const Color(0xFFF2F2F2),
          borderRadius: BorderRadius.circular(30),
        ),
        child: TextField(
          onChanged: (val) => controller.searchQuery.value = val,
          decoration: const InputDecoration(
            hintText: 'Search products...',
            hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
            prefixIcon: Icon(LucideIcons.search, color: Colors.grey, size: 20),
            border: InputBorder.none,
            contentPadding: EdgeInsets.symmetric(vertical: 12),
          ),
        ),
      ),
    );
  }

  Widget _buildSortDropdown() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Obx(() => Text(
                'Showing ${controller.products.length} of ${controller.totalProducts.value} Products',
                style: const TextStyle(color: Colors.grey, fontSize: 13),
              )),
          Row(
            children: [
              const Text('Sort by: ', style: TextStyle(color: Colors.grey, fontSize: 13)),
              Obx(() => DropdownButton<String>(
                    value: controller.sortBy.value,
                    underline: const SizedBox(),
                    icon: const Icon(LucideIcons.chevronDown, size: 14),
                    style: const TextStyle(color: AppTheme.primaryColor, fontWeight: FontWeight.bold, fontSize: 13),
                    items: const [
                      DropdownMenuItem(value: 'featured', child: Text('Featured')),
                      DropdownMenuItem(value: 'name_asc', child: Text('A-Z')),
                      DropdownMenuItem(value: 'price_asc', child: Text('Price: Low-High')),
                      DropdownMenuItem(value: 'price_desc', child: Text('Price: High-Low')),
                    ],
                    onChanged: (val) {
                      if (val != null) controller.sortBy.value = val;
                    },
                  )),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildFilterDrawer() {
    return Drawer(
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Filters', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  TextButton(
                    onPressed: () => controller.resetFilters(),
                    child: const Text('Clear All', style: TextStyle(color: AppTheme.primaryColor)),
                  ),
                ],
              ),
              const Divider(),
              const SizedBox(height: 20),
              const Text('Category', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              const SizedBox(height: 12),
              Expanded(
                child: Obx(() => ListView.builder(
                      itemCount: controller.categories.length,
                      itemBuilder: (context, index) {
                        final cat = controller.categories[index];
                        final isSelected = controller.selectedCategoryId.value == cat.id;
                        return CheckboxListTile(
                          title: Text(cat.name, style: const TextStyle(fontSize: 14)),
                          value: isSelected,
                          activeColor: AppTheme.primaryColor,
                          dense: true,
                          contentPadding: EdgeInsets.zero,
                          onChanged: (val) {
                            controller.selectedCategoryId.value = val == true ? cat.id : null;
                          },
                        );
                      },
                    )),
              ),
              const SizedBox(height: 20),
              const Text('Availability', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              Obx(() => Column(
                    children: [
                      RadioListTile<String>(
                        title: const Text('All', style: TextStyle(fontSize: 14)),
                        value: 'all',
                        groupValue: controller.stockStatus.value,
                        activeColor: AppTheme.primaryColor,
                        onChanged: (v) => controller.stockStatus.value = v!,
                      ),
                      RadioListTile<String>(
                        title: const Text('In Stock', style: TextStyle(fontSize: 14)),
                        value: 'in',
                        groupValue: controller.stockStatus.value,
                        activeColor: AppTheme.primaryColor,
                        onChanged: (v) => controller.stockStatus.value = v!,
                      ),
                      RadioListTile<String>(
                        title: const Text('Out of Stock', style: TextStyle(fontSize: 14)),
                        value: 'out',
                        groupValue: controller.stockStatus.value,
                        activeColor: AppTheme.primaryColor,
                        onChanged: (v) => controller.stockStatus.value = v!,
                      ),
                    ],
                  )),
              const SizedBox(height: 20),
              ElevatedButton(
                onPressed: () => Get.back(),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primaryColor,
                  minimumSize: const Size(double.infinity, 50),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: const Text('Apply Filters', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProductCard(dynamic product) {
    return GestureDetector(
      onTap: () => Get.toNamed('/product-details', arguments: product),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10, offset: const Offset(0, 5))
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Stack(
                children: [
                  Container(
                    width: double.infinity,
                    decoration: const BoxDecoration(
                      borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
                      color: Color(0xFFF2F2F2),
                    ),
                    child: ClipRRect(
                      borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                      child: product.image != null
                          ? Image.network(product.image!, fit: BoxFit.cover)
                          : const Center(child: Icon(LucideIcons.image, size: 40, color: Colors.grey)),
                    ),
                  ),
                  Positioned(
                    top: 8,
                    right: 8,
                    child: GestureDetector(
                      onTap: () => controller.toggleWishlist(product),
                      child: Container(
                        padding: const EdgeInsets.all(6),
                        decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
                        child: const Icon(LucideIcons.heart, size: 16, color: AppTheme.primaryColor),
                      ),
                    ),
                  ),
                  if (product.salePrice != null)
                    Positioned(
                      top: 8,
                      left: 8,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(color: AppTheme.primaryColor, borderRadius: BorderRadius.circular(8)),
                        child: const Text('SALE', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                    ),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(12.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    product.name,
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: AppTheme.textColor),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  Row(
                    children: [
                      Text(
                        '\$${product.price.toStringAsFixed(2)}',
                        style: TextStyle(
                          color: product.salePrice != null ? Colors.grey : AppTheme.textColor,
                          fontWeight: FontWeight.bold,
                          fontSize: 14,
                          decoration: product.salePrice != null ? TextDecoration.lineThrough : null,
                        ),
                      ),
                      if (product.salePrice != null) ...[
                        const SizedBox(width: 8),
                        Text(
                          '\$${product.salePrice!.toStringAsFixed(2)}',
                          style: const TextStyle(color: Color(0xFFE53935), fontWeight: FontWeight.bold, fontSize: 14),
                        ),
                      ]
                    ],
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () => Get.find<CartController>().addItem(product),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryColor,
                        padding: const EdgeInsets.symmetric(vertical: 8),
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      ),
                      child: const Text('Add to Cart', style: TextStyle(fontSize: 12, color: Colors.white)),
                    ),
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(LucideIcons.shoppingBag, size: 64, color: Colors.grey.shade300),
          const SizedBox(height: 16),
          const Text('No products found', style: TextStyle(color: Colors.grey, fontSize: 16)),
          TextButton(
            onPressed: () => controller.resetFilters(),
            child: const Text('Clear Filters'),
          ),
        ],
      ),
    );
  }

  Widget _buildShimmerGrid() {
    return GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        childAspectRatio: 0.62,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
      ),
      itemCount: 6,
      itemBuilder: (_, __) => _buildSkeletonCard(),
    );
  }

  Widget _buildMoreLoadingIndicator() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 16),
      alignment: Alignment.center,
      child: const SizedBox(
        height: 24,
        width: 24,
        child: CircularProgressIndicator(
          strokeWidth: 2,
          valueColor: AlwaysStoppedAnimation<Color>(AppTheme.primaryColor),
        ),
      ),
    );
  }

  Widget _buildSkeletonCard() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Shimmer.fromColors(
        baseColor: Colors.grey.shade200,
        highlightColor: Colors.grey.shade50,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(12.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(height: 14, width: double.infinity, color: Colors.white),
                  const SizedBox(height: 8),
                  Container(height: 14, width: 60, color: Colors.white),
                  const SizedBox(height: 12),
                  Container(height: 36, width: double.infinity, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8))),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}

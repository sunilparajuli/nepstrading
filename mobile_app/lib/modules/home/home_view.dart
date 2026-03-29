import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../core/models/product_model.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:badges/badges.dart' as badges;
import 'package:shimmer/shimmer.dart';
import 'home_controller.dart';
import '../cart/cart_controller.dart';
import '../../core/theme/app_theme.dart';
import '../../core/services/auth_service.dart';

class HomeView extends GetView<HomeController> {
  const HomeView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.backgroundColor,
      body: SafeArea(
        child: Column(
          children: [
            _buildAppBar(context),
            _buildSearchRow(),
            _buildCategories(),
            Expanded(
              child: Obx(() {
                if (controller.isLoading.value && controller.products.isEmpty) {
                  return _buildShimmerSkeleton();
                }
                return RefreshIndicator(
                  onRefresh: () => controller.fetchHomeData(),
                  color: AppTheme.primaryColor,
                  child: CustomScrollView(
                    controller: controller.scrollController,
                    physics: const AlwaysScrollableScrollPhysics(),
                    slivers: [
                      SliverToBoxAdapter(
                        child: _buildSectionTitle('Popular Product', context),
                      ),
                      SliverPadding(
                        padding: const EdgeInsets.symmetric(horizontal: 16.0),
                        sliver: SliverGrid(
                          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 2,
                            childAspectRatio: 0.60,
                            crossAxisSpacing: 16,
                            mainAxisSpacing: 16,
                          ),
                          delegate: SliverChildBuilderDelegate(
                            (context, index) {
                              final product = controller.products[index];
                              return _buildProductCard(product, context);
                            },
                            childCount: controller.products.length,
                          ),
                        ),
                      ),
                      if (controller.isMoreLoading.value)
                        const SliverToBoxAdapter(
                          child: Padding(
                            padding: EdgeInsets.symmetric(vertical: 20),
                            child: Center(child: CircularProgressIndicator(color: AppTheme.primaryColor)),
                          ),
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
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
                                ),
                                child: const Text('Load More'),
                              ),
                            ),
                          ),
                        ),
                      const SliverToBoxAdapter(child: SizedBox(height: 20)),
                    ],
                  ),
                );
              }),
            ),
          ],
        ),
      ),
      bottomNavigationBar: _buildBottomNav(),
    );
  }

  Widget _buildAppBar(BuildContext context) {
    final authService = Get.find<AuthService>();
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 12.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              GestureDetector(
                onTap: () => controller.toggleProfileInfo(),
                child: const CircleAvatar(
                  radius: 20,
                  backgroundColor: Color(0xFFF2F2F2),
                  child: Icon(LucideIcons.user, size: 20, color: Colors.grey),
                ),
              ),
              Obx(() => Visibility(
                    visible: controller.isProfileInfoVisible.value,
                    child: Padding(
                      padding: const EdgeInsets.only(left: 12.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Good Morning!', style: TextStyle(color: Colors.grey, fontSize: 12)),
                          Text(
                            authService.isGuest.value ? 'Guest User' : (authService.user.value?.name ?? 'User'),
                            style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                                  fontWeight: FontWeight.bold,
                                  color: AppTheme.textColor,
                                ),
                          ),
                        ],
                      ),
                    ),
                  )),
            ],
          ),
          Row(
            children: [
              IconButton(
                icon: Obx(() => Icon(
                      controller.isSearchVisible.value ? LucideIcons.x : LucideIcons.search,
                      color: AppTheme.textColor,
                      size: 20,
                    )),
                onPressed: () => controller.toggleSearch(),
              ),
              Obx(() {
                final count = Get.find<CartController>().cartItems.length;
                return badges.Badge(
                  position: badges.BadgePosition.topEnd(top: 0, end: 3),
                  showBadge: count > 0,
                  badgeAnimation: const badges.BadgeAnimation.scale(),
                  badgeContent: Text(
                    count.toString(),
                    style: const TextStyle(color: Colors.white, fontSize: 10),
                  ),
                  child: Container(
                    decoration: BoxDecoration(
                      color: Colors.white,
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4)),
                      ],
                    ),
                    child: IconButton(
                      icon: const Icon(LucideIcons.bell, color: AppTheme.textColor, size: 20),
                      onPressed: () {},
                    ),
                  ),
                );
              }),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSearchRow() {
    return Obx(() => Visibility(
          visible: controller.isSearchVisible.value,
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 12.0),
            child: Row(
              children: [
                Expanded(
                  child: Container(
                    decoration: BoxDecoration(
                      color: const Color(0xFFF2F2F2),
                      borderRadius: BorderRadius.circular(30),
                    ),
                    child: TextField(
                      onChanged: (val) => controller.searchQuery.value = val,
                      decoration: const InputDecoration(
                        hintText: 'Search something...',
                        hintStyle: TextStyle(color: Colors.grey, fontSize: 14),
                        prefixIcon: Icon(LucideIcons.search, color: Colors.grey),
                        border: InputBorder.none,
                        enabledBorder: InputBorder.none,
                        focusedBorder: InputBorder.none,
                        contentPadding: EdgeInsets.symmetric(vertical: 14),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Container(
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: IconButton(
                    icon: const Icon(LucideIcons.sliders, color: Colors.white),
                    onPressed: () => Get.toNamed('/products'),
                  ),
                ),
              ],
            ),
          ),
        ));
  }

  Widget _buildCategories() {
    return Obx(() {
      if (controller.categories.isEmpty) return const SizedBox.shrink();
      
      return Container(
        height: 40,
        margin: const EdgeInsets.symmetric(vertical: 8),
        child: ListView.builder(
          scrollDirection: Axis.horizontal,
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16),
          itemCount: controller.categories.length + 1, // Add "All"
          itemBuilder: (context, index) {
            final isAll = index == 0;
            final category = isAll ? null : controller.categories[index - 1];
            final catId = isAll ? null : category!.id;
            
            final isSelected = controller.selectedCategoryId.value == catId;
            return GestureDetector(
              onTap: () => controller.onCategorySelected(catId),
              child: Container(
                margin: const EdgeInsets.only(right: 12),
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  color: isSelected ? AppTheme.primaryColor : const Color(0xFFF2F2F2),
                  borderRadius: BorderRadius.circular(20),
                ),
                alignment: Alignment.center,
                child: Row(
                  children: [
                    if (isAll) ...[
                      Icon(LucideIcons.flame, color: isSelected ? Colors.white : Colors.black, size: 16),
                      const SizedBox(width: 6),
                    ],
                    Text(
                      isAll ? 'All' : category!.name,
                      style: TextStyle(
                        color: isSelected ? Colors.white : Colors.black,
                        fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        ),
      );
    });
  }

  Widget _buildSectionTitle(String title, BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold, fontSize: 20),
          ),
          TextButton(
            onPressed: () => Get.toNamed('/products'),
            child: const Text('View All', style: TextStyle(color: Colors.grey, fontSize: 14)),
          )
        ],
      ),
    );
  }

  Widget _buildProductCard(Product product, BuildContext context) {
    return GestureDetector(
      onTap: () => Get.toNamed('/product-details', arguments: product),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 10,
              offset: const Offset(0, 5),
            )
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
                          ? Image.network(
                              product.image!,
                              fit: BoxFit.cover,
                              errorBuilder: (context, error, stackTrace) =>
                                  const Center(child: Icon(LucideIcons.imageOff, size: 40, color: Colors.grey)),
                            )
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
                        decoration: const BoxDecoration(
                          color: Colors.white,
                          shape: BoxShape.circle,
                        ),
                        child: const Icon(LucideIcons.heart, size: 16, color: AppTheme.primaryColor),
                      ),
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
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: AppTheme.textColor),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  Text(
                    product.categories?.isNotEmpty == true ? product.categories!.first.name : 'Uncategorized',
                    style: const TextStyle(color: Colors.grey, fontSize: 12),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        '\$${product.price.toStringAsFixed(2)}',
                        style: const TextStyle(
                          color: AppTheme.textColor,
                          fontWeight: FontWeight.bold,
                          fontSize: 16,
                        ),
                      ),
                      GestureDetector(
                        onTap: () {
                          Get.find<CartController>().addItem(product);
                        },
                        child: Container(
                          padding: const EdgeInsets.all(6),
                          decoration: BoxDecoration(
                            color: AppTheme.primaryColor,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: const Icon(LucideIcons.shoppingBag, color: Colors.white, size: 16),
                        ),
                      ),
                    ],
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

import 'package:google_nav_bar/google_nav_bar.dart';

  Widget _buildBottomNav() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            blurRadius: 20,
            color: Colors.black.withOpacity(.1),
          )
        ],
      ),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 15.0, vertical: 8),
          child: GNav(
            rippleColor: Colors.grey[300]!,
            hoverColor: Colors.grey[100]!,
            gap: 8,
            activeColor: AppTheme.primaryColor,
            iconSize: 24,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            duration: const Duration(milliseconds: 400),
            tabBackgroundColor: AppTheme.primaryColor.withOpacity(0.1),
            color: Colors.grey[600],
            tabs: const [
              GButton(
                icon: LucideIcons.home,
                text: 'Home',
              ),
              GButton(
                icon: LucideIcons.shoppingBag,
                text: 'Cart',
              ),
              GButton(
                icon: LucideIcons.heart,
                text: 'Wishlist',
              ),
              GButton(
                icon: LucideIcons.user,
                text: 'Profile',
              ),
            ],
            selectedIndex: 0,
            onTabChange: (index) {
              if (index == 1) {
                Get.toNamed('/cart');
              } else if (index == 2) {
                Get.toNamed('/wishlist');
              } else if (index == 3) {
                Get.toNamed('/profile');
              }
            },
          ),
        ),
      ),
    );
  }

  Widget _buildShimmerSkeleton() {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            padding: const EdgeInsets.all(16),
            itemCount: 4,
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              childAspectRatio: 0.60,
              crossAxisSpacing: 16,
              mainAxisSpacing: 16,
            ),
            itemBuilder: (_, __) => Shimmer.fromColors(
              baseColor: Colors.grey.shade300,
              highlightColor: Colors.grey.shade100,
              child: Container(
                decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
              ),
            ),
          )
        ],
      ),
    );
  }
}

import 'package:get/get.dart';
import 'app_routes.dart';
import '../modules/splash/splash_view.dart';
import '../modules/splash/splash_binding.dart';
import '../modules/home/home_view.dart';
import '../modules/home/home_binding.dart';
import '../modules/auth/login_view.dart';
import '../modules/auth/register_view.dart';
import '../modules/auth/auth_binding.dart';
import '../modules/cart/cart_view.dart';
import '../modules/cart/cart_binding.dart';
import '../modules/checkout/checkout_view.dart';
import '../modules/checkout/checkout_binding.dart';
import '../modules/profile/profile_view.dart';
import '../modules/profile/profile_binding.dart';
import '../modules/wishlist/wishlist_view.dart';
import '../modules/wishlist/wishlist_binding.dart';
import '../modules/product_detail/product_detail_view.dart';
import '../modules/product_detail/product_detail_binding.dart';
import '../modules/products/products_view.dart';
import '../modules/products/products_binding.dart';
import '../modules/orders/orders_view.dart';
import '../modules/orders/orders_binding.dart';
import '../modules/maintenance/maintenance_view.dart';
import '../modules/chatbot/chatbot_view.dart';
import '../modules/chatbot/chatbot_binding.dart';

class AppPages {
  static const INITIAL = Routes.SPLASH;

  static final routes = [
    GetPage(
      name: Routes.SPLASH,
      page: () => const SplashView(),
      binding: SplashBinding(),
    ),
    GetPage(
      name: Routes.HOME,
      page: () => const HomeView(),
      binding: HomeBinding(),
    ),
    GetPage(
      name: Routes.AUTH,
      page: () => const LoginView(),
      binding: AuthBinding(),
    ),
    GetPage(
      name: '/register',
      page: () => const RegisterView(),
      binding: AuthBinding(),
    ),

    GetPage(
      name: Routes.CART,
      page: () => const CartView(),
      binding: CartBinding(),
    ),
    GetPage(
      name: Routes.CHECKOUT,
      page: () => const CheckoutView(),
      binding: CheckoutBinding(),
    ),

    GetPage(
      name: Routes.PROFILE,
      page: () => const ProfileView(),
      binding: ProfileBinding(),
    ),
    GetPage(
      name: '/wishlist',
      page: () => const WishlistView(),
      binding: WishlistBinding(),
    ),
    GetPage(
      name: Routes.PRODUCT_DETAILS,
      page: () => const ProductDetailView(),
      binding: ProductDetailBinding(),
    ),
    GetPage(
      name: Routes.PRODUCTS,
      page: () => const ProductsView(),
      binding: ProductsBinding(),
    ),
    GetPage(
      name: Routes.ORDERS,
      page: () => const OrdersView(),
      binding: OrdersBinding(),
    ),
    GetPage(
      name: Routes.MAINTENANCE,
      page: () => const MaintenanceView(),
    ),
    GetPage(
      name: Routes.CHATBOT,
      page: () => const ChatbotView(),
      binding: ChatbotBinding(),
    ),
  ];
}

import 'package:get/get.dart';
import 'home_controller.dart';
import '../cart/cart_controller.dart';

class HomeBinding extends Bindings {
  @override
  void dependencies() {
    Get.put(CartController(), permanent: true);
    Get.lazyPut<HomeController>(() => HomeController());
  }
}

import 'package:get/get.dart';
import 'cart_controller.dart';

class CartBinding extends Bindings {
  @override
  void dependencies() {
    // We use put instead of lazyPut because cart state needs to persist globally
    Get.put(CartController(), permanent: true);
  }
}

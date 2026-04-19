import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../core/network/dio_client.dart';

class ChatMessage {
  final String text;
  final bool isUser;
  final List<dynamic>? actions;

  ChatMessage({required this.text, required this.isUser, this.actions});
}

class ChatbotController extends GetxController {
  final messages = <ChatMessage>[].obs;
  final isLoading = false.obs;
  final TextEditingController textController = TextEditingController();
  final ScrollController scrollController = ScrollController();

  @override
  void onInit() {
    super.onInit();
    // Welcome message
    messages.add(ChatMessage(
      text: "Namaste! I'm your Nepstrading Assistant. How can I help you find what you need today?",
      isUser: false,
    ));
  }

  Future<void> sendMessage() async {
    final text = textController.text.trim();
    if (text.isEmpty || isLoading.value) return;

    textController.clear();
    messages.add(ChatMessage(text: text, isUser: true));
    _scrollToBottom();

    isLoading.value = true;
    try {
      final dio = Get.find<DioClient>().dio;
      final response = await dio.post('/chatbot', data: {'message': text});

      if (response.statusCode == 200) {
        messages.add(ChatMessage(
          text: response.data['message'],
          isUser: false,
          actions: response.data['actions'],
        ));
      } else {
        messages.add(ChatMessage(text: "Something went wrong. Please try again later.", isUser: false));
      }
    } catch (e) {
      messages.add(ChatMessage(text: "Connection error. Please check your internet.", isUser: false));
    } finally {
      isLoading.value = false;
      _scrollToBottom();
    }
  }

  void _scrollToBottom() {
    Future.delayed(const Duration(milliseconds: 100), () {
      if (scrollController.hasClients) {
        scrollController.animateTo(
          scrollController.position.maxScrollExtent,
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOut,
        );
      }
    });
  }

  void handleAction(Map<String, dynamic> action) {
    switch (action['type']) {
      case 'product':
        Get.toNamed('/product-details', arguments: action['id']); // Adjust based on model if needed
        break;
      case 'category':
        Get.toNamed('/products', arguments: {'category': action['slug']});
        break;
      case 'contact':
        // Handle call logic if needed
        Get.snackbar("Contact Us", "You can call us at ${action['phone']}");
        break;
    }
  }
}

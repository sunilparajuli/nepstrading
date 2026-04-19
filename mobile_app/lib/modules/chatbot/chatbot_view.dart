import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'chatbot_controller.dart';
import '../../core/theme/app_theme.dart';

class ChatbotView extends GetView<ChatbotController> {
  const ChatbotView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Shop Assistant', style: TextStyle(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        foregroundColor: AppTheme.textColor,
        elevation: 0,
      ),
      body: Column(
        children: [
          Expanded(
            child: Obx(() => ListView.builder(
              controller: controller.scrollController,
              padding: const EdgeInsets.all(16),
              itemCount: controller.messages.length,
              itemBuilder: (context, index) {
                final msg = controller.messages[index];
                return _buildChatBubble(msg);
              },
            )),
          ),
          if (controller.isLoading.value)
            const Padding(
              padding: EdgeInsets.all(8.0),
              child: Center(child: CircularProgressIndicator(strokeWidth: 2)),
            ),
          _buildInputArea(),
        ],
      ),
    );
  }

  Widget _buildChatBubble(ChatMessage msg) {
    return Align(
      alignment: msg.isUser ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        constraints: BoxConstraints(maxWidth: Get.width * 0.75),
        decoration: BoxDecoration(
          color: msg.isUser ? AppTheme.primaryColor : const Color(0xFFF2F2F2),
          borderRadius: BorderRadius.only(
            topLeft: const Radius.circular(16),
            topRight: const Radius.circular(16),
            bottomLeft: Radius.circular(msg.isUser ? 16 : 0),
            bottomRight: Radius.circular(msg.isUser ? 0 : 16),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              msg.text,
              style: TextStyle(
                color: msg.isUser ? Colors.white : AppTheme.textColor,
                fontSize: 14,
              ),
            ),
            if (msg.actions != null && msg.actions!.isNotEmpty) ...[
              const SizedBox(height: 12),
              ...msg.actions!.map((action) => _buildActionButton(action)),
            ]
          ],
        ),
      ),
    );
  }

  Widget _buildActionButton(Map<String, dynamic> action) {
    String label = "View";
    IconData icon = LucideIcons.externalLink;

    if (action['type'] == 'product') {
      label = "View Product";
      icon = LucideIcons.shoppingBag;
    } else if (action['type'] == 'category') {
      label = "Explore Category";
      icon = LucideIcons.layers;
    } else if (action['type'] == 'contact') {
      label = "Call Store";
      icon = LucideIcons.phoneCall;
    }

    return Padding(
      padding: const EdgeInsets.only(top: 8.0),
      child: ElevatedButton.icon(
        onPressed: () => controller.handleAction(action),
        icon: Icon(icon, size: 14, color: AppTheme.primaryColor),
        label: Text(label, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: AppTheme.primaryColor)),
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.white,
          elevation: 0,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
          minimumSize: const Size(double.infinity, 36),
        ),
      ),
    );
  }

  Widget _buildInputArea() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, -4)),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: const Color(0xFFF2F2F2),
                borderRadius: BorderRadius.circular(24),
              ),
              child: TextField(
                controller: controller.textController,
                decoration: const InputDecoration(
                  hintText: 'Type a message...',
                  border: InputBorder.none,
                  contentPadding: EdgeInsets.symmetric(vertical: 12),
                ),
                onSubmitted: (_) => controller.sendMessage(),
              ),
            ),
          ),
          const SizedBox(width: 12),
          CircleAvatar(
            backgroundColor: AppTheme.primaryColor,
            child: IconButton(
              icon: const Icon(LucideIcons.send, color: Colors.white, size: 20),
              onPressed: () => controller.sendMessage(),
            ),
          )
        ],
      ),
    );
  }
}

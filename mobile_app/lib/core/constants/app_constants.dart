class AppConstants {
  // Using 10.0.2.2 for Android Emulator, or localhost for iOS simulator
  // Since we are running the Laravel backend locally on port 8000
  // Adjust this IP if testing on physical devices (e.g., your local LAN IP)
  static const String baseUrl = 'http://127.0.0.1:8000/api';
  
  // Shared Preferences Keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'auth_user';
  static const String cartKey = 'local_cart';
}

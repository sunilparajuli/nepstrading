class AppSettings {
  final String storeName;
  final String currency;
  final String currencySymbol;
  final String primaryColor;
  final bool maintenanceMode;
  final String appVersion;
  final String minAppVersion;
  final String appUpdateUrl;

  AppSettings({
    required this.storeName,
    required this.currency,
    required this.currencySymbol,
    required this.primaryColor,
    required this.maintenanceMode,
    required this.appVersion,
    required this.minAppVersion,
    required this.appUpdateUrl,
  });

  factory AppSettings.fromJson(Map<String, dynamic> json) {
    return AppSettings(
      storeName: json['store_name'] ?? 'Nepstrading',
      currency: json['currency'] ?? 'AUD',
      currencySymbol: json['currency_symbol'] ?? '\$',
      primaryColor: json['primary_color'] ?? '#F15F22',
      maintenanceMode: json['maintenance_mode'] == true || json['maintenance_mode'] == 1 || json['maintenance_mode'] == '1',
      appVersion: json['app_version'] ?? '1.0.0',
      minAppVersion: json['min_app_version'] ?? '1.0.0',
      appUpdateUrl: json['app_update_url'] ?? 'https://play.google.com/store/apps/details?id=com.sunil.nepstrading.ecommerce.app',
    );
  }

  Map<String, dynamic> toJson() => {
    'store_name': storeName,
    'currency': currency,
    'currency_symbol': currencySymbol,
    'primary_color': primaryColor,
    'maintenance_mode': maintenanceMode,
    'app_version': appVersion,
    'min_app_version': minAppVersion,
    'app_update_url': appUpdateUrl,
  };
}

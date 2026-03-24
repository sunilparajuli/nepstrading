class AppSettings {
  final String storeName;
  final String currency;
  final String currencySymbol;
  final String primaryColor;
  final bool maintenanceMode;

  AppSettings({
    required this.storeName,
    required this.currency,
    required this.currencySymbol,
    required this.primaryColor,
    required this.maintenanceMode,
  });

  factory AppSettings.fromJson(Map<String, dynamic> json) {
    return AppSettings(
      storeName: json['store_name'] ?? 'Nepstrading',
      currency: json['currency'] ?? 'AUD',
      currencySymbol: json['currency_symbol'] ?? '\$',
      primaryColor: json['primary_color'] ?? '#F15F22',
      maintenanceMode: json['maintenance_mode'] == true || json['maintenance_mode'] == 1 || json['maintenance_mode'] == '1',
    );
  }

  Map<String, dynamic> toJson() => {
    'store_name': storeName,
    'currency': currency,
    'currency_symbol': currencySymbol,
    'primary_color': primaryColor,
    'maintenance_mode': maintenanceMode,
  };
}

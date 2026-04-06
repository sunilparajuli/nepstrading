<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

echo "Clearing all Orders and Order Items...\n";

try {
    DB::beginTransaction();
    
    // Clear items first to avoid foreign key issues
    DB::table('order_items')->truncate();
    echo "Order items cleared.\n";
    
    // Clear orders
    DB::table('orders')->truncate();
    echo "Orders cleared.\n";
    
    DB::commit();
    echo "\nAll order data removed successfully.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}

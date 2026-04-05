<?php
// scripts/update_categories_icons.php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

echo "Updating category images to UI Avatars...\n";

$categories = Category::all();

foreach ($categories as $cat) {
    // We will overwrite all of them or just the unsplash ones
    if (empty($cat->image) || strpos($cat->image, 'unsplash.com') !== false || strpos($cat->image, 'placehold.co') !== false) {
        // Create an avatar based on category name
        $encodedName = urlencode($cat->name);
        $url = "https://ui-avatars.com/api/?name={$encodedName}&background=random&color=fff&size=256&rounded=true&bold=true&font-size=0.4";
        $cat->image = $url;
        $cat->save();
        echo "Updated icon for {$cat->name}\n";
    }
}

echo "\nDone!\n";

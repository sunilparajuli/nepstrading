<?php
// scripts/update_categories.php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\HomepageSection;
use Illuminate\Support\Str;

echo "Updating HomepageSection limit...\n";

$section = HomepageSection::where('type', 'categories')->first();
if ($section) {
    $data = $section->data;
    unset($data['limit']); // remove limit to list all
    $section->data = $data;
    $section->save();
    echo "Limit removed for section: {$section->title}\n";
} else {
    echo "HomepageSection 'categories' not found.\n";
}

echo "\nAssigning free images to categories missing them...\n";

$categories = Category::whereNotNull('parent_id')->orWhereNull('parent_id')->get();
$keywords = [
    'Rice' => 'rice,grain',
    'Masala' => 'spices,indian',
    'Lentil' => 'lentils,beans',
    'Spices' => 'spices,powder',
    'Pantry' => 'pantry,grocery',
    'Bakery' => 'bread,pastry',
    'Dairy' => 'milk,cheese',
    'Meat' => 'meat,butcher',
    'Snack' => 'chips,snacks',
    'Baby' => 'baby,care',
    'Health' => 'medicine,pharmacy'
];

foreach ($categories as $cat) {
    if (empty($cat->image)) {
        // Try to find a good keyword match
        $match = 'grocery,food';
        foreach ($keywords as $key => $term) {
            if (stripos($cat->name, $key) !== false) {
                $match = $term;
                break;
            }
        }
        
        // Assign a random unsplash image based on keyword
        $url = "https://images.unsplash.com/featured/?{$match}&sig=" . rand(1, 1000);
        $cat->image = $url;
        $cat->save();
        echo "Updated image for {$cat->name} -> {$match}\n";
    }
}

echo "\nDone!\n";

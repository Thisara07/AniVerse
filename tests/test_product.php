<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Testing product 6...\n";
$product = Product::find(6);
if ($product) {
    echo "Product: " . $product->productName . "\n";
    echo "Image path: " . $product->image . "\n";
    echo "File exists in storage: " . (file_exists(public_path('storage/' . $product->image)) ? 'Yes' : 'No') . "\n";
    echo "Direct file exists: " . (file_exists(public_path($product->image)) ? 'Yes' : 'No') . "\n";
} else {
    echo "Product not found\n";
}
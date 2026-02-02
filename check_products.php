<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Checking products in database...\n";
echo "Products count: " . Product::count() . "\n";

$products = Product::all();
foreach($products as $product) {
    echo "ID: " . $product->id . " | Name: " . $product->productName . " | Image: " . $product->image . "\n";
}
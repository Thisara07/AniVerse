<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Livewire\ProductFilter;

echo "Testing ProductFilter component...\n";

try {
    $component = new ProductFilter();
    $component->mount();
    echo "Component mounted successfully\n";
    
    $component->render();
    echo "Component rendered successfully\n";
    echo "Products found: " . count($component->products) . "\n";
    
    foreach($component->products as $product) {
        echo "Product: " . $product->productName . " (ID: " . $product->id . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
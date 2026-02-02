<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Livewire\ImageGallery;
use Livewire\Livewire;

echo "Testing ImageGallery component...\n";

try {
    // Test the component
    $component = Livewire::test(ImageGallery::class, ['productId' => 6]);
    
    echo "Component created successfully\n";
    echo "Product ID: 6\n";
    echo "Main image: " . $component->mainImage . "\n";
    echo "Show zoom: " . ($component->showZoom ? 'true' : 'false') . "\n";
    
    // Test the zoom functionality
    $component->call('zoomImage', $component->mainImage);
    echo "After zoomImage call:\n";
    echo "Show zoom: " . ($component->showZoom ? 'true' : 'false') . "\n";
    echo "Zoomed image: " . $component->zoomedImage . "\n";
    
    // Test closing zoom
    $component->call('closeZoom');
    echo "After closeZoom call:\n";
    echo "Show zoom: " . ($component->showZoom ? 'true' : 'false') . "\n";
    echo "Zoomed image: " . $component->zoomedImage . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
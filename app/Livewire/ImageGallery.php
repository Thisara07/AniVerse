<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ImageGallery extends Component
{
    public $product;
    public $mainImage;
    public $galleryImages = [];
    public $showZoom = false;
    public $zoomedImage = '';
    
    public function mount($productId)
    {
        $this->product = Product::findOrFail($productId);
        $this->mainImage = $this->product->image;
        
        // Create gallery from product image and related images
        $this->galleryImages = [$this->product->image];
        
        // If product has additional images, add them to gallery
        // For now, just use the main product image
    }
    
    public function selectImage($image)
    {
        $this->mainImage = $image;
    }
    
    public function zoomImage($image)
    {
        $this->zoomedImage = $image;
        $this->showZoom = true;
    }
    
    public function closeZoom()
    {
        $this->showZoom = false;
        $this->zoomedImage = '';
    }
    
    public function render()
    {
        return view('livewire.image-gallery');
    }
}

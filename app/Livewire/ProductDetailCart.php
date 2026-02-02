<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductDetailCart extends Component
{
    public $productId;
    
    public function mount($productId)
    {
        $this->productId = $productId;
    }
    
    public function addToCart()
    {
        $product = Product::findOrFail($this->productId);
        $cart = Session::get('cart', []);
        $cart[$this->productId] = ($cart[$this->productId] ?? 0) + 1;
        Session::put('cart', $cart);
        
        $this->dispatch('cart-updated');
        
        session()->flash('message', 'Item added to cart successfully!');
    }
    
    public function render()
    {
        return view('livewire.product-detail-cart');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductFilter extends Component
{
    public $search = '';
    public $category = 'All';
    public $products = [];
    
    protected $queryString = ['search', 'category'];

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        $cart = Session::get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        Session::put('cart', $cart);
        
        $this->dispatch('cart-updated');
    }
    
    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
        
        $this->dispatch('cart-updated');
    }
    
    public function render()
    {
        $query = Product::query();
        
        if ($this->search) {
            $query->where('productName', 'LIKE', "%{$this->search}%");
        }
        
        if ($this->category !== 'All') {
            $query->where('category', $this->category);
        }
        
        $products = $query->get();
        
        $cartItems = Session::get('cart', []);
        $cartCount = collect($cartItems)->sum();
        $totalPrice = 0;
        
        foreach ($cartItems as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;
            }
        }

        return view('livewire.product-filter', [
            'products' => $products,
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'totalPrice' => $totalPrice,
        ]);
    }
    
    public function updated($field)
    {
        // Refresh when search or category changes
        if ($field === 'search' || $field === 'category') {
            $this->render();
        }
    }
}
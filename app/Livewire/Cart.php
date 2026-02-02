<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $cartCount = 0;
    public $totalPrice = 0;
    
    protected $listeners = ['cart-updated' => 'refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.cart', [
            'cartItems' => $this->cartItems,
            'cartCount' => $this->cartCount,
            'totalPrice' => $this->totalPrice,
        ]);
    }

    public function refreshCart()
    {
        $this->cartItems = Session::get('cart', []);
        $this->cartCount = collect($this->cartItems)->sum();
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->totalPrice = 0;
        
        foreach ($this->cartItems as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $qty;
                $this->totalPrice += $subtotal;
            }
        }
    }

    public function removeFromCart($productId)
    {
        $cart = $this->cartItems;
        unset($cart[$productId]);
        Session::put('cart', $cart);
        $this->cartItems = $cart;
        $this->cartCount = collect($cart)->sum();
        $this->calculateTotal();
        
        $this->dispatch('cart-updated');
    }
    
    public function updateQuantity($productId, $newQuantity)
    {
        if ($newQuantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }
        
        $cart = $this->cartItems;
        $cart[$productId] = $newQuantity;
        Session::put('cart', $cart);
        $this->cartItems = $cart;
        $this->calculateTotal();
        
        $this->dispatch('cart-updated');
    }
}
<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CartIcon extends Component
{
    public $cartCount = 0;
    
    protected $listeners = ['cart-updated' => 'refreshCartCount'];

    public function mount()
    {
        $this->refreshCartCount();
    }

    public function refreshCartCount()
    {
        $cartItems = Session::get('cart', []);
        $this->cartCount = collect($cartItems)->sum();
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
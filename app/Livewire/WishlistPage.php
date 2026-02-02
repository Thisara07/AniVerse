<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistPage extends Component
{
    public $wishlistItems = [];
    public $cartItems = [];
    public $cartCount = 0;
    public $totalPrice = 0;
    
    public function mount()
    {
        $this->loadWishlist();
        $this->loadCart();
    }
    
    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::with('product')
                ->where('user_id', Auth::id())
                ->get()
                ->toArray();
        } else {
            $this->wishlistItems = [];
        }
    }
    
    public function loadCart()
    {
        $this->cartItems = Session::get('cart', []);
        $this->cartCount = collect($this->cartItems)->sum();
        $this->totalPrice = 0;
        
        foreach ($this->cartItems as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $this->totalPrice += $product->price * $qty;
            }
        }
    }
    
    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
        
        $this->loadCart();
        $this->dispatch('cart-updated');
    }
    
    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        $cart = Session::get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        Session::put('cart', $cart);
        
        $this->loadCart();
        $this->dispatch('cart-updated');
    }
    
    public function removeFromWishlist($wishlistId)
    {
        if (!Auth::check()) return;
        
        Wishlist::where([
            'id' => $wishlistId,
            'user_id' => Auth::id()
        ])->delete();
        
        $this->loadWishlist();
        $this->dispatch('wishlist-removed', message: 'Item removed from wishlist');
    }
    
    public function moveToCart($wishlistId)
    {
        if (!Auth::check()) return;
        
        $wishlistItem = Wishlist::where([
            'id' => $wishlistId,
            'user_id' => Auth::id()
        ])->first();
        
        if ($wishlistItem) {
            // Add to cart session
            $cart = session()->get('cart', []);
            $cart[$wishlistItem->product_id] = ($cart[$wishlistItem->product_id] ?? 0) + 1;
            session()->put('cart', $cart);
            
            // Remove from wishlist
            $wishlistItem->delete();
            
            $this->loadWishlist();
            $this->loadCart(); // Refresh cart data
            $this->dispatch('moved-to-cart', message: 'Item moved to cart');
        }
    }
    
    public function render()
    {
        return view('livewire.wishlist-page');
    }
}

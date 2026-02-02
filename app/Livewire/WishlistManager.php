<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistManager extends Component
{
    public $wishlistItems = [];
    public $wishlistCount = 0;
    public $productId = 0;
    public $isWishlisted = false;
    public $showCount = false;
    
    public function mount($productId = null, $isWishlisted = false, $showCount = false)
    {
        $this->productId = $productId ?? 0;
        $this->showCount = $showCount;
        $this->loadWishlist();
        
        // Check if this specific product is wishlisted
        if ($this->productId && Auth::check()) {
            $this->isWishlisted = Wishlist::where([
                'user_id' => Auth::id(),
                'product_id' => $this->productId
            ])->exists();
        }
    }
    
    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::with('product')
                ->where('user_id', Auth::id())
                ->get()
                ->toArray();
            $this->wishlistCount = count($this->wishlistItems);
        } else {
            $this->wishlistItems = [];
            $this->wishlistCount = 0;
        }
    }
    
    public function toggleWishlist($productId = null)
    {
        $productId = $productId ?? $this->productId;
        
        if (!Auth::check()) {
            $this->dispatch('wishlist-error', message: 'Please login to add items to wishlist');
            return;
        }
        
        $existing = Wishlist::where([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ])->first();
        
        if ($existing) {
            $existing->delete();
            $this->isWishlisted = false;
            $this->dispatch('wishlist-removed', message: 'Removed from wishlist');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $this->isWishlisted = true;
            $this->dispatch('wishlist-added', message: 'Added to wishlist');
        }
        
        $this->loadWishlist();
    }
    
    public function removeFromWishlist($wishlistId)
    {
        if (!Auth::check()) return;
        
        Wishlist::where([
            'id' => $wishlistId,
            'user_id' => Auth::id()
        ])->delete();
        
        $this->loadWishlist();
        $this->dispatch('wishlist-removed', message: 'Removed from wishlist');
    }
    
    public function moveToCart($wishlistId)
    {
        if (!Auth::check()) return;
        
        $wishlistItem = Wishlist::where([
            'id' => $wishlistId,
            'user_id' => Auth::id()
        ])->first();
        
        if ($wishlistItem) {
            // Add to cart session (similar to existing cart logic)
            $cart = session()->get('cart', []);
            $cart[$wishlistItem->product_id] = ($cart[$wishlistItem->product_id] ?? 0) + 1;
            session()->put('cart', $cart);
            
            // Remove from wishlist
            $wishlistItem->delete();
            
            $this->loadWishlist();
            $this->dispatch('moved-to-cart', message: 'Item moved to cart');
        }
    }
    
    public function render()
    {
        return view('livewire.wishlist-manager');
    }
}

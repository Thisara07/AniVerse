<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductFilter extends Component
{
    public $search = '';
    public $category = 'All';
    public $products = [];
    public $categories = [];
    public $wishlistItems = [];
    
    public function mount()
    {
        $this->categories = ['All', 'Figures', 'Clothing', 'Posters', 'Accessories'];
        $this->loadWishlist();
    }
    
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
    
    public function toggleWishlist($productId)
    {
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
            $this->dispatch('wishlist-removed', message: 'Removed from wishlist');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $this->dispatch('wishlist-added', message: 'Added to wishlist');
        }
        
        $this->loadWishlist();
    }
    
    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $this->wishlistItems = [];
        }
    }
    
    public function render()
    {
        $this->products = Product::search($this->search)
            ->category($this->category)
            ->get();
        
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
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'totalPrice' => $totalPrice,
        ]);
    }
    
    public function setCategory($category)
    {
        $this->category = $category;
        $this->render();
    }
    
    public function updated($field)
    {
        // Refresh when search or category changes
        if ($field === 'search' || $field === 'category') {
            $this->render();
        }
    }
}
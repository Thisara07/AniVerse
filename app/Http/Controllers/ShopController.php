<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search', '');
        $selectedCategory = $request->get('category', 'All');
        
        $query = Product::query();
        
        if ($searchTerm) {
            $query->where('productName', 'LIKE', "%{$searchTerm}%");
        }
        
        if ($selectedCategory !== 'All') {
            $query->where('category', $selectedCategory);
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
        
        return view('shop.index', compact('products', 'cartItems', 'cartCount', 'totalPrice', 'selectedCategory', 'searchTerm'));
    }
    
    public function byCategory($category)
    {
        $products = Product::where('category', $category)->get();
        
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
        
        return view('shop.index', compact('products', 'cartItems', 'cartCount', 'totalPrice', 'category'));
    }
    
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = Session::get('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Item added to cart!');
    }
    
    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}

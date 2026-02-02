<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = [
            ["title"=>"Figures","subtitle"=>"Best Selling","desc"=>"Check out our new arrivals","color"=>"#2D2D2D","img"=>"images/figure2.png","link"=>"shop?category=Figures","tag"=>"New","btnClass"=>"btn"],
            ["title"=>"Tshirts","subtitle"=>"Top New Arrivals","desc"=>"Discover The New Design","color"=>"#9B5DE5","img"=>"images/prod_68d11d4901c420.81225543.png","link"=>"shop?category=Clothing","tag"=>"","btnClass"=>"btn dark"],
            ["title"=>"Posters","subtitle"=>"Popular Picks","desc"=>"Decorate your room with anime vibes","color"=>"#d7773cff","img"=>"images/prod_68d120c20c51c0.11527591.jpg","link"=>"shop?category=Posters","tag"=>"","btnClass"=>"btn"],
            ["title"=>"Accessories","subtitle"=>"Hot Trends","desc"=>"Keychains, mugs and more","color"=>"#00BFA6","img"=>"images/keychain.png","link"=>"shop?category=Accessories","tag"=>"","btnClass"=>"btn light"],
            ["title"=>"Manga","subtitle"=>"Reader’s Choice","desc"=>"Complete your collection with top manga volumes","color"=>"#FF4C61","img"=>"images/manga.png","link"=>"shop?category=Manga","tag"=>"Hot","btnClass"=>"btn"],
            ["title"=>"Cosplay","subtitle"=>"Best Costumes","desc"=>"Bring your favorite characters to life","color"=>"#3A86FF","img"=>"images/cosplay.png","link"=>"shop?category=Cosplay","tag"=>"","btnClass"=>"btn dark"]
        ];
        
        $cartItems = session('cart', []);
        $cartCount = collect($cartItems)->sum();
        $totalPrice = 0;
        
        foreach ($cartItems as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;
            }
        }
        
        return view('home.index', compact('products', 'categories', 'cartItems', 'cartCount', 'totalPrice'));
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Auth\SocialController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{category}', [ShopController::class, 'byCategory'])->name('shop.category');
Route::get('/product/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    return view('products.show', compact('product'));
})->name('product.show');

Route::get('/wishlist', function () {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('login');
    }
    
    $wishlistItems = \App\Models\Wishlist::with('product')
        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->get()
        ->toArray();
    
    return view('wishlist.index', compact('wishlistItems'));
})->name('wishlist.index')->middleware('auth');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');

Route::post('/wishlist/{id}/move-to-cart', function ($id) {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('login');
    }
    
    $wishlistItem = \App\Models\Wishlist::where([
        'id' => $id,
        'user_id' => \Illuminate\Support\Facades\Auth::id()
    ])->first();
    
    if ($wishlistItem) {
        // Add to cart session
        $cart = session()->get('cart', []);
        $cart[$wishlistItem->product_id] = ($cart[$wishlistItem->product_id] ?? 0) + 1;
        session()->put('cart', $cart);
        
        // Remove from wishlist
        $wishlistItem->delete();
        
        return redirect()->back()->with('success', 'Item moved to cart successfully!');
    }
    
    return redirect()->back()->with('error', 'Item not found in wishlist.');
})->name('wishlist.move-to-cart')->middleware('auth');

Route::delete('/wishlist/{id}', function ($id) {
    if (!\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('login');
    }
    
    \App\Models\Wishlist::where([
        'id' => $id,
        'user_id' => \Illuminate\Support\Facades\Auth::id()
    ])->delete();
    
    return redirect()->back()->with('success', 'Item removed from wishlist!');
})->name('wishlist.remove')->middleware('auth');
Route::post('/cart/add/{id}', [ShopController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');

// JetStream handles authentication routes automatically
// Custom logout route for API compatibility
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Social Authentication Routes
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('/checkout', [CheckoutController::class, 'index'])->middleware('auth')->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('auth');

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth');
Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->middleware('auth')->name('profile.change-password');
Route::get('/profile/orders/{orderId}', [ProfileController::class, 'orderDetails'])->middleware('auth')->name('profile.order-details');

Route::get('/my-orders', [OrderController::class, 'index'])->middleware('auth')->name('my.orders');
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth')->name('orders.index');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/products/{productId}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{productId}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');
    Route::get('/products/edit/{productId}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::put('/orders/{orderId}', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');
    
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/users/{userId}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
    Route::delete('/users/{userId}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

// Separate Login Routes
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login')->middleware('guest');

Route::get('/user/login', function () {
    return view('auth.user-login');
})->name('user.login')->middleware('guest');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

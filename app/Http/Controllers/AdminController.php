<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Payment;

class AdminController extends Controller
{
    public function products()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }
    
    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])->orderBy('created_at', 'desc')->get();
        $payments = Payment::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.orders', compact('orders', 'payments'));
    }
    
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
    
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,completed,cancelled'
        ]);
        
        $order = Order::findOrFail($orderId);
        $order->update([
            'status' => $request->status
        ]);
        
        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully!');
    }
    
    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:customer,admin'
        ]);
        
        $user = User::findOrFail($userId);
        $user->update([
            'role' => $request->role
        ]);
        
        return redirect()->route('admin.users')->with('success', 'User role updated successfully!');
    }
    
    public function destroyUser($userId)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($userId);
        
        // Prevent deleting yourself
        if ($user->id == $currentUser->id) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete yourself!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
    
    public function storeProduct(Request $request)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        Product::create([
            'productName' => $request->productName,
            'category' => $request->category,
            'price' => $request->price,
            'image' => $imagePath
        ]);
        
        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }
    
    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $products = Product::all();
        return view('admin.products', compact('product', 'products'));
    }
    
    public function updateProduct(Request $request, $productId)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $product = Product::findOrFail($productId);
        
        $data = [
            'productName' => $request->productName,
            'category' => $request->category,
            'price' => $request->price
        ];
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);
        
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }
    
    public function destroyProduct($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty!');
        }
        
        $productIds = array_keys($cartItems);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $totalPrice = 0;
        
        foreach ($cartItems as $id => $qty) {
            $product = $products->get($id);
            if ($product) {
                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;
            }
        }
        
        return view('checkout.index', compact('products', 'cartItems', 'totalPrice'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'payment' => 'required|in:Credit Card,PayPal,Cash on Delivery',
        ]);
        
        $user = Auth::user();
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty!');
        }
        
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'fullName' => $request->name,
                'email' => $request->email,
                'phoneNo' => $user->phoneNo ?? '',
                'address' => $request->address,
                'city' => '',
                'state' => '',
                'zipCode' => '',
                'country' => '',
                'total_amount' => 0, // Will update after adding items
                'status' => 'pending',
            ]);
            
            $totalPrice = 0;
            
            // Create order items
            foreach ($cartItems as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    $subtotal = $product->price * $qty;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $qty,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                    $totalPrice += $subtotal;
                }
            }
            
            // Update order total
            $order->update(['total_amount' => $totalPrice]);
            
            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $totalPrice,
                'payment_type' => $request->payment,
            ]);
            
            DB::commit();
            
            // Dispatch Order Placed Event
            \App\Events\OrderPlaced::dispatch($order);
            
            // Clear cart
            Session::forget('cart');
            
            return redirect()->route('home')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('checkout')->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }
}
@extends('layouts.app')

@section('title', 'Checkout - AniVerse')

@section('content')
<div class="bg-[url('/images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen">
<!-- Header -->
<header class="flex justify-between items-center px-6 py-4">
    <a href="{{ route('home') }}" class="text-white text-sm font-semibold hover:underline">← Back to Store</a>
    <a href="{{ route('checkout') }}" class="text-white text-sm font-semibold hover:underline">🛒 Cart ({{ count($cartItems) }})</a>
</header>

<main class="flex-grow flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 font-[Poppins]">

        <h1 class="text-2xl font-bold text-purple-700 mb-6">Checkout</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-400 text-green-800 rounded">
                {{ session('success') }}
            </div>
            <a href="{{ route('home') }}" class="text-purple-700 hover:underline">Continue Shopping</a>
        @else
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if (empty($cartItems))
                <p class="mb-4 text-gray-700">Your cart is empty.</p>
                <a href="{{ route('home') }}" class="text-purple-700 hover:underline">Return to store</a>
            @else
                <!-- Order Summary -->
                <section class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Your Order</h2>
                    @php
                        $totalPrice = 0;
                    @endphp
                    @foreach ($cartItems as $id => $qty)
                        @php
                            $item = $products[$id] ?? null;
                            if($item) {
                                $subtotal = $item->price * $qty;
                                $totalPrice += $subtotal;
                            }
                        @endphp
                        @if($item)
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-800">
                                {{ $item->productName }} (x{{ $qty }})
                            </span>
                            <span class="font-medium text-gray-800">
                                ${{ number_format($subtotal, 2) }}
                            </span>
                        </div>
                        @endif
                    @endforeach

                    <div class="border-t pt-4 flex justify-between font-semibold text-gray-900">
                        <span>Total</span>
                        <span>${{ number_format($totalPrice, 2) }}</span>
                    </div>
                </section>

                <!-- Customer Info & Payment -->
                <section>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Info</h2>
                    <form method="post" action="{{ route('checkout') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-gray-700 font-medium">Name</label>
                            <input
                              type="text" name="name" required
                              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                            />
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium">Email</label>
                            <input
                              type="email" name="email" required
                              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                            />
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium">Address</label>
                            <textarea
                              name="address" rows="3" required
                              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium">Payment Method</label>
                            <select
                              name="payment" required
                              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"
                            >
                                <option value="">-- Choose Payment --</option>
                                <option value="Credit Card">💳 Credit Card</option>
                                <option value="PayPal">🅿️ PayPal</option>
                                <option value="Cash on Delivery">💵 Cash on Delivery</option>
                            </select>
                        </div>

                        <button
                          type="submit"
                          class="w-full bg-purple-700 text-white py-2 rounded-md font-semibold hover:bg-purple-800 transition-colors"
                        >
                            Place Order
                        </button>
                    </form>
                </section>
            @endif
        @endif
    </div>
</main>
</div>
@endsection
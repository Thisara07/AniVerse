@extends('layouts.app')

@section('title', 'My Orders - AniVerse')

@section('content')
<div class="bg-[url('/images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('shop') }}" class="text-white text-sm font-semibold hover:underline mb-4 inline-block">← Back to Shop</a>
        
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">My Orders</h1>

        @if($orders->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <h3 class="text-xl font-medium text-gray-700 mb-2">No orders yet</h3>
                <p class="text-gray-500 mb-6">Your order history will appear here once you make purchases!</p>
                <a href="{{ route('shop') }}" class="bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition">
                    Start Shopping
                </a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Order Header -->
                    <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 border-b">
                        <div class="mb-2 sm:mb-0">
                            <h2 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h2>
                            <p class="text-sm text-gray-600">
                                Placed: {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if(strtolower($order->status) == 'pending') bg-yellow-100 text-yellow-800
                                @elseif(strtolower($order->status) == 'shipped') bg-blue-100 text-blue-800
                                @elseif(strtolower($order->status) == 'completed') bg-green-100 text-green-800
                                @elseif(strtolower($order->status) == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <button class="text-purple-700 hover:text-purple-900 text-sm font-medium">
                                Track Order
                            </button>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-4">
                                        @php
                                            $itemImagePath = '';
                                            if($item->product->image) {
                                                if(file_exists(public_path($item->product->image))) {
                                                    $itemImagePath = asset($item->product->image);
                                                } else {
                                                    $itemImagePath = asset('storage/'.$item->product->image);
                                                }
                                            } else {
                                                $itemImagePath = asset('images/default-product.png');
                                            }
                                        @endphp
                                        <img 
                                            src="{{ $itemImagePath }}" 
                                            alt="{{ $item->product->productName }}" 
                                            class="w-16 h-16 object-cover rounded-lg"
                                        >
                                        <div>
                                            <h3 class="font-medium text-gray-800">{{ $item->product->productName }}</h3>
                                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-800">${{ number_format($item->price, 2) }} each</p>
                                        <p class="text-sm text-gray-600">Subtotal: ${{ number_format($item->subtotal, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Total -->
                        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Order Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>
                                @if($order->tracking_number)
                                    <p class="text-sm text-gray-600">Tracking: <span class="font-mono">{{ $order->tracking_number }}</span></p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-purple-700">Total: ${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
            </div>
        </div>
    </div>
</div>
@endsection
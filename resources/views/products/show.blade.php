@extends('layouts.app')

@section('title', $product->productName . ' - AniVerse')

@section('content')
<div class="bg-[url('/images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('shop') }}" class="text-white text-sm font-semibold hover:underline mb-4 inline-block">← Back to Shop</a>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-6xl mx-auto">
            <div class="md:flex">
                <div class="md:w-1/2 p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->productName }}</h1>
                    <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                    <div class="text-2xl font-bold text-purple-700 mb-6">${{ number_format($product->price, 2) }}</div>
                    
                    <!-- Image Gallery Section -->
                    <livewire:image-gallery :product-id="$product->id" />
                </div>
                
                <div class="md:w-1/2 p-6 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Details</h2>
                    <div class="mb-4">
                        <span class="font-medium text-gray-700">Category:</span>
                        <span class="ml-2 text-gray-600">{{ $product->category }}</span>
                    </div>
                    <div class="mb-6">
                        <span class="font-medium text-gray-700">Availability:</span>
                        <span class="ml-2 text-green-600">In Stock</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <livewire:product-detail-cart :product-id="$product->id" />
                        <livewire:wishlist-manager :product-id="$product->id" :is-wishlisted="false" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
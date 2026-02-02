@extends('layouts.app')

@section('title', 'My Wishlist - AniVerse')

@section('content')
<div class="bg-[url('/images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('shop') }}" class="text-white text-sm font-semibold hover:underline mb-4 inline-block">← Back to Shop</a>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-6xl mx-auto">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">My Wishlist</h1>
                
                <livewire:wishlist-page />
            </div>
        </div>
    </div>
</div>
@endsection
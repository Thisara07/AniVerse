@extends('layouts.app')

@section('content')
<div class="bg-[url('images/wallpaper7.jpg')] bg-center bg-top bg-fixed bg-cover min-h-screen px-5 py-10">
    <header class="flex justify-between items-center mb-10">
        <a href="{{ route('home') }}" class="block">
            <img src="{{ asset('images/AniVerse-icon.png') }}" alt="AniVerse" class="h-20 w-auto"/>
        </a>
        <div class="text-3xl font-bold text-purple-700">
            <a href="{{ route('home') }}">AniVerse</a>
        </div>
    </header>

    <!-- About Container -->
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-8 text-base leading-relaxed">
        <h2 class="text-center text-[#4a90e2] text-2xl font-semibold mb-5">
            About AniVerse
        </h2>

        <p class="mb-6">
            Welcome to <strong>AniVerse</strong> — your one-stop shop for all
            things anime! We are passionate fans just like you, dedicated to
            bringing you high-quality anime figures, posters, clothing, and
            collectibles.
        </p>

        <h3 class="mt-6 text-lg font-semibold text-gray-800">Our Mission</h3>
        <p class="mb-6">
            To connect anime lovers worldwide with the best merchandise, while
            creating a trusted and fun shopping experience. We carefully select
            each product to ensure it meets the standards our fellow fans deserve.
        </p>

        <h3 class="mt-6 text-lg font-semibold text-gray-800">Why Shop With Us?</h3>
        <ul class="list-none p-0 mb-6">
            @php
                $reasons = [
                    "Wide range of anime merchandise",
                    "Secure checkout & fast delivery",
                    "Free shipping for orders over $50",
                    "Friendly customer support"
                ];
            @endphp
            @foreach ($reasons as $reason)
                <li class="relative pl-6 my-2">
                    <span class="absolute left-0">⭐</span>
                    {{ $reason }}
                </li>
            @endforeach
        </ul>

        <h3 class="mt-6 text-lg font-semibold text-gray-800">Contact Us</h3>
        <p>
            Got questions or suggestions? We'd love to hear from you!<br />
            Email:
            <a href="mailto:support@aniverse.com" class="text-purple-700 hover:underline">support@aniverse.com</a><br />
            Phone:
            <a href="tel:1234567890" class="text-purple-700 hover:underline">123-456-7890</a>
        </p>
    </div>
</div>
@endsection
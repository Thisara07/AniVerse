@extends('layouts.app')

@section('title', 'Home - AniVerse')

@section('styles')
<style>
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes fadeInLeft {
    from {
      opacity: 0;
      transform: translateX(-30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes fadeInRight {
    from {
      opacity: 0;
      transform: translateX(30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes pulseGlow {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
  }
  
  @keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
  }
  
  @keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
  }
  
  .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
  .animate-fade-in-left { animation: fadeInLeft 0.6s ease-out forwards; }
  .animate-fade-in-right { animation: fadeInRight 0.6s ease-out forwards; }
  .animate-pulse-glow { animation: pulseGlow 2s infinite; }
  .animate-float { animation: float 3s ease-in-out infinite; }
  .animate-bounce-in { animation: bounceIn 0.8s ease-out forwards; }
  
  /* Staggered animations */
  .stagger-animation:nth-child(1) { animation-delay: 0.1s; }
  .stagger-animation:nth-child(2) { animation-delay: 0.2s; }
  .stagger-animation:nth-child(3) { animation-delay: 0.3s; }
  .stagger-animation:nth-child(4) { animation-delay: 0.4s; }
  .stagger-animation:nth-child(5) { animation-delay: 0.5s; }
  .stagger-animation:nth-child(6) { animation-delay: 0.6s; }
</style>
@endsection

@section('content')
<div class="min-h-screen" style="background-image: url('{{ asset('images/wallpaper7.jpg') }}'); background-size: cover; background-position: center top; background-attachment: fixed; background-repeat: no-repeat;">
  

  
  <!-- Hero Section - Recreated from scratch -->
  <div style="height: 400px; background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('/images/anime-hero.jpg'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; text-align: center; position: relative;">
         
      <!-- Cart Icon - Top Right Corner of Hero Section -->
      <div style="position: absolute; top: 20px; right: 20px; z-index: 20;">
          <livewire:cart-icon />
      </div>
      <div style="background: rgba(0,0,0,0.7); padding: 2rem; border-radius: 1rem; max-width: 600px; margin: 0 1rem;">
          <div style="color: white;">
              <span style="background: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: bold; display: inline-block; margin-bottom: 1rem;">🔥 Hot Deals</span>
              <h1 style="font-size: 2.25rem; font-weight: bold; margin: 1rem 0;">Exclusive Anime Merch for Every Fan</h1>
              <p style="margin: 1rem 0 1.5rem 0;">Get your favorite anime gear at the best prices</p>
              <a href="{{ route('shop') }}?category=All" 
                 style="display: inline-block; padding: 1rem 1.5rem; background: linear-gradient(to right, #7e22ce, #3730a3); color: white; border-radius: 9999px; font-weight: bold; text-decoration: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); transition: all 0.3s;"
                 onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1)'"
                 onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)'">
                  🚀 Shop Now
              </a>
          </div>
      </div>
  </div>

  <!-- Categories -->
  <section class="px-5 sm:px-10 py-12">
      
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          @foreach ($categories as $cat)
              <div class="relative rounded-2xl p-8 text-white overflow-hidden flex items-center justify-between transition-all duration-500 hover:scale-105 cursor-pointer stagger-animation animate-fade-in-up group" style="background-color: {{ $cat['color'] }}; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                  <!-- Hover overlay effect -->
                  <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  
                  <img
                    src="{{ asset($cat['img']) }}"
                    alt="{{ $cat['title'] }}"
                    class="max-h-[220px] object-contain flex-shrink-0 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3"
                  />
                  <div class="max-w-[50%] relative z-10">
                      @if (!empty($cat['tag']))
                          <span class="bg-red-500 px-3 py-1.5 rounded-full text-sm font-bold inline-block mb-2 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6 animate-pulse-glow">{{ $cat['tag'] }}</span>
                      @endif
                      <h4 class="text-xl font-semibold my-3 transform transition-transform duration-300 group-hover:translate-x-2">{{ $cat['subtitle'] }}</h4>
                      <h2 class="text-2xl my-3 font-bold transform transition-transform duration-300 group-hover:translate-x-3">{{ $cat['title'] }}</h2>
                      <p class="mb-5 opacity-90 transform transition-transform duration-300 group-hover:translate-x-1">{{ $cat['desc'] }}</p>
                      @php
                          $btnClass = '';
                          switch ($cat['btnClass']) {
                              case 'btn':
                                  $btnClass = 'bg-red-500 text-white';
                                  break;
                              case 'btn dark':
                                  $btnClass = 'bg-black text-white';
                                  break;
                              case 'btn light':
                                  $btnClass = 'bg-white text-black';
                                  break;
                              default:
                                  $btnClass = 'bg-red-500 text-white';
                          }
                      @endphp
                      <a
                        href="{{ route('shop.category', ['category' => str_replace('category=', '', $cat['link'])]) }}"
                        class="{{ $btnClass }} px-5 py-2.5 rounded-lg font-bold inline-block transform transition-all duration-300 hover:scale-110 hover:-translate-y-1 shadow-lg group-hover:shadow-xl"
                      >🛍️ Shop Now</a>
                  </div>
              </div>
          @endforeach
      </div>
  </section>



</div>
@endsection
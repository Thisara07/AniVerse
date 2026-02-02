<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('product-filter', \App\Livewire\ProductFilter::class);
        Livewire::component('cart', \App\Livewire\Cart::class);
        Livewire::component('admin-panel', \App\Livewire\AdminPanel::class);
    }
}
<div>
    <!-- Header -->
    <header class="flex flex-wrap justify-between items-center px-5 py-4">
        <a href="{{ route('home') }}" class="block"><img src="{{ asset('images/AniVerse-icon.png') }}" alt="AniVerse" class="h-20 w-auto"/></a>
    
        <form wire:submit.prevent="render" class="flex w-full sm:w-96 mt-3 sm:mt-0">
            <input
              type="text"
              wire:model.live="search"
              placeholder="Search Anime Merch..."
              class="w-full px-3 py-2 border border-black rounded-l-full focus:outline-none bg-white"
            />
            <button
              type="submit"
              class="px-5 py-2 bg-purple-700 text-white rounded-r-full"
            >🔍</button>
        </form>
    
        <a
          href="{{ route('checkout') }}"
          class="mt-3 sm:mt-0 bg-purple-700 text-white px-4 py-2 rounded-full font-bold hover:bg-purple-800"
        >
            🛒 Cart ({{ $cartCount }})
        </a>
    </header>
    
    <!-- Shop Section -->
    <section class="px-5 py-10">
        <h1 class="text-3xl text-white font-semibold mb-6">
            {{ $category }} Products
        </h1>
    
        <!-- Category Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-6 justify-center">
            @foreach($categories as $cat)
                <button
                    wire:click="setCategory('{{ $cat }}')"
                    class="px-4 py-2 rounded-full font-medium transition-colors duration-300 {{ $category === $cat ? 'bg-purple-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    
        <div
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5"
        >
            @if ($products->isEmpty())
                <p class="col-span-full text-center">No products found.</p>
            @else
                @foreach ($products as $product)
                    <div
                      class="bg-white border border-gray-300 rounded-lg p-4 text-center
                             transition-transform duration-300 hover:scale-105">
                        <a href="{{ route('product.show', $product->id) }}">
                        <img
                          @php
                              $productImagePath = '';
                              if($product->image) {
                                  if(file_exists(public_path($product->image))) {
                                      $productImagePath = asset($product->image);
                                  } else {
                                      $productImagePath = asset('storage/'.$product->image);
                                  }
                              } else {
                                  $productImagePath = asset('images/default-product.png');
                              }
                          @endphp
                          src="{{ $productImagePath }}"
                          alt="{{ $product->productName }}"
                          class="w-full h-44 object-cover rounded-lg cursor-pointer"
                        />
                        <h3 class="text-base font-medium mt-4 cursor-pointer">{{ $product->productName }}</h3>
                        </a>
                        <p class="text-sm font-bold text-purple-700 mt-2">
                            ${{ number_format($product->price, 2) }}
                        </p>
                        <div class="flex justify-center space-x-2 mt-3">
                            <form wire:submit.prevent="addToCart({{ $product->id }})" class="inline">
                                @csrf
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        wire:target="addToCart({{ $product->id }})"
                                        class="px-3 py-1.5 bg-purple-700 text-white
                                               rounded-full text-sm font-bold transition-all duration-300
                                               hover:bg-purple-800 hover:scale-105
                                               active:scale-95
                                               disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Cart</span>
                                    <span wire:loading wire:target="addToCart({{ $product->id }})" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Adding...
                                    </span>
                                </button>
                            </form>
                            
                            <button 
                                wire:click="toggleWishlist({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="toggleWishlist({{ $product->id }})"
                                class="px-3 py-1.5 border-2 border-pink-500 text-pink-500
                                       rounded-full text-sm font-bold transition-all duration-300
                                       hover:bg-pink-500 hover:text-white hover:scale-105
                                       active:scale-95 flex items-center space-x-1
                                       disabled:opacity-50 disabled:cursor-not-allowed"
                                title="{{ in_array($product->id, $wishlistItems) ? 'Remove from wishlist' : 'Add to wishlist' }}"
                            >
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-4 w-4 transition-all duration-300 {{ in_array($product->id, $wishlistItems) ? 'fill-current text-red-500 animate-pulse' : '' }}" 
                                    viewBox="0 0 20 20" 
                                    fill="currentColor"
                                >
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ in_array($product->id, $wishlistItems) ? 'Wishlisted' : 'Wishlist' }}</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
    
    <!-- Mini Cart -->
    <section class="bg-white/80 backdrop-blur-md rounded-lg p-6 mx-auto my-5 max-w-3xl shadow-lg font-[Poppins]">
        <h2 class="text-2xl font-semibold mb-4">Your Cart</h2>
    
        @if (empty($cartItems))
            <p>Your cart is empty.</p>
        @else
            @php
                $totalPrice = 0;
            @endphp
            @foreach ($cartItems as $id => $qty)
                @php
                    $item = $products->firstWhere('id', $id);
                    if($item) {
                        $subtotal = $item->price * $qty;
                        $totalPrice += $subtotal;
                    }
                @endphp
                @if($item)
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2.5">
                        <img
                          @php
                              $itemImagePath = '';
                              if($item->image) {
                                  if(file_exists(public_path($item->image))) {
                                      $itemImagePath = asset($item->image);
                                  } else {
                                      $itemImagePath = asset('storage/'.$item->image);
                                  }
                              } else {
                                  $itemImagePath = asset('images/default-product.png');
                              }
                          @endphp
                          src="{{ $itemImagePath }}"
                          alt="{{ $item->productName }}"
                          class="w-10 h-10 rounded-lg object-cover"
                        />
                        <span>{{ $item->productName }} (x{{ $qty }})</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span>${{ number_format($item->price * $qty, 2) }}</span>
                        <form wire:submit.prevent="removeFromCart({{ $id }})" class="inline">
                            <button type="submit"
                                    class="text-red-500 text-xl transition-transform duration-200
                                           hover:scale-125 hover:rotate-12">
                                ✖
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
    
            <div class="mt-4 text-lg font-bold">
                Total: ${{ number_format($totalPrice, 2) }}
            </div>
            <p class="mt-4">
                <a
                  href="{{ route('checkout') }}"
                  class="bg-gradient-to-br from-purple-700 to-blue-500 text-white
                         px-6 py-3 rounded-full font-bold text-lg shadow-md
                         transition-all duration-300 hover:from-purple-600 hover:to-blue-600 hover:-translate-y-1"
                >Proceed to Checkout</a>
            </p>
        @endif
    </section>
</div>
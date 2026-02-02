<div>
    @if(count($wishlistItems) == 0)
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-500 mb-6">Start adding products you love to your wishlist!</p>
            <a href="{{ route('shop') }}" class="bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @foreach($wishlistItems as $item)
                <div class="bg-white border border-gray-300 rounded-lg p-4 text-center transition-transform duration-300 hover:scale-105">
                    <a href="{{ route('product.show', $item['product']['id']) }}">
                        @php
                            $wishlistImagePath = '';
                            if($item['product']['image']) {
                                if(file_exists(public_path($item['product']['image']))) {
                                    $wishlistImagePath = asset($item['product']['image']);
                                } else {
                                    $wishlistImagePath = asset('storage/'.$item['product']['image']);
                                }
                            } else {
                                $wishlistImagePath = asset('images/default-product.png');
                            }
                        @endphp
                        <img 
                            src="{{ $wishlistImagePath }}" 
                            alt="{{ $item['product']['productName'] }}" 
                            class="w-full h-44 object-cover rounded-lg cursor-pointer"
                        >
                        <h3 class="text-base font-medium mt-4 cursor-pointer">{{ $item['product']['productName'] }}</h3>
                    </a>
                    
                    <p class="text-sm font-bold text-purple-700 mt-2">
                        ${{ number_format($item['product']['price'], 2) }}
                    </p>
                    
                    <div class="flex justify-center space-x-2 mt-3">
                        <button 
                            wire:click="moveToCart({{ $item['id'] }})"
                            wire:loading.attr="disabled"
                            wire:target="moveToCart({{ $item['id'] }})"
                            class="px-3 py-1.5 bg-purple-700 text-white rounded-full text-sm font-bold transition-all duration-300 hover:bg-purple-800 hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove wire:target="moveToCart({{ $item['id'] }})">Move to Cart</span>
                            <span wire:loading wire:target="moveToCart({{ $item['id'] }})" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                        
                        <button 
                            wire:click="removeFromWishlist({{ $item['id'] }})"
                            wire:loading.attr="disabled"
                            wire:target="removeFromWishlist({{ $item['id'] }})"
                            class="px-3 py-1.5 border-2 border-red-500 text-red-500 rounded-full text-sm font-bold transition-all duration-300 hover:bg-red-500 hover:text-white hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1"
                            title="Remove from wishlist"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Remove</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Mini Cart -->
    @if(!empty($cartItems))
        <div class="mt-8 bg-white/80 backdrop-blur-md rounded-lg p-6 shadow-lg font-[Poppins]">
            <h2 class="text-2xl font-semibold mb-4">Your Cart</h2>
            
            @php
                $totalPrice = 0;
                $cartProducts = \App\Models\Product::whereIn('id', array_keys($cartItems))->get();
            @endphp
            @foreach ($cartItems as $id => $qty)
                @php
                    $item = $cartProducts->firstWhere('id', $id);
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
                        <button 
                            wire:click="removeFromCart({{ $id }})"
                            class="text-red-500 text-xl transition-transform duration-200 hover:scale-125 hover:rotate-12"
                        >
                            ✖
                        </button>
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
        </div>
    @endif
</div>

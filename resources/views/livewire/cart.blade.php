<div>
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
                    $item = \App\Models\Product::find($id);
                    if($item) {
                        $subtotal = $item->price * $qty;
                        $totalPrice += $subtotal;
                    }
                @endphp
                @if($item)
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <div class="flex items-center gap-2.5">
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
                        <img
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
                            class="text-red-500 text-xl transition-transform duration-200
                                   hover:scale-125 hover:rotate-12">
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
        @endif
    </section>
</div>
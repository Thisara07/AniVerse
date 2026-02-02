<div class="relative">
    <!-- Wishlist Toggle Button -->
    <button 
        wire:click="toggleWishlist({{ $productId ?? 0 }})"
        class="flex items-center space-x-1 text-purple-700 hover:text-purple-900 transition-colors"
        title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
    >
        <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-5 w-5 {{ $isWishlisted ? 'fill-current text-red-500' : '' }}" 
            viewBox="0 0 20 20" 
            fill="currentColor"
        >
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>
        <span>{{ $isWishlisted ? 'Wishlisted' : 'Wishlist' }}</span>
    </button>
    
    <!-- Wishlist Count Badge (for header) -->
    @if(isset($showCount) && $showCount && $wishlistCount > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {{ $wishlistCount }}
        </span>
    @endif
</div>

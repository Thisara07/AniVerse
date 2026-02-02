<div>
    <!-- Top Navbar with Gradient Background -->
    <div class="bg-gradient-to-r from-purple-700 via-indigo-900 to-purple-700 text-white shadow">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center px-6 py-3">
            
            <!-- Left Side: Logo and Shipping Notice -->
            <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                <a href="{{ route('home') }}" class="block">
                    <img src="{{ asset('images/AniVerse-icon.png') }}" alt="AniVerse" class="h-12 w-auto"/>
                </a>
                
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5 text-yellow-400"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.2A1 1 0 006.7 20h10.6a1 1 0 001-.8L19 13M7 13H3m16 0h-4"/>
                    </svg>
                    <span class="text-sm font-medium">Free Shipping for orders over $50</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex flex-wrap items-center space-x-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Home</a>
                <a href="{{ route('shop') }}" class="hover:text-yellow-300 transition-colors">Shop</a>
                <a href="{{ route('about') }}" class="hover:text-yellow-300 transition-colors">About</a>
                <a href="{{ route('checkout') }}" class="hover:text-yellow-300 transition-colors">Checkout</a>
                
                @auth
                    <a href="{{ route('wishlist.index') }}" class="hover:text-yellow-300 transition-colors">Wishlist</a>
                    <a href="{{ route('orders.index') }}" class="hover:text-yellow-300 transition-colors">My Orders</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.products') }}" class="hover:text-yellow-300 transition-colors">Admin Dashboard</a>

                    @endif
                    <a href="{{ route('profile.show') }}" class="hover:text-yellow-300 transition-colors">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline" id="logoutForm">
                        @csrf
                        <button type="button" onclick="showLogoutConfirmation()" class="hover:text-yellow-300 transition-colors bg-transparent border-0 cursor-pointer">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-yellow-300 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-yellow-300 transition-colors">Register</a>
                @endauth
            </nav>
        </div>
    </div>
    
    <!-- Mobile Navigation -->
    <div class="sm:hidden bg-white px-5 py-4 shadow">
        
        <div class="flex flex-col space-y-2">
            @auth
                <a href="{{ route('about') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-full font-bold text-center">About</a>
                <a href="{{ route('checkout') }}" class="bg-purple-700 text-white px-4 py-2 rounded-full font-bold text-center">
                    🛒 Cart ({{ \Illuminate\Support\Facades\Session::get('cart_count', 0) }})
                </a>
                <a href="{{ route('wishlist.index') }}" class="bg-pink-500 text-white px-4 py-2 rounded-full font-bold text-center">❤️ Wishlist</a>
                <a href="{{ route('orders.index') }}" class="bg-orange-500 text-white px-4 py-2 rounded-full font-bold text-center">📦 My Orders</a>
                <a href="{{ route('profile.show') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-full font-bold text-center">Profile</a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.products') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-full font-bold text-center">Admin Dashboard</a>

                @endif
                <form method="POST" action="{{ route('logout') }}" id="mobileLogoutForm">
                    @csrf
                    <button type="button" onclick="showMobileLogoutConfirmation()" class="w-full bg-red-500 text-white px-4 py-2 rounded-full font-bold">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-purple-700 text-white px-4 py-2 rounded-full font-bold text-center">Login</a>
                <a href="{{ route('register') }}" class="bg-transparent border-2 border-purple-700 text-purple-700 px-4 py-2 rounded-full font-bold text-center hover:bg-purple-700 hover:text-white">Register</a>
            @endauth
        </div>
    </div>
    
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gradient-to-br from-purple-700 to-indigo-900 rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-400 mb-4">
                    <svg class="h-10 w-10 text-purple-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Ready to Leave?</h3>
                <p class="text-purple-100 mb-6">Are you sure you want to logout? Your session will end and you'll need to sign in again to access your account.</p>
                <div class="flex space-x-4 justify-center">
                    <button onclick="confirmLogout()" class="px-6 py-3 bg-yellow-400 text-purple-900 font-bold rounded-full hover:bg-yellow-300 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                        Yes, Logout
                    </button>
                    <button onclick="cancelLogout()" class="px-6 py-3 bg-white bg-opacity-20 text-white font-bold rounded-full hover:bg-opacity-30 transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showLogoutConfirmation() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }
        
        function showMobileLogoutConfirmation() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }
        
        function confirmLogout() {
            // Submit the appropriate form based on which logout button was clicked
            const logoutForm = document.getElementById('logoutForm') || document.getElementById('mobileLogoutForm');
            if (logoutForm) {
                logoutForm.submit();
            }
        }
        
        function cancelLogout() {
            document.getElementById('logoutModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelLogout();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cancelLogout();
            }
        });
    </script>
</div>

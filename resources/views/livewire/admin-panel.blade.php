<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-white mb-6">Admin Dashboard</h1>
        
        <!-- Navigation Tabs -->
        <div class="flex space-x-2 mb-6">
            <button wire:click="switchView('products')" 
                    class="py-3 px-6 font-medium text-base rounded-lg transition-all duration-200 {{ $activeView === 'products' ? 'bg-purple-700 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Manage Products
            </button>
            <button wire:click="switchView('orders')" 
                    class="py-3 px-6 font-medium text-base rounded-lg transition-all duration-200 {{ $activeView === 'orders' ? 'bg-purple-700 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Manage Orders
            </button>
            <button wire:click="switchView('users')" 
                    class="py-3 px-6 font-medium text-base rounded-lg transition-all duration-200 {{ $activeView === 'users' ? 'bg-purple-700 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Manage Users
            </button>
        </div>
        
        @if(session()->has('message'))
            <div class="alert alert-success bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif
        
        <!-- Content View -->
        @if($activeView === 'products')
            @livewire('admin.manage-products')
            <!--
            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
            <script>
                // Get the token from localStorage for API operations
                const token = localStorage.getItem('sanctum_token');
                
                // Set default axios configuration
                if (token) {
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }
                
                // Wait for DOM to be ready
                document.addEventListener('DOMContentLoaded', function() {
                    // Test API connection
                    const testApiBtn = document.getElementById('testApiBtn');
                    if (testApiBtn) {
                        testApiBtn.addEventListener('click', async () => {
                            try {
                                const response = await axios.get('/api/products-public');
                                document.getElementById('apiResults').innerHTML = `
                                    <div class="p-4 bg-green-100 text-green-700 rounded">
                                        <p>API Connected Successfully!</p>
                                        <p>Products Count: ${response.data.data?.length || response.data.length || 'Unknown'}</p>
                                    </div>
                                `;
                            } catch (error) {
                                console.error('API Error:', error);
                                document.getElementById('apiResults').innerHTML = `
                                    <div class="p-4 bg-red-100 text-red-700 rounded">
                                        <p>Error connecting to API: ${error.message}</p>
                                    </div>
                                `;
                            }
                        });
                    }
                    
                    // Load products via API
                    const loadProductsBtn = document.getElementById('loadProductsBtn');
                    if (loadProductsBtn) {
                        loadProductsBtn.addEventListener('click', async () => {
                            try {
                                const response = await axios.get('/api/products-public');
                                const products = response.data.data || response.data;
                                
                                let html = `<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 mt-4">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">`;
                                
                                products.forEach(product => {
                                    html += `
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap">${product.id}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">${product.productName}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">$${parseFloat(product.price).toFixed(2)}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">${product.category}</td>
                                        </tr>
                                    `;
                                });
                                
                                html += `</tbody></table></div>`;
                                
                                document.getElementById('apiResults').innerHTML = html;
                            } catch (error) {
                                console.error('Error loading products via API:', error);
                                document.getElementById('apiResults').innerHTML = `
                                    <div class="p-4 bg-red-100 text-red-700 rounded">
                                        <p>Error loading products: ${error.message}</p>
                                    </div>
                                `;
                            }
                        });
                    }
                });
            </script>
            -->
            
            <!-- Token Passing Script for Livewire -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Get the token from localStorage
                    const token = localStorage.getItem('sanctum_token');
                    
                    if (token) {
                        // Wait for Livewire to initialize
                        setTimeout(function() {
                            // Call the setToken method on the Livewire component
                            if (window.Livewire) {
                                window.Livewire.dispatch('set-token', { token: token });
                            }
                        }, 500); // Small delay to ensure Livewire is initialized
                    }
                });
            </script>
        @elseif($activeView === 'orders')
            @livewire('admin.manage-orders')
        @elseif($activeView === 'users')
            @livewire('admin.manage-users')
        @endif
    </div>
</div>
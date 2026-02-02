<div>
    <!-- Success/Error Messages -->
    @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    
    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Token Status Display -->
    <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded">
        <p>Token Status: <span id="tokenStatus">{{ $sanctumToken ? 'Authenticated' : 'Not Authenticated' }}</span></p>
    </div>
    
    <!-- Product Creation Form (Using Livewire) -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">
            {{ $editingProductId ? 'Edit Product' : 'Create New Product' }}
        </h2>
        
        <form wire:submit="{{ $editingProductId ? 'updateProduct' : 'createProduct' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input 
                        type="text" 
                        wire:model="name" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                        placeholder="Product Name">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input 
                        type="number" 
                        wire:model="price" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                        placeholder="Price"
                        step="0.01">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                    <select 
                        wire:model="category" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Image</label>
                    <input 
                        type="file" 
                        wire:model="image" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea 
                    wire:model="description" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md"
                    placeholder="Description"
                    rows="3"></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="mt-6">
                @if($editingProductId)
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mr-2">Update Product</button>
                    <button type="button" wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                @else
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create Product</button>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Search (Using Livewire) -->
    <div class="mb-6">
        <input 
            type="text" 
            wire:model.live="search" 
            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-full bg-white"
            placeholder="Search products...">
    </div>

    <!-- Products Table (Using Livewire) -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                @php
                                    $productImagePath = '';
                                    if(file_exists(public_path($product->image))) {
                                        $productImagePath = asset($product->image);
                                    } else {
                                        $productImagePath = asset('storage/'.$product->image);
                                    }
                                @endphp
                                <img src="{{ $productImagePath }}" alt="{{ $product->productName }}" class="w-12 h-12 object-cover">
                            @else
                                <span class="text-gray-500">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->productName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button 
                                wire:click="editProduct({{ $product->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button 
                                wire:click="confirmProductDeletion({{ $product->id }})"
                                class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Delete Product Confirmation Modal -->
    @if($confirmingProductDeletion)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Product</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete product <strong>{{ $productToDelete ? $productToDelete->productName : '' }}</strong>? 
                        This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button 
                        wire:click="deleteProduct"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-32 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 mr-4">
                        Delete
                    </button>
                    <button 
                        wire:click="cancelProductDeletion"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-32 shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

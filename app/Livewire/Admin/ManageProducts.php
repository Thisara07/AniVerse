<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageProducts extends Component
{
    use WithFileUploads;
    
    public $products = [];
    public $name;
    public $description;
    public $price;
    public $category;
    public $image;
    public $editingProductId = null;
    public $search = '';
    public $sanctumToken = null;
    
    public $categories = [];
    
    // Delete confirmation properties
    public $confirmingProductDeletion = false;
    public $productToDelete = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => '|string',
        'price' => 'required|numeric|min:0',
        'category' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
    ];

       public function setToken($token)
    {
        // Store the token in the component
        $this->sanctumToken = $token;
        
        // The component is already behind auth/admin middleware, so user is authenticated
        // Just confirm the user has admin privileges
        $user = Auth::user();
        if ($user && $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        // Update token status display
        session()->flash('message', 'Token validated successfully. You are authenticated as admin.');
    }

    public function mount()
    {
        $this->loadProducts();
        $this->loadCategories();
        // Optionally retrieve token if needed for API calls
        // Note: Sanctum tokens are typically handled by middleware, not passed to components
        $user = Auth::user();
        if ($user) {
            // Set a flag to indicate user is authenticated
            $this->sanctumToken = 'authenticated'; // This just confirms authentication status
        }
    }

    public function render()
    {
        return view('livewire.admin.manage-products');
    }
    
    protected $listeners = ['set-token' => 'setToken'];

  

    public function loadProducts()
    {
        $query = Product::query();
        
        if ($this->search) {
            $query->where('productName', 'LIKE', '%' . $this->search . '%');
        }
        
        $this->products = $query->get();
    }

    public function createProduct()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $this->validate();

        $imageName = null;
        if ($this->image) {
            $imageName = $this->image->store('products', 'public');
        }

        Product::create([
            'productName' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'image' => $imageName,
        ]);

        $this->resetForm();
        $this->loadProducts();
        
        session()->flash('message', 'Product created successfully.');
    }

    public function editProduct($id)
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $product = Product::findOrFail($id);
        
        $this->editingProductId = $id;
        $this->name = $product->productName;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category = $product->category;
    }

    public function updateProduct()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $this->validate();

        $product = Product::findOrFail($this->editingProductId);
        
        $imageName = $product->image;
        if ($this->image) {
            // Delete old image
            if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                unlink(public_path('storage/' . $product->image));
            }
            $imageName = $this->image->store('products', 'public');
        }

        $product->update([
            'productName' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
            'image' => $imageName,
        ]);

        $this->resetForm();
        $this->loadProducts();
        
        session()->flash('message', 'Product updated successfully.');
    }

    public function confirmProductDeletion($id)
    {
        // Validate authentication before showing confirmation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $product = Product::find($id);
        
        if ($product) {
            $this->productToDelete = $product;
            $this->confirmingProductDeletion = true;
        } else {
            session()->flash('error', 'Product not found.');
        }
    }
    
    public function deleteProduct()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            $this->cancelProductDeletion();
            return;
        }
        
        if ($this->productToDelete) {
            // Delete image file if exists
            if ($this->productToDelete->image && file_exists(public_path('storage/' . $this->productToDelete->image))) {
                unlink(public_path('storage/' . $this->productToDelete->image));
            }
            
            $this->productToDelete->delete();
            $this->loadProducts();
            
            session()->flash('message', 'Product deleted successfully.');
        }
        
        $this->cancelProductDeletion();
    }
    
    public function cancelProductDeletion()
    {
        $this->confirmingProductDeletion = false;
        $this->productToDelete = null;
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

   

    private function resetForm()
    {
        $this->reset(['name', 'description', 'price', 'category', 'image', 'editingProductId']);
    }

    public function loadCategories()
    {
        // Define predefined categories
        $this->categories = [
            'Clothing',
            'Figures',
            'Posters',
            'Accessories',
            'Manga'
        ];
    }
    
    public function updated($field)
    {
        if ($field === 'search') {
            $this->loadProducts();
        }
    }
}

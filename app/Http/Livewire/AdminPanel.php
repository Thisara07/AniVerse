<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminPanel extends Component
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

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'category' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.admin-panel');
    }

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
        $product = Product::findOrFail($id);
        
        $this->editingProductId = $id;
        $this->name = $product->productName;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category = $product->category;
    }

    public function updateProduct()
    {
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

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image file if exists
        if ($product->image && file_exists(public_path('storage/' . $product->image))) {
            unlink(public_path('storage/' . $product->image));
        }
        
        $product->delete();
        $this->loadProducts();
        
        session()->flash('message', 'Product deleted successfully.');
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'description', 'price', 'category', 'image', 'editingProductId']);
    }

    public function updated($field)
    {
        if ($field === 'search') {
            $this->loadProducts();
        }
    }
}
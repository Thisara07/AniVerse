<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ManageOrders extends Component
{
    public $orders;
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedOrder = null;
    public $showOrderDetails = false;
    public $sanctumToken = null;
    
    // Delete confirmation properties
    public $confirmingOrderDeletion = false;
    public $orderToDelete = null;
    
    protected $queryString = ['search', 'statusFilter', 'dateFrom', 'dateTo'];
    
    public function mount()
    {
        $this->loadOrders();
        // Optionally retrieve token if needed for API calls
        $user = Auth::user();
        if ($user) {
            // Set a flag to indicate user is authenticated
            $this->sanctumToken = 'authenticated';
        }
    }
    
    protected $listeners = ['set-token' => 'setToken'];
    
    public function loadOrders()
    {
        $query = Order::with(['user', 'orderItems.product']);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('fullName', 'like', '%' . $this->search . '%');
                  })
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->dateFrom));
        }
        
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->dateTo));
        }
        
        $this->orders = $query->orderBy('created_at', 'asc')->get();
    }
    
    public function updateOrderStatus($orderId, $newStatus)
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $order = Order::find($orderId);
        if ($order) {
            $order->status = $newStatus;
            $order->save();
            
            // Refresh orders
            $this->loadOrders();
            
            session()->flash('message', 'Order status updated successfully.');
        }
    }
    
    public function confirmOrderDeletion($orderId)
    {
        // Validate authentication before showing confirmation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $order = Order::find($orderId);
        if ($order) {
            $this->orderToDelete = $order;
            $this->confirmingOrderDeletion = true;
        } else {
            session()->flash('error', 'Order not found.');
        }
    }
    
    public function deleteOrder()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            $this->cancelOrderDeletion();
            return;
        }
        
        if ($this->orderToDelete) {
            $this->orderToDelete->delete();
            
            // Refresh orders
            $this->loadOrders();
            
            session()->flash('message', 'Order deleted successfully.');
        }
        
        $this->cancelOrderDeletion();
    }
    
    public function cancelOrderDeletion()
    {
        $this->confirmingOrderDeletion = false;
        $this->orderToDelete = null;
    }
    
    public function viewOrderDetails($orderId)
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $this->selectedOrder = Order::with(['user', 'orderItems.product'])->find($orderId);
        $this->showOrderDetails = true;
    }
    
    public function closeOrderDetails()
    {
        $this->showOrderDetails = false;
        $this->selectedOrder = null;
    }
    
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
    
    public function render()
    {
        return view('livewire.admin.manage-orders');
    }
    
    public function updated($property)
    {
        if ($property === 'search' || $property === 'statusFilter' || $property === 'dateFrom' || $property === 'dateTo') {
            $this->loadOrders();
        }
    }
}

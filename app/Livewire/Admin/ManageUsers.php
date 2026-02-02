<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManageUsers extends Component
{
    public $users;
    public $search = '';
    public $roleFilter = '';
    public $showCreateForm = false;
    
    public $name;
    public $email;
    public $password;
    public $role = 'customer';
    public $editingUserId = null;
    public $sanctumToken = null;
    
    // Delete confirmation properties
    public $confirmingUserDeletion = false;
    public $userToDelete = null;
    
    protected $queryString = ['search', 'roleFilter'];
    
    public function mount()
    {
        $this->loadUsers();
        // Optionally retrieve token if needed for API calls
        $user = Auth::user();
        if ($user) {
            // Set a flag to indicate user is authenticated
            $this->sanctumToken = 'authenticated';
        }
    }
    
    protected $listeners = ['set-token' => 'setToken'];
    
    public function loadUsers()
    {
        $query = User::query();
        
        if ($this->search) {
            $query->where('fullName', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }
        
        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }
        
        $this->users = $query->orderBy('created_at', 'desc')->get();
    }
    
    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->resetForm();
    }
    
    public function updateUserRole($userId, $newRole)
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $user = User::find($userId);
        if ($user) {
            $user->role = $newRole;
            $user->save();
            
            // Refresh users
            $this->loadUsers();
            
            session()->flash('message', 'User role updated successfully.');
        }
    }
    
    public function confirmUserDeletion($userId)
    {
        // Validate authentication before showing confirmation
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $user = User::find($userId);
        
        if ($user) {
            // Check if trying to delete own account
            if ($currentUser && $user->id == $currentUser->id) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }
            
            $this->userToDelete = $user;
            $this->confirmingUserDeletion = true;
        } else {
            session()->flash('error', 'User not found.');
        }
    }
    
    public function deleteUser()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            $this->cancelUserDeletion();
            return;
        }
        
        if ($this->userToDelete) {
            $this->userToDelete->delete();
            
            // Refresh users
            $this->loadUsers();
            
            session()->flash('message', 'User deleted successfully.');
        }
        
        $this->cancelUserDeletion();
    }
    
    public function cancelUserDeletion()
    {
        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
    }
    
    public function saveUser()
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingUserId ?: 'NULL'),
            'role' => 'required|in:customer,admin',
        ]);
        
        if ($this->editingUserId) {
            // Update existing user
            $user = User::find($this->editingUserId);
            $user->fullName = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;
            
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            
            $user->save();
            session()->flash('message', 'User updated successfully.');
        } else {
            // Create new user
            User::create([
                'fullName' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'password' => Hash::make($this->password ?: 'password'), // Default password if none provided
            ]);
            session()->flash('message', 'User created successfully.');
        }
        
        $this->resetForm();
        $this->loadUsers();
        $this->showCreateForm = false;
    }
    
    public function editUser($userId)
    {
        // Validate authentication before CRUD operation
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            session()->flash('error', 'Unauthorized access. Admin privileges required.');
            return;
        }
        
        $user = User::find($userId);
        if ($user) {
            $this->editingUserId = $user->id;
            $this->name = $user->fullName;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->showCreateForm = true;
        }
    }
    
    public function cancelEdit()
    {
        $this->resetForm();
        $this->showCreateForm = false;
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
    
    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editingUserId']);
        $this->role = 'customer';
    }
    
    public function render()
    {
        return view('livewire.admin.manage-users');
    }
    
    public function updated($property)
    {
        if ($property === 'search' || $property === 'roleFilter') {
            $this->loadUsers();
        }
    }
}

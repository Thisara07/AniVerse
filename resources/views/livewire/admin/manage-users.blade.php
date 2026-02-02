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
    
    <!-- Create User Form -->
    @if($showCreateForm)
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">
            {{ $editingUserId ? 'Edit User' : 'Create New User' }}
        </h2>
        
        <form wire:submit="saveUser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input 
                        type="text" 
                        wire:model="name" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                        placeholder="Full Name">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input 
                        type="email" 
                        wire:model="email" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                        placeholder="Email">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md"
                        placeholder="Password (leave blank to keep current)">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select wire:model="role" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mr-2">{{ $editingUserId ? 'Update User' : 'Create User' }}</button>
                <button type="button" wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
            </div>
        </form>
    </div>
    @endif
    
    <!-- Controls -->
    <div class="flex flex-col md:flex-row justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <button 
                wire:click="toggleCreateForm"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ $showCreateForm ? 'Cancel' : 'Create New User' }}
            </button>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <input 
                type="text" 
                wire:model.live="search" 
                class="px-4 py-2 border border-gray-300 rounded-full bg-white w-full md:w-64"
                placeholder="Search users...">
            
            <select wire:model.live="roleFilter" class="px-4 py-2 border border-gray-300 rounded-md bg-white">
                <option value="">All Roles</option>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->fullName }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                              {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <div class="relative group">
                                <select wire:change="updateUserRole({{ $user->id }}, $event.target.value)" 
                                        class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-400 px-2 py-1 pr-8 pl-3 rounded shadow leading-tight focus:outline-none focus:shadow-outline text-sm">
                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                            <button 
                                wire:click="editUser({{ $user->id }})"
                                class="text-blue-600 hover:text-blue-900 text-sm">Edit</button>
                            <button 
                                wire:click="confirmUserDeletion({{ $user->id }})"
                                class="text-red-600 hover:text-red-900 text-sm">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Delete User Confirmation Modal -->
    @if($confirmingUserDeletion)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete User</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete user <strong>{{ $userToDelete ? $userToDelete->fullName : '' }}</strong>? 
                        This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button 
                        wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-32 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 mr-4">
                        Delete
                    </button>
                    <button 
                        wire:click="cancelUserDeletion"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-32 shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">User Login</h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="user_type" value="user">
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Login as User</button>
            </div>
        </form>
        
        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-red-600 hover:text-red-800">Login as Admin</a>
        </div>
    </div>
</div>
@endsection
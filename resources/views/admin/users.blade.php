@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <header class="mb-8 text-center">
        <a href="{{ route('home') }}" class="block"><img src="{{ asset('images/AniVerse-icon.png') }}" alt="AniVerse" class="h-20 w-auto"/></a>
        <h1 class="text-3xl font-bold text-black-700">AniVerse – Manage Users</h1>
    </header>

    <div class="max-w-5xl mx-auto space-y-8">

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @if($users->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @else
                    @foreach($users as $user)
                        <tr class="even:bg-gray-50 hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $user->id }}</td>
                            <td class="px-4 py-3">{{ $user->fullName }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <form method="post" action="{{ route('admin.users.update-role', $user->id) }}" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                        <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button
                                        type="submit"
                                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition"
                                    >Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-3">{{ $user->phoneNo }}</td>
                            <td class="px-4 py-3">
                                @if($user->id !== auth()->user()->id)
                                    <form method="post" action="{{ route('admin.users.destroy', $user->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                        >Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
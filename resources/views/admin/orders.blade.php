@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-black mb-6">AniVerse – Manage Orders</h1>
        <a href="{{ route('home') }}" class="block"><img src="{{ asset('images/AniVerse-icon.png') }}" alt="AniVerse" class="h-20 w-auto"/></a>

        <!-- Orders Section -->
        <div class="max-w-5xl mx-auto bg-white/90 backdrop-blur-lg rounded-lg shadow-lg overflow-auto mb-12">
            <div class="px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">All Orders</h1>
                <table class="w-full table-auto border-collapse text-sm">
                    <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Order ID</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Address</th>
                        <th class="px-4 py-2 text-left">Items</th>
                        <th class="px-4 py-2 text-left">Total ($)</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            @php
                                $itemNames = [];
                                foreach($order->orderItems as $item) {
                                    $itemNames[] = $item->product->productName . ' x' . $item->quantity;
                                }
                            @endphp
                            <tr class="even:bg-gray-100 hover:bg-gray-200">
                                <td class="px-4 py-2 border">{{ $order->id }}</td>
                                <td class="px-4 py-2 border">{{ $order->user->fullName }}</td>
                                <td class="px-4 py-2 border">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2 border">{{ $order->address }}</td>
                                <td class="px-4 py-2 border">{{ implode(', ', $itemNames) }}</td>
                                <td class="px-4 py-2 border">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 rounded-full text-sm 
                                        @if(strtolower($order->status) == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif(strtolower($order->status) == 'shipped') bg-blue-100 text-blue-800
                                        @elseif(strtolower($order->status) == 'completed') bg-green-100 text-green-800
                                        @elseif(strtolower($order->status) == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border">
                                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="px-2 py-1 border rounded text-sm focus:ring-purple-500">
                                            @foreach(['pending','shipped','completed','cancelled'] as $status)
                                                <option value="{{ $status }}" {{ ($order->status === $status ? 'selected' : '') }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                                class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments Section -->
        <div class="max-w-5xl mx-auto bg-white/90 backdrop-blur-lg rounded-lg shadow-lg overflow-auto">
            <div class="px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">All Payments</h1>
                <table class="w-full table-auto border-collapse text-sm">
                    <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Payment ID</th>
                        <th class="px-4 py-2 text-left">Order ID</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Amount ($)</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($payments->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No payments recorded.</td>
                        </tr>
                    @else
                        @foreach($payments as $payment)
                            <tr class="even:bg-gray-100 hover:bg-gray-200">
                                <td class="px-4 py-2 border">{{ $payment->id }}</td>
                                <td class="px-4 py-2 border">{{ $payment->order_id }}</td>
                                <td class="px-4 py-2 border">{{ $payment->user->fullName }}</td>
                                <td class="px-4 py-2 border">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-2 border">{{ $payment->payment_type }}</td>
                                <td class="px-4 py-2 border">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
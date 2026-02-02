@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Order Details</h1>
        
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Order Information</h2>
                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($order->status == 'pending') bg-yellow-200 text-yellow-800
                            @elseif($order->status == 'completed') bg-green-200 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-200 text-red-800
                            @else bg-gray-200 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Customer Information</h2>
                    <p><strong>Name:</strong> {{ $order->fullName }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Phone:</strong> {{ $order->phoneNo }}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Shipping Address</h2>
                <p>{{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zipCode }}</p>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Order Items</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Product</th>
                                <th class="py-3 px-6 text-left">Price</th>
                                <th class="py-3 px-6 text-left">Quantity</th>
                                <th class="py-3 px-6 text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($order->orderItems as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $item->product->productName }}</td>
                                    <td class="py-3 px-6 text-left">${{ number_format($item->price, 2) }}</td>
                                    <td class="py-3 px-6 text-left">{{ $item->quantity }}</td>
                                    <td class="py-3 px-6 text-left">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="flex justify-end">
                <div class="bg-gray-100 rounded-lg p-4 w-64">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->total_amount - $order->shipping_fee - $order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <a href="{{ route('profile') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Back to Profile
        </a>
    </div>
</div>
@endsection
<?php
// Simple API test script with proper Laravel initialization

// Bootstrap Laravel application
require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

// Set base URL
$baseUrl = 'http://127.0.0.1:8000'; // Using 127.0.0.1 instead of localhost

echo "Testing Laravel API endpoints...\n\n";

// Test 1: Get public products
echo "1. Testing public products endpoint...\n";
try {
    $response = Http::get($baseUrl . '/api/products/public');  // Correct API endpoint
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . substr($response->body(), 0, 200) . "...\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Register a new user
echo "2. Testing user registration...\n";
try {
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post($baseUrl . '/api/register', [  // Correct API endpoint
        'fullName' => 'API Test User',
        'email' => 'apitest' . time() . '@example.com', // Using timestamp to ensure unique email
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phoneNo' => '1234567890'
    ]);
    echo "Status: " . $response->status() . "\n";
    if ($response->successful()) {
        echo "Success: User registered\n";
        $data = $response->json();
        $token = $data['token'] ?? null;
        echo "Token received: " . ($token ? 'Yes' : 'No') . "\n";
    } else {
        echo "Error Response: " . $response->body() . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Login
echo "3. Testing user login...\n";
try {
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post($baseUrl . '/api/login', [  // Correct API endpoint
        'email' => 'admin@example.com',
        'password' => 'password'
    ]);
    echo "Status: " . $response->status() . "\n";
    if ($response->successful()) {
        echo "Success: Login successful\n";
        $data = $response->json();
        $token = $data['token'] ?? null;
        echo "Token received: " . ($token ? 'Yes' : 'No') . "\n";
    } else {
        echo "Error Response: " . $response->body() . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "API testing completed.\n";
?>
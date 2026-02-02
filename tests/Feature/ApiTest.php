<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
// use Illuminate\Foundation\Testing\RefreshDatabase; // Disabled to use seeded data
use Illuminate\Support\Facades\Hash;

class ApiTest extends TestCase
{
    // use RefreshDatabase; // Disabled to use seeded data

    public function test_api_registration()
    {
        $email = 'apitest' . time() . rand(1000, 9999) . '@example.com';
        $response = $this->postJson('/api/register', [
            'fullName' => 'API Test User',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phoneNo' => '1234567890'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user',
                     'token',
                     'token_abilities',
                     'created_at'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'fullName' => 'API Test User'
        ]);
    }

    public function test_api_login()
    {
        // First create a user
        $email = 'logintest' . time() . rand(1000, 9999) . '@example.com';
        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'user',
                     'token'
                 ]);
    }

    public function test_public_products_endpoint()
    {
        // Create some test products
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products/public');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'description',
                             'price',
                             'category',
                             'image',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);
    }

    public function test_protected_user_endpoints()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $user->id,
                     'email' => $user->email,
                     'fullName' => $user->fullName
                 ]);
    }

    public function test_product_api_endpoints()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('test-token')->plainTextToken;

        // Test creating a product
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/products', [
                             'name' => 'Test Product ' . time(),
                             'description' => 'Test Description ' . time(),
                             'price' => 10.99,
                             'category' => 'test',
                             'image' => 'test.jpg'
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'category',
                         'image'
                     ]
                 ]);

        // Get the created product ID
        $productId = $response->json('data.id');

        // Test getting the product
        $getResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                            ->getJson("/api/products/{$productId}");

        $getResponse->assertStatus(200);

        // Test updating the product
        $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->putJson("/api/products/{$productId}", [
                                   'name' => 'Updated Product',
                                   'price' => 15.99
                               ]);

        $updateResponse->assertStatus(200);

        // Test deleting the product
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->deleteJson("/api/products/{$productId}");

        $deleteResponse->assertStatus(200);
    }
}
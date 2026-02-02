<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TokenController;

// Public API routes (no authentication required)
Route::get('/products/public', [\App\Http\Controllers\Api\ProductController::class, '__invoke']);

// Enhanced Authentication routes with advanced Sanctum features
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'registerAPI']);

// Advanced Sanctum Token Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Token management with scopes and expiration
    Route::post('/tokens/create', [TokenController::class, 'createToken']);
    Route::get('/tokens', [TokenController::class, 'listTokens']);
    Route::delete('/tokens/{tokenId}', [TokenController::class, 'revokeToken']);
    Route::post('/tokens/{tokenId}/abilities', [TokenController::class, 'updateTokenAbilities']);
    
    // Multi-device session management
    Route::get('/sessions', [TokenController::class, 'listSessions']);
    Route::delete('/sessions/{tokenId}', [TokenController::class, 'revokeSession']);
    Route::delete('/sessions/all', [TokenController::class, 'revokeAllSessions']);
    
    // User profile and security
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    
    // Protected API resources with role-based access
    Route::apiResource('/users', UserApiController::class)->except(['store']);
    Route::post('/users', [UserApiController::class, 'store'])->middleware('auth:sanctum');
    
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::post('/products', [ProductApiController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/products/{product}', [ProductApiController::class, 'show']);
    Route::put('/products/{product}', [ProductApiController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/products/{product}', [ProductApiController::class, 'destroy'])->middleware('auth:sanctum');
    
    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [AuthController::class, 'adminStats']);
        Route::get('/admin/users', [UserApiController::class, 'adminIndex']);
    });
});

// Logout route (can be called with or without token for flexibility)
Route::post('/logout', [AuthController::class, 'logout']);
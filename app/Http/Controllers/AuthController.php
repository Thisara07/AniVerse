<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            
            // For web requests, regenerate session
            if (!$request->expectsJson() && !$request->is('api/*')) {
                $request->session()->regenerate();
            }
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                $user = Auth::user();
                /** @var \App\Models\User $user */
                $token = $user->createToken('API Token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ]);
            }
            
            // Check user type for separate dashboard redirects
            $user = Auth::user();
            $userType = $request->input('user_type', 'default');
            
            if ($userType === 'admin' && $user->role === 'admin') {
                return redirect()->intended('/admin/products')->with('success', 'Welcome back, Admin!');
            } elseif ($userType === 'user' || $user->role === 'user' || $user->role === 'customer') {
                return redirect()->intended('/')->with('success', 'Welcome back!');
            }
            
            return redirect()->intended('/')->with('success', 'Welcome back!');
        }
        
        // Return appropriate response for API or web
        if ($request->expectsJson()) {
            return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phoneNo' => ['required', 'string', 'max:15'],
        ]);
        
        if ($validator->fails()) {
            return redirect('register')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phoneNo' => $request->phoneNo,
        ]);
        
        Auth::login($user);
        
        return redirect('/')->with('success', 'Account created successfully!');
    }
    
    public function logout(Request $request)
    {
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($request->user()) {
                // Enhanced logout with token metadata
                $token = $request->user()->currentAccessToken();
                if ($token) {
                    $tokenName = $token->name;
                    $token->delete();
                    return response()->json([
                        'message' => 'Logged out successfully',
                        'token_name' => $tokenName,
                        'logged_out_at' => now()->toISOString()
                    ]);
                }
            }
            return response()->json(['message' => 'No active token found'], 400);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out.');
    }

    /**
     * API Registration endpoint with enhanced validation
     * Demonstrates secure user registration
     */
    public function registerAPI(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phoneNo' => ['required', 'string', 'max:15'],
            'role' => ['sometimes', 'string', 'in:customer,admin'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'customer',
            'phoneNo' => $request->phoneNo,
        ]);

        // Auto-generate API token for new users
        $token = $user->createToken('Registration Token', ['users:view', 'products:view']);

        return response()->json([
            'message' => 'Account created successfully',
            'user' => $user,
            'token' => $token->plainTextToken,
            'token_abilities' => $token->accessToken->abilities,
            'created_at' => now()->toISOString()
        ], 201);
    }

    /**
     * Update user profile via API
     * Demonstrates secure profile management
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'fullName' => ['sometimes', 'string', 'max:255'],
            'phoneNo' => ['sometimes', 'string', 'max:15'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['fullName', 'phoneNo']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }

    /**
     * Update user password via API
     * Demonstrates secure password management
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => ['The provided password does not match our records.']]], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Revoke all tokens for security
        $user->tokens()->delete();
        
        // Generate new token
        $newToken = $user->createToken('Password Update Token', ['users:view', 'products:view']);

        return response()->json([
            'message' => 'Password updated successfully. All previous sessions have been logged out for security.',
            'new_token' => $newToken->plainTextToken
        ]);
    }

    /**
     * Admin statistics endpoint
     * Demonstrates role-based access control
     */
    public function adminStats(Request $request)
    {
        $user = $request->user();
        
        // Additional role check
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'active_sessions' => \Laravel\Sanctum\PersonalAccessToken::count(),
            'admin_users' => \App\Models\User::where('role', 'admin')->count(),
            'customer_users' => \App\Models\User::where('role', 'customer')->count(),
            'generated_at' => now()->toISOString()
        ];

        return response()->json($stats);
    }
}

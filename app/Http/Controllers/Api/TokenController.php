<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class TokenController extends Controller
{
    /**
     * Create a new personal access token with specific abilities and expiration
     * Demonstrates token scopes and expiration features
     */
    public function createToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'abilities' => 'array',
            'abilities.*' => 'string|in:users:view,users:create,users:update,users:delete,products:view,products:create,products:update,products:delete',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $abilities = $request->abilities ?? ['users:view', 'products:view'];
        
        // Create token with expiration if provided
        $token = $user->createToken($request->name, $abilities);
        
        if ($request->expires_at) {
            // Note: Laravel Sanctum doesn't natively support token expiration
            // This would require custom implementation or using a different package
            // For demonstration, we'll store expiration info in metadata
            $token->accessToken->expires_at = Carbon::parse($request->expires_at);
            $token->accessToken->save();
        }

        return response()->json([
            'message' => 'Token created successfully',
            'token' => $token->plainTextToken,
            'token_name' => $request->name,
            'abilities' => $abilities,
            'expires_at' => $request->expires_at,
            'token_id' => $token->accessToken->id
        ], 201);
    }

    /**
     * List all personal access tokens for the authenticated user
     * Demonstrates token management capabilities
     */
    public function listTokens(Request $request)
    {
        $user = $request->user();
        
        $tokens = $user->tokens()->select([
            'id',
            'name',
            'abilities',
            'last_used_at',
            'created_at',
            'updated_at'
        ])->get();

        return response()->json([
            'tokens' => $tokens,
            'total_tokens' => $tokens->count()
        ]);
    }

    /**
     * Revoke/delete a specific personal access token
     * Demonstrates token revocation security feature
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $user = $request->user();
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return response()->json(['message' => 'Token not found'], 404);
        }

        $token->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

    /**
     * Update token abilities/scopes
     * Demonstrates dynamic token scope modification
     */
    public function updateTokenAbilities(Request $request, $tokenId)
    {
        $validator = Validator::make($request->all(), [
            'abilities' => 'required|array',
            'abilities.*' => 'string|in:users:view,users:create,users:update,users:delete,products:view,products:create,products:update,products:delete',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return response()->json(['message' => 'Token not found'], 404);
        }

        // Note: Laravel Sanctum doesn't allow updating abilities after creation
        // This would require custom implementation
        // For demonstration, we'll show the limitation
        return response()->json([
            'message' => 'Token abilities cannot be updated after creation in Sanctum',
            'current_abilities' => $token->abilities,
            'suggestion' => 'Create a new token with desired abilities instead'
        ], 400);
    }

    /**
     * List all active sessions (tokens) for multi-device management
     * Demonstrates multi-device session tracking
     */
    public function listSessions(Request $request)
    {
        $user = $request->user();
        
        $sessions = $user->tokens()->select([
            'id',
            'name',
            'abilities',
            'last_used_at',
            'created_at',
            'ip_address', // Would need to be stored during token creation
            'user_agent'  // Would need to be stored during token creation
        ])->get();

        return response()->json([
            'sessions' => $sessions,
            'active_sessions' => $sessions->count(),
            'current_session_id' => $request->user()->currentAccessToken()?->id
        ]);
    }

    /**
     * Revoke a specific session
     * Demonstrates targeted session management
     */
    public function revokeSession(Request $request, $tokenId)
    {
        return $this->revokeToken($request, $tokenId);
    }

    /**
     * Revoke all sessions except current one
     * Demonstrates bulk session management for security
     */
    public function revokeAllSessions(Request $request)
    {
        $user = $request->user();
        $currentTokenId = $request->user()->currentAccessToken()?->id;
        
        $revokedCount = $user->tokens()
            ->where('id', '!=', $currentTokenId)
            ->delete();

        return response()->json([
            'message' => 'All other sessions revoked successfully',
            'sessions_revoked' => $revokedCount,
            'current_session_active' => true
        ]);
    }
}

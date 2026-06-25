<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function issueApiToken(AuthService $authService) : JsonResponse
    {
        $token = $authService->generateToken();

        return response()->json(['success' => true, 'message' => 'Token issued successfully', 'token' => $token]);
    }

    public function revokeToken(AuthService $authService) :JsonResponse
    {
        $header = request()->header('Authorization');
        $token = str_replace('Bearer ', '', $header);

        if ($authService->revokeToken($token)) {
            return response()->json(['success' => true, 'message' => 'Token revoked successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Token not found or already revoked'], 404);
    }
}

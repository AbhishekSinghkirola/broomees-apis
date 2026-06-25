<?php

namespace App\Services;

use App\Models\ApiToken;
use Illuminate\Support\Str;

class AuthService
{

    public function generateToken(): string
    {
        $token =  Str::random(60);
        $hashedToken = hash('sha256', $token);

        $existToken = ApiToken::where('token_hash', $hashedToken)->first();

        if ($existToken) {
            return $this->generateToken();
        }

        ApiToken::create([
            'token_hash' => $hashedToken,
            'expires_at' => now()->addDays(7)
        ]);

        return $token;
    }

    public function revokeToken(string $token): bool
    {
        $hashedToken = hash('sha256', $token);
        $apiToken = ApiToken::where('token_hash', $hashedToken)->first();

        if ($apiToken) {
            $apiToken->is_revoked = true;
            $apiToken->save();
            return true;
        }

        return false;
    }
}

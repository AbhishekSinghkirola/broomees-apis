<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class OptimisticLockingTest extends TestCase
{
    use RefreshDatabase;


    public function test_update_fails_when_version_mismatch()
    {
        $user = User::factory()->create([
            'version' => 2,
        ]);

       $plainToken = Str::random(60);

        ApiToken::create([
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addDay(),
            'revoked' => false,
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$plainToken}",
        ])->putJson("/api/users/{$user->id}", [
            'username' => 'Updated User',
            'age' => 25,
            'version' => 1,
        ]);

        $response->assertStatus(409);
    }
}

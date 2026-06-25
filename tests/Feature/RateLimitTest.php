<?php

namespace Tests\Feature;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limit_is_enforced()
    {
        $user = User::factory()->create();

        $plainToken = Str::random(60);

        ApiToken::create([
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => now()->addDay(),
            'revoked' => false,
        ]);

        for ($i = 0; $i < 30; $i++) {

            $this->withHeaders([
                'Authorization' => "Bearer {$plainToken}",
            ])->postJson("/api/users", [
                'username' => fake()->unique()->username(),
                'age' => fake()->numberBetween(18, 50)
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$plainToken}",
        ])->postJson("/api/users", [
            'username' => fake()->unique()->username(),
            'age' => fake()->numberBetween(18, 50)
        ]);

        $response->assertStatus(429);
    }
}

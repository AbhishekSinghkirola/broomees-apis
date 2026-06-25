<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ReputationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReputationCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reputation_calculation_returns_numeric_score()
    {
        $user = User::factory()->create();

        $score = app(ReputationService::class)
            ->recalculate($user);

        $this->assertIsNumeric($score);
    }
}

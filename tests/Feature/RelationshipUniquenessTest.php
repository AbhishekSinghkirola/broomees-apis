<?php

namespace Tests\Feature;

use App\Models\Relationship;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipUniquenessTest extends TestCase
{

    use RefreshDatabase;

    public function test_duplicate_relationship_is_not_allowed()
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        Relationship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
        ]);

        $this->expectException(QueryException::class);

        Relationship::create([
            'user_id' => $user->id,
            'friend_id' => $friend->id,
        ]);
    }
}

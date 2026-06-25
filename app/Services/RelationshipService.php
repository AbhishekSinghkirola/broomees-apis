<?php

namespace App\Services;

use App\Models\Relationship;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RelationshipService
{

    public function __construct(
        private ReputationService $reputationService
    ) {}

    public function create(User $user, string $friend_id): void
    {
        $friend = User::findorFail($friend_id);

        if ($user->id === $friend->id) {
            throw new ConflictHttpException('User cannot be friends with themselves');
        }

        $existingRelationship = Relationship::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->first();

        if ($existingRelationship) {
            throw new ConflictHttpException('Relationship already exists');
        }

        DB::transaction(function () use ($user, $friend) {

            Relationship::create([
                'user_id' => $user->id,
                'friend_id' => $friend->id,
            ]);

            Relationship::create([
                'user_id' => $friend->id,
                'friend_id' => $user->id,
            ]);

            $this->reputationService->recalculate($user);
            $this->reputationService->recalculate($friend);
        });
    }

    public function delete(User $user, string $friend_id): void
    {
        $friend = User::findorFail($friend_id);

        $existingRelationship = Relationship::where('user_id', $user->id)
            ->where('friend_id', $friend->id)
            ->first();

        if (!$existingRelationship) {
            throw new ConflictHttpException('Relationship does not exist');
        }

        DB::transaction(function () use ($user, $friend) {
            Relationship::where('user_id', $user->id)
                ->where('friend_id', $friend->id)
                ->delete();

            Relationship::where('user_id', $friend->id)
                ->where('friend_id', $user->id)
                ->delete();

            $this->reputationService->recalculate($user);
            $this->reputationService->recalculate($friend);
        });
    }
}

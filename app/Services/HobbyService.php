<?php

namespace App\Services;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HobbyService
{
    public function __construct(
        private ReputationService $reputationService
    ) {}

    public function getHobbies(): Collection {
        return Hobby::all();
    }

    public function create(string $hobbyId, User $user): void
    {
        Hobby::findorFail($hobbyId);

        if ($user->hobbies()->where('hobby_id', $hobbyId)->exists()) {
            throw new ConflictHttpException('Hobby already assigned to the user.');
        }

        DB::transaction(function () use ($user, $hobbyId) {

            $user->hobbies()->attach($hobbyId);

            $this->reputationService->recalculate($user);
        });
    }

    public function delete(string $hobbyId, User $user): void
    {
        Hobby::findorFail($hobbyId);

        if (! $user->hobbies()->where('hobby_id', $hobbyId)->exists()) {
            throw new NotFoundHttpException('Hobby is not assigned to the user.');
        }

        DB::transaction(function () use ($user, $hobbyId) {

            $user->hobbies()->detach($hobbyId);

            $this->reputationService->recalculate($user);
        });
    }
}

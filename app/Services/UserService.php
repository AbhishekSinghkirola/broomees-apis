<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserService
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function getAllUsers(int $limit = 10)
    {
        return User::paginate($limit);
    }

    public function updateUser(User $user, array $data): User
    {
        $updated = User::where('id', $user->id)
            ->where('version', $data['version'])
            ->update([
                'username' => $data['username'],
                'age' => $data['age'],
                'version' => DB::raw('version + 1'),
                'updated_at' => now(),
            ]);

        if (!$updated) {
            throw new ConflictHttpException(
                'Version mismatch'
            );
        }

        return User::findOrFail($user->id);
    }

    public function deleteUser(User $user): void
    {
        $userHasRelationships = $user->friends()->exists();

        if($userHasRelationships) {
            throw new ConflictHttpException("User have active relationships.");
        }

        $reputationDeleteThreshold = config('reputaion.reputation_delete_threshold', 10);

        if ($user->reputation_score > $reputationDeleteThreshold) {
            throw new ConflictHttpException("User cannot be deleted due to high reputation score.");
        }

        $user->delete();
    }
}

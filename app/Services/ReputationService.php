<?php

namespace App\Services;

use App\Models\User;

class ReputationService
{
    public function recalculate(User $user)
    {
        $uniqueFriendsCount = $this->getFriendCount($user);

        $sharedHobbies = $this->getSharedHobbyCount($user);

        $accountAgeScore = $this->getAccountAgeScore($user);

        $blockedPenalty = 0; // Not mention in documentation

        $score =
            $uniqueFriendsCount +
            ($sharedHobbies * 0.5) +
            $accountAgeScore -
            $blockedPenalty;

        $user->update([
            'reputation_score' => round($score, 2)
        ]);

        return $score;
    }

    public function getFriendCount(User $user)
    {
        return $user->friends()->count();
    }

    public function getSharedHobbyCount(User $user)
    {
        $hobbies = $user->hobbies()->pluck('id')->toArray();

        return User::whereHas('hobbies', function ($query) use ($hobbies) {
            $query->whereIn('hobby_id', $hobbies);
        })->count();
    }

    public function getAccountAgeScore(User $user)
    {
        $accountAgeInDays = now()->diffInDays($user->created_at);

        return min($accountAgeInDays / 30,  3);
    }

    public function getMetrics():array {

        $totalUsers = User::count();
        $averageReputationScore = User::avg('reputation_score');
        $highestReputaionScore = User::max('reputation_score'); 

        return [
            'totalUsers' => $totalUsers,
            'averageReputationScore' => $averageReputationScore,
            'highestReputaionScore' => $highestReputaionScore,
        ];
    }
}

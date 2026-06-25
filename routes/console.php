<?php

use App\Models\User;
use App\Services\ReputationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $reputationService = app(ReputationService::class);

    User::chunk(100, function ($users) use ($reputationService) {
        foreach ($users as $user) {
            $reputationService->recalculate($user);
        }
    });
})->daily();

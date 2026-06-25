<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HobbyController;
use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\RelationshipController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/token', [AuthController::class, 'issueApiToken']);

Route::middleware([
    'token.verify',
    'throttle:read'
])->group(function () {

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/{user}', [UserController::class, 'show']);

    Route::get("/hobbies", [HobbyController::class, 'index']);

    Route::get('/metrics/reputation', [MetricsController::class, 'index']);
});

Route::middleware([
    'token.verify',
    'throttle:write'
])->group(function () {

    Route::post('/auth/revoke', [AuthController::class, 'revokeToken']);

    Route::post('/users', [UserController::class, 'store']);

    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::post('/users/{user}/relationships', [RelationshipController::class, 'store']);

    Route::delete('/users/{user}/relationships', [RelationshipController::class, 'destroy']);

    Route::post('/users/{user}/hobbies', [HobbyController::class, 'store']);

    Route::delete('/users/{user}/hobbies', [HobbyController::class, 'destroy']);
});

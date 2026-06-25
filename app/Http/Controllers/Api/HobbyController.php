<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HobbyRequest;
use App\Models\User;
use App\Services\HobbyService;
use Illuminate\Http\JsonResponse;

class HobbyController extends Controller
{
    public function index(HobbyService $hobbyService): JsonResponse
    {
        $hobbies = $hobbyService->getHobbies();
        return response()->json(['success' => true, "message" => "Hobbies retrieved successfully", 'data' => $hobbies]);
    }

    public function store(User $user, HobbyService $hobbyService, HobbyRequest $request): JsonResponse
    {
        $hobbyService->create($request->hobby_id, $user);
        return response()->json(['success' => true, 'message' => "Hobby assigned successfully"]);
    }

    public function destroy(User $user, HobbyService $hobbyService, HobbyRequest $request): JsonResponse
    {
        $hobbyService->delete($request->hobby_id, $user);
        return response()->json(['success' => true, 'message' => "Hobby deleted successfully"]);
    }
}

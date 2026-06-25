<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function index(UserService $userService): JsonResponse
    {
        $limit = request()->query('limit', 10);

        $users = $userService->getAllUsers($limit);

        return response()->json(['success' => true, 'message' => 'Users retrieved successfully', 'data' => $users]);
    }

    public function store(CreateUserRequest $request, UserService $userService): JsonResponse
    {
        $user = $userService->createUser($request->validated());

        return response()->json(['success' => true, 'message' => 'User created successfully', 'data' => $user], 201);
    }


    public function show(User $user): JsonResponse
    {
        return response()->json(['success' => true, 'message' => 'User retrieved successfully', 'data' => $user]);
    }


    public function update(UpdateUserRequest $request, User $user, UserService $userService): JsonResponse
    {
        $updatedUser = $userService->updateUser($user, $request->validated());

        return response()->json(['success' => true, 'message' => 'User updated successfully', 'data' => $updatedUser]);
    }


    public function destroy(User $user, UserService $userService): JsonResponse
    {
        $userService->deleteUser($user);

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}

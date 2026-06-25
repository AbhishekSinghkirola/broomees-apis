<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelationshipRequest;
use App\Models\User;
use App\Services\RelationshipService;

class RelationshipController extends Controller
{
    public function store(RelationshipRequest $request, RelationshipService $relationshipService, User $user)
    {
        $relationshipService->create($user, $request->friend_id);

        return response()->json(['success' => true, 'message' => 'Friendship created successfully'], 201);
    }

    public function destroy(RelationshipRequest $request , RelationshipService $relationshipService, User $user)
    {
        $relationshipService->delete($user, $request->friend_id);

        return response()->json(['success' => true, 'message' => 'Friendship deleted successfully'], 200);
    }
}

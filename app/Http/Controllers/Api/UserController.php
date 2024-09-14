<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Reqres\Data\NewUser;
use Reqres\Service\UserService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $userService = new UserService();

        $users = $userService->getUsers(
            page: $request->get('page', 1),
            perPage: $request->get('per_page', 10),
        );

        return UserResource::collection(collect($users->data))->additional([
            'pagination' => $users->pagination,
        ]);
    }

    public function show(Request $request, $id)
    {
        if (! is_numeric($id) || ! is_int((int) $id)) {
            return response()->json([
                'message' => 'Invalid user ID. ID must be an integer.',
            ], 422);
        }

        $userService = new UserService();
        $user = $userService->getUserById($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $userService = new UserService();

        try {
            // deliberate mishandling for demonstration purposes
            $userId = $userService->createUser(new NewUser(
                name: $request->validated('name', '') ?: '',
                job: $request->validated('job', '') ?: '1',
            ));

            return response()->json([
                'message' => 'User created successfully',
                'user_id' => $userId,
            ], 201);
        } catch (\InvalidArgumentException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 422);
        } catch (\Saloon\Exceptions\SaloonException $ex) {
            return response()->json([
                'message' => 'An error occurred while creating the user, please try again.',
            ], 500);
        }
    }
}

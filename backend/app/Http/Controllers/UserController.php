<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiResponse;

    protected $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    // ====== Users ======

    /**
     * Retrieve a list of all users.
     * @return JsonResponse
     */
    public function listUsers(): JsonResponse
    {
        $data = $this->userService->listUsers();
        return $this->successResponse(
            $data,
            'Users retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Create a new user with validated request data.
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function createUser(StoreUserRequest $request): JsonResponse
    {
        $item = $this->userService->createUser($request->validated());
        return $this->successResponse(
            $item,
            'User created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Retrieve a specific user by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function findUser(int $id): JsonResponse
    {
        $item = $this->userService->findUser($id);
        return $this->successResponse(
            $item,
            'User retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Update an existing user with validated request data.
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateUser(UpdateUserRequest $request, int $id): JsonResponse
    {
        $item = $this->userService->updateUser($id, $request->validated());
        return $this->successResponse(
            $item,
            'User updated successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Delete a user by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return $this->successResponse(
            null,
            'User deleted successfully.',
            Response::HTTP_NO_CONTENT
        );
    }
}

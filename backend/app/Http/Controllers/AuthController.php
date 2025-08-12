<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddImageRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use http\Exception\RuntimeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response as HttpResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AuthService $authService
    )
    {
        if (!config('app.frontend_url')) {
            throw new RuntimeException('Frontend URL is not configured');
        }
    }

    // ====== OAuth ======

    /**
     * Google authorization.
     * @return RedirectResponse
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return $this->authService->getGoogleRedirect();
    }

    /**
     * Google callback after successful authorization.
     * @return HttpResponse
     */
    public function handleGoogleCallback(): HttpResponse
    {
        return $this->authService->processGoogleCallback();
    }

    // ====== Auth ======

    /**
     * Register new user.
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'User registered successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Login user.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->loginWithCookies(
            $request->validated(),
            fn($tokens) => $this->successResponse(new TokenResource($tokens), 'Login successful')
        );
    }

    /**
     * Refresh user tokens.
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        return $this->authService->refreshWithCookies(
            $request->cookie('refresh_token'),
            fn($tokens) => $this->successResponse(new TokenResource($tokens), 'Token refreshed successfully')
        );
    }

    /**
     * Log out user.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->successResponse(null, 'Successfully logged out')
            ->withCookie(cookie()->forget('refresh_token'));
    }

    /**
     * Get current user information.
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        if (!$user) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new UserResource($user->load('gallery.images')),
            'User data retrieved successfully'
        );
    }

    // ====== Profile ======

    /**
     * Update user profile info.
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $updatedUser = $this->authService->updateProfile(
            $request->validated(),
            $request->file('images')
        );

        return $this->successResponse(
            new UserResource($updatedUser),
            'Profile updated successfully'
        );
    }

    /**
     * Search users by params.
     * @param SearchUserRequest $request
     * @return JsonResponse
     */
    public function search(SearchUserRequest $request): JsonResponse
    {
        $users = $this->authService->searchUsers($request->query('searchParams', ''));

        return $this->successResponse(
            UserResource::collection($users),
            $users->isEmpty() ? 'No users found' : 'Users retrieved successfully'
        );
    }

    // ====== Admin ======

    /**
     * Promote user to admin.
     * @param User $user
     * @return JsonResponse
     */
    public function promoteToAdmin(User $user): JsonResponse
    {
        if (auth('api')->id() === $user->id) {
            return $this->errorResponse('Cannot promote yourself', Response::HTTP_FORBIDDEN);
        }

        $updatedUser = $this->authService->promoteToAdmin($user);

        return $this->successResponse(
            new UserResource($updatedUser),
            'User promoted to admin successfully'
        );
    }

    // ====== Gallery ======

    /**
     * Add profile image.
     * @param AddImageRequest $request
     * @return JsonResponse
     */
    public function addImage(AddImageRequest $request): JsonResponse
    {
        $updatedUser = $this->authService->addImage($request->file('image'));

        return $this->successResponse(
            new UserResource($updatedUser),
            'User updated successfully'
        );
    }

    /**
     * Delete profile image.
     * @param int $id
     * @return JsonResponse
     */
    public function deleteImage(int $id): JsonResponse
    {
        $deleted = $this->authService->deleteImage($id);

        if (!$deleted) {
            return $this->errorResponse('Image not found or unauthorized', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(null, 'Image deleted successfully');
    }
}

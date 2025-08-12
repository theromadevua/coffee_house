<?php

namespace App\Services;

use App\Enums\Role;
use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    // ===== OAuth =====

    /**
     * @return mixed
     */
    public function getGoogleRedirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * @return Response
     */
    public function processGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = $this->loginWithGoogle($googleUser);
        $tokens = $this->generateTokens($user);

        $frontendUrl = config('app.frontend_url');

        return response()->view('auth.google-callback', [
            'accessToken' => htmlspecialchars($tokens['access_token'], ENT_QUOTES, 'UTF-8'),
            'refreshToken' => htmlspecialchars($tokens['refresh_token'], ENT_QUOTES, 'UTF-8'),
            'tokenType' => htmlspecialchars($tokens['token_type'], ENT_QUOTES, 'UTF-8'),
            'expiresIn' => (int)$tokens['expires_in'],
            'frontendUrl' => $frontendUrl
        ], 200)
            ->header('Content-Type', 'text/html')
            ->header('X-Frame-Options', 'DENY');
    }

    /**
     * @param SocialiteUser $googleUser
     * @return User
     */
    private function loginWithGoogle(SocialiteUser $googleUser): User
    {
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(uniqid()),
                'role' => Role::USER,
            ]);
        } elseif (!$user->google_id) {
            $user->update(['google_id' => $googleUser->id]);
        }

        return $user;
    }

    // ===== Tokens =====

    /**
     * @param User $user
     * @return array
     */
    public function generateTokens(User $user): array
    {
        $accessToken = JWTAuth::fromUser($user);
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    /**
     * @param array $credentials
     * @param callable $responseBuilder
     * @return mixed
     * @throws AuthException
     */
    public function loginWithCookies(array $credentials, callable $responseBuilder)
    {
        $tokens = $this->login($credentials);

        return $responseBuilder($tokens)->cookie(
            'refresh_token',
            $tokens['refresh_token'],
            config('auth.refresh_token_expiration', 43200),
            '/',
            null,
            true,
            true,
            'Strict'
        );
    }

    /**
     * @param string|null $refreshToken
     * @param callable $responseBuilder
     * @return JsonResponse
     */
    public function refreshWithCookies(?string $refreshToken, callable $responseBuilder)
    {
        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token not provided'], 401);
        }

        $newTokens = $this->refresh($refreshToken);

        return $responseBuilder($newTokens)->cookie(
            'refresh_token',
            $newTokens['refresh_token'],
            config('auth.refresh_token_expiration', 43200),
            '/',
            null,
            true,
            true,
            'Strict'
        );
    }

    // ===== Auth =====

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => Role::USER,
        ]);
    }

    /**
     * @param array $credentials
     * @return array
     * @throws AuthException
     */
    public function login(array $credentials): array
    {
        if (!$accessToken = JWTAuth::attempt($credentials)) {
            throw new AuthException('Invalid credentials');
        }

        $user = JWTAuth::setToken($accessToken)->toUser();
        $refreshToken = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    /**
     * @param string $refreshToken
     * @return array
     */
    public function refresh(string $refreshToken): array
    {
        $newAccessToken = JWTAuth::setToken($refreshToken)->refresh();
        $user = JWTAuth::setToken($newAccessToken)->toUser();
        $newRefresh = JWTAuth::claims(['type' => 'refresh'])->fromUser($user);

        return [
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefresh,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        auth('api')->logout();
    }

    /**
     * @return User|null
     */
    public function me(): ?User
    {
        return auth('api')->user();
    }

    // ===== Profile =====

    /**
     * @param array $data
     * @param array|null $images
     * @return User
     */
    public function updateProfile(array $data, ?array $images = null): User
    {
        /** @var User $user */
        $user = auth('api')->user();

        if ($images) {
            $user->gallery()->delete();

            $gallery = $user->gallery()->create([
                'name' => 'Gallery for ' . ($data['name'] ?? $user->name),
            ]);

            foreach ($images as $image) {
                $path = $this->uploadImage($image);
                $gallery->images()->create([
                    'path' => $path,
                    'caption' => null,
                ]);
            }
        }

        $user->update($data);

        return $user->refresh()->load('gallery.images');
    }

    /**
     * @param string $query
     * @return Collection
     */
    public function searchUsers(string $query): Collection
    {
        return User::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->get();
    }

    // ===== Admin =====

    /**
     * @param User $user
     * @return User
     */
    public function promoteToAdmin(User $user): User
    {
        $user->role = Role::ADMIN;
        $user->save();

        return $user;
    }

    // ===== Gallery =====

    /**
     * @param UploadedFile $image
     * @param string|null $caption
     * @return User
     */
    public function addImage(UploadedFile $image, ?string $caption = null): User
    {
        /** @var User $user */
        $user = auth('api')->user();

        $gallery = $user->gallery()->firstOrCreate(
            [
                'galleryable_id' => $user->id,
                'galleryable_type' => User::class,
            ],
            [
                'name' => 'Gallery for ' . $user->name,
            ]
        );

        $path = $this->uploadImage($image);
        $gallery->images()->create([
            'path' => $path,
            'caption' => $caption,
        ]);

        return $user->load(['gallery.images' => fn($q) => $q->orderBy('created_at', 'desc')]);
    }

    /**
     * @param int $imageId
     * @return bool
     */
    public function deleteImage(int $imageId): bool
    {
        /** @var User $user */
        $user = auth('api')->user();
        $image = $user->gallery->images()->find($imageId);

        if (!$image) {
            return false;
        }

        $relativePath = str_replace(Storage::url(''), '', $image->path);
        Storage::disk('public')->delete($relativePath);
        $image->delete();

        return true;
    }

    // ===== Utils =====

    /**
     * @param UploadedFile $image
     * @return string
     */
    private function uploadImage(UploadedFile $image): string
    {
        $path = $image->store('images', 'public');
        return Storage::disk('public')->url($path);
    }
}

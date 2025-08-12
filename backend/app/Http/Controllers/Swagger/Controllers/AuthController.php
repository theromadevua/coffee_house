<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Post(
 *      path="/api/auth/register",
 *      operationId="registerUser",
 *      tags={"Authentication"},
 *      summary="Register a new user",
 *      description="Creates a new user account and returns the user's data.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="User registration data",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/RegisterRequest"),
 *              example={
 *                  "name": "John Doe",
 *                  "email": "john.doe@example.com",
 *                  "password": "Password123!",
 *                  "password_confirmation": "Password123!",
 *                  "role": "user"
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="User registered successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/UserResource"),
 *              example={
 *                  "success": true,
 *                  "message": "User registered successfully",
 *                  "data": {
 *                      "id": 2,
 *                      "name": "John Doe",
 *                      "email": "john.doe@example.com",
 *                      "role": "user",
 *                      "created_at": "2025-08-01T16:20:45.000000Z",
 *                      "updated_at": "2025-08-01T16:20:45.000000Z",
 *                      "gallery": {
 *                          "id": 2,
 *                          "galleryable_type": "App\\Models\\User",
 *                          "galleryable_id": 2,
 *                          "name": "John's Personal Gallery",
 *                          "created_at": "2025-08-01T16:20:45.000000Z",
 *                          "updated_at": "2025-08-01T16:20:45.000000Z",
 *                          "images": {
 *                              {
 *                                  "id": 1,
 *                                  "imageable_type": "App\\Models\\Gallery",
 *                                  "imageable_id": 2,
 *                                  "path": "http://coffee.test/storage/images/example_image.jpg",
 *                                  "caption": null,
 *                                  "created_at": "2025-08-01T16:20:45.000000Z",
 *                                  "updated_at": "2025-08-01T16:20:45.000000Z"
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "The given data was invalid.",
 *                  "errors": {
 *                      "email": {"The email has already been taken."},
 *                      "password": {"The password must be at least 8 characters."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/auth/login",
 *      operationId="loginUser",
 *      tags={"Authentication"},
 *      summary="Log in a user",
 *      description="Authenticates a user and returns an access token and refresh token. The refresh token is also set as an HttpOnly cookie.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="User login credentials",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/LoginRequest"),
 *              example={
 *                  "email": "john.doe@example.com",
 *                  "password": "Password123!"
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Login successful",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/TokenResource"),
 *              example={
 *                  "success": true,
 *                  "message": "Login successful",
 *                  "data": {
 *                      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
 *                      "token_type": "Bearer",
 *                      "expires_in": 3600,
 *                      "refresh_token": "def50200a1b2c3d4e5f6..."
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized (Invalid credentials)",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "Invalid credentials."
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/auth/refresh",
 *      operationId="refreshToken",
 *      tags={"Authentication"},
 *      summary="Refresh the access token",
 *      description="Uses the refresh token (sent via an HttpOnly cookie) to generate a new pair of access and refresh tokens.",
 *      @OA\Response(
 *          response=200,
 *          description="Token refreshed successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/TokenResource"),
 *              example={
 *                  "success": true,
 *                  "message": "Token refreshed successfully",
 *                  "data": {
 *                      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
 *                      "token_type": "Bearer",
 *                      "expires_in": 3600,
 *                      "refresh_token": "def50200b2c3d4e5f6a7..."
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized (Invalid or expired refresh token)",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "Invalid or expired refresh token."
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/auth/logout",
 *      operationId="logoutUser",
 *      tags={"Authentication"},
 *      summary="Log out the current user",
 *      description="Invalidates the user's current token and clears the refresh token cookie.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="Successfully logged out",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": true,
 *                  "message": "Successfully logged out."
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              example={
 *                  "success": false,
 *                  "message": "Unauthenticated."
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/auth/me",
 *      operationId="getCurrentUser",
 *      tags={"Authentication"},
 *      summary="Get current user's data",
 *      description="Returns the data of the currently authenticated user.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="User data retrieved successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/UserResource"),
 *              example={
 *                  "success": true,
 *                  "message": "User data retrieved successfully",
 *                  "data": {
 *                      "id": 1,
 *                      "name": "aboba",
 *                      "email": "admin@example.com",
 *                      "email_verified_at": null,
 *                      "role": "admin",
 *                      "created_at": "2025-07-30T08:33:36.000000Z",
 *                      "updated_at": "2025-07-31T13:25:46.000000Z",
 *                      "gallery": {
 *                          "id": 1,
 *                          "galleryable_type": "App\\Models\\User",
 *                          "galleryable_id": 1,
 *                          "name": "Admin's Personal Gallery",
 *                          "created_at": "2025-07-30T08:33:37.000000Z",
 *                          "updated_at": "2025-07-30T08:33:37.000000Z",
 *                          "images": {
 *                              {
 *                                  "id": 33,
 *                                  "imageable_type": "App\\Models\\Gallery",
 *                                  "imageable_id": 1,
 *                                  "path": "http://coffee.test/storage/images/SFNRzMnRn5BtsA1RObXElq6VPNKjQOLdky6T2Kcq.jpg",
 *                                  "caption": null,
 *                                  "created_at": "2025-08-01T10:06:42.000000Z",
 *                                  "updated_at": "2025-08-01T10:06:42.000000Z"
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              example={
 *                  "success": false,
 *                  "message": "Unauthenticated."
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/auth/profile",
 *      operationId="updateUserProfile",
 *      tags={"Authentication"},
 *      summary="Update current user's profile",
 *      description="Updates the profile information of the currently authenticated user. To upload an avatar, use `multipart/form-data`.",
 *      security={{"bearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for updating the profile",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(property="name", type="string", example="John Updated"),
 *                  @OA\Property(property="email", type="string", format="email", example="john.updated@example.com"),
 *                  @OA\Property(property="avatar", type="file", format="binary")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Profile updated successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/UserResource"),
 *              example={
 *                  "success": true,
 *                  "message": "Profile updated successfully",
 *                  "data": {
 *                      "id": 1,
 *                      "name": "John Updated",
 *                      "email": "john.updated@example.com",
 *                      "email_verified_at": null,
 *                      "role": "admin",
 *                      "created_at": "2025-07-30T08:33:36.000000Z",
 *                      "updated_at": "2025-08-01T16:20:45.000000Z",
 *                      "gallery": {
 *                          "id": 1,
 *                          "galleryable_type": "App\\Models\\User",
 *                          "galleryable_id": 1,
 *                          "name": "John's Personal Gallery",
 *                          "created_at": "2025-07-30T08:33:37.000000Z",
 *                          "updated_at": "2025-08-01T16:20:45.000000Z",
 *                          "images": {
 *                              {
 *                                  "id": 34,
 *                                  "imageable_type": "App\\Models\\Gallery",
 *                                  "imageable_id": 1,
 *                                  "path": "http://coffee.test/storage/images/john_updated_20250801.jpg",
 *                                  "caption": null,
 *                                  "created_at": "2025-08-01T16:20:45.000000Z",
 *                                  "updated_at": "2025-08-01T16:20:45.000000Z"
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              example={
 *                  "success": false,
 *                  "message": "Unauthenticated."
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "The given data was invalid.",
 *                  "errors": {
 *                      "email": {"The email has already been taken."},
 *                      "avatar": {"The avatar must be an image."}
 *                  }
 *              }
 *          )
 *      )
 * )
 */
class AuthController
{

}

<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Get(
 *      path="/api/users",
 *      operationId="listUsers",
 *      tags={"Users"},
 *      summary="List all users",
 *      description="Retrieves a list of all registered users.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="listUsersResponse",
 *                  summary="Example response for listing users",
 *                  value={
 *                      "success": true,
 *                      "message": "Users retrieved successfully.",
 *                      "data": {
 *                          {
 *                              "id": 1,
 *                              "name": "John Doe",
 *                              "email": "john.doe@example.com",
 *                              "role": "user",
 *                              "created_at": "2025-08-01T12:00:00.000000Z",
 *                              "updated_at": "2025-08-01T12:00:00.000000Z"
 *                          },
 *                          {
 *                              "id": 2,
 *                              "name": "Jane Smith",
 *                              "email": "jane.smith@example.com",
 *                              "role": "admin",
 *                              "created_at": "2025-08-01T12:01:00.000000Z",
 *                              "updated_at": "2025-08-01T12:01:00.000000Z"
 *                          }
 *                      }
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(
 *                  property="success",
 *                  type="boolean",
 *                  example=true
 *              ),
 *              @OA\Property(
 *                  property="message",
 *                  type="string",
 *                  example="Users retrieved successfully."
 *              ),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="name", type="string", example="John Doe"),
 *                      @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                      @OA\Property(property="role", type="string", example="user"),
 *                      @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z"),
 *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z")
 *                  )
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/users",
 *      operationId="createUser",
 *      tags={"Users"},
 *      summary="Create a new user",
 *      description="Creates a new user with the provided data.",
 *      security={{"bearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          description="User creation data",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="createUserRequest",
 *                  summary="Example request to create a user",
 *                  value={
 *                      "name": "John Doe",
 *                      "email": "john.doe@example.com",
 *                      "password": "password123",
 *                      "role": "user"
 *                  }
 *              ),
 *              type="object",
 *              required={"name", "email", "password"},
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *              @OA\Property(property="password", type="string", format="password", example="password123"),
 *              @OA\Property(property="role", type="string", example="user", enum={"user", "admin"})
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="User created successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="createUserResponse",
 *                  summary="Example response for user creation",
 *                  value={
 *                      "success": true,
 *                      "message": "User created successfully.",
 *                      "data": {
 *                          "id": 1,
 *                          "name": "John Doe",
 *                          "email": "john.doe@example.com",
 *                          "role": "user",
 *                          "created_at": "2025-08-01T12:00:00.000000Z",
 *                          "updated_at": "2025-08-01T12:00:00.000000Z"
 *                      }
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="User created successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="name", type="string", example="John Doe"),
 *                  @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                  @OA\Property(property="role", type="string", example="user"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Validation failed"),
 *              @OA\Property(property="errors", type="object")
 *          )
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/users/{id}",
 *      operationId="findUser",
 *      tags={"Users"},
 *      summary="Retrieve a specific user",
 *      description="Retrieves details of a user by their ID.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to retrieve",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="findUserResponse",
 *                  summary="Example response for retrieving a user",
 *                  value={
 *                      "success": true,
 *                      "message": "User retrieved successfully.",
 *                      "data": {
 *                          "id": 1,
 *                          "name": "John Doe",
 *                          "email": "john.doe@example.com",
 *                          "role": "user",
 *                          "created_at": "2025-08-01T12:00:00.000000Z",
 *                          "updated_at": "2025-08-01T12:00:00.000000Z",
 *                          "gallery": {
 *                              "id": 1,
 *                              "name": "User Gallery",
 *                              "galleryable_id": 1,
 *                              "galleryable_type": "App\\Models\\User",
 *                              "created_at": "2025-08-01T12:00:00.000000Z",
 *                              "updated_at": "2025-08-01T12:00:00.000000Z"
 *                          },
 *                          "images": {
 *                              {
 *                                  "id": 1,
 *                                  "path": "images/user1.jpg",
 *                                  "caption": "Profile picture",
 *                                  "imageable_id": 1,
 *                                  "imageable_type": "App\\Models\\User",
 *                                  "created_at": "2025-08-01T12:00:00.000000Z",
 *                                  "updated_at": "2025-08-01T12:00:00.000000Z"
 *                              }
 *                          }
 *                      }
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="User retrieved successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="name", type="string", example="John Doe"),
 *                  @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                  @OA\Property(property="role", type="string", example="user"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z"),
 *                  @OA\Property(
 *                      property="gallery",
 *                      type="object",
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="name", type="string", example="User Gallery"),
 *                      @OA\Property(property="galleryable_id", type="integer", example=1),
 *                      @OA\Property(property="galleryable_type", type="string", example="App\\Models\\User"),
 *                      @OA\Property(property="created_at", type="string", format="date-time"),
 *                      @OA\Property(property="updated_at", type="string", format="date-time")
 *                  ),
 *                  @OA\Property(
 *                      property="images",
 *                      type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          @OA\Property(property="id", type="integer", example=1),
 *                          @OA\Property(property="path", type="string", example="images/user1.jpg"),
 *                          @OA\Property(property="caption", type="string", example="Profile picture"),
 *                          @OA\Property(property="imageable_id", type="integer", example=1),
 *                          @OA\Property(property="imageable_type", type="string", example="App\\Models\\User"),
 *                          @OA\Property(property="created_at", type="string", format="date-time"),
 *                          @OA\Property(property="updated_at", type="string", format="date-time")
 *                      )
 *                  )
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="User not found")
 *          )
 *      )
 * )
 *
 * @OA\Put(
 *      path="/api/users/{id}",
 *      operationId="updateUser",
 *      tags={"Users"},
 *      summary="Update a user",
 *      description="Updates the details of a specific user by their ID.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to update",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="User update data",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="updateUserRequest",
 *                  summary="Example request to update a user",
 *                  value={
 *                      "name": "John Updated",
 *                      "email": "john.updated@example.com",
 *                      "role": "admin"
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(property="name", type="string", example="John Updated"),
 *              @OA\Property(property="email", type="string", format="email", example="john.updated@example.com"),
 *              @OA\Property(property="role", type="string", example="admin", enum={"user", "admin"})
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User updated successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="updateUserResponse",
 *                  summary="Example response for user update",
 *                  value={
 *                      "success": true,
 *                      "message": "User updated successfully.",
 *                      "data": {
 *                          "id": 1,
 *                          "name": "John Updated",
 *                          "email": "john.updated@example.com",
 *                          "role": "admin",
 *                          "created_at": "2025-08-01T12:00:00.000000Z",
 *                          "updated_at": "2025-08-01T12:30:00.000000Z"
 *                      }
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="User updated successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="name", type="string", example="John Updated"),
 *                  @OA\Property(property="email", type="string", example="john.updated@example.com"),
 *                  @OA\Property(property="role", type="string", example="admin"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-01T12:00:00.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T12:30:00.000000Z")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="User not found")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Validation failed"),
 *              @OA\Property(property="errors", type="object")
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/users/{id}",
 *      operationId="deleteUser",
 *      tags={"Users"},
 *      summary="Delete a user",
 *      description="Deletes a specific user by their ID.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to delete",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="User deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="deleteUserResponse",
 *                  summary="Example response for user deletion",
 *                  value={
 *                      "success": true,
 *                      "message": "User deleted successfully.",
 *                      "data": null
 *                  }
 *              ),
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="User deleted successfully."),
 *              @OA\Property(property="data", type="null", example=null)
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="User not found")
 *          )
 *      )
 * )
 */
class UserController
{
}

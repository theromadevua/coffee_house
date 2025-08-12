<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Get(
 *      path="/api/categories",
 *      operationId="getCategoriesList",
 *      tags={"Categories"},
 *      summary="Get a list of categories",
 *      description="Returns a list of all categories.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="success",
 *                  type="boolean",
 *                  example=true
 *              ),
 *              @OA\Property(
 *                  property="message",
 *                  type="string",
 *                  example="Successful categories request"
 *              ),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="name", type="string", example="Main Dishes"),
 *                      @OA\Property(property="description", type="string", example="Delicious main courses for any meal."),
 *                      @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-30T08:33:37.000000Z"),
 *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-30T08:33:37.000000Z")
 *                  ),
 *                  example={
 *                      {
 *                          "id": 1,
 *                          "name": "Main Dishes",
 *                          "description": "Delicious main courses for any meal.",
 *                          "created_at": "2025-07-30T08:33:37.000000Z",
 *                          "updated_at": "2025-07-30T08:33:37.000000Z"
 *                      },
 *                      {
 *                          "id": 2,
 *                          "name": "Salad",
 *                          "description": "Fresh and healthy salads",
 *                          "created_at": "2025-07-30T08:34:00.000000Z",
 *                          "updated_at": "2025-07-30T08:34:00.000000Z"
 *                      }
 *                  }
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/categories",
 *      operationId="storeCategory",
 *      tags={"Categories"},
 *      summary="Create a new category",
 *      description="Creates a new category record and returns it",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for creating a new category",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/CategoryRequest",
 *                  example={
 *                      "name": "Desserts",
 *                      "description": "Sweet treats and desserts"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Category created successfully",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="success",
 *                  type="boolean",
 *                  example=true
 *              ),
 *              @OA\Property(
 *                  property="message",
 *                  type="string",
 *                  example="Successful category creation"
 *              ),
 *              @OA\Property(
 *                  property="data",
 *                  @OA\Property(property="id", type="integer", example=16),
 *                  @OA\Property(property="name", type="string", example="cappuccino1111111"),
 *                  @OA\Property(property="description", type="string", example="cappuccino"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-22T07:18:22.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-22T07:18:22.000000Z"),
 *                  example={
 *                      "id": 16,
 *                      "name": "cappuccino1111111",
 *                      "description": "cappuccino",
 *                      "created_at": "2025-07-22T07:18:22.000000Z",
 *                      "updated_at": "2025-07-22T07:18:22.000000Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/MessageResponse",
 *                  example={
 *                      "success": false,
 *                      "message": "Validation failed",
 *                      "data": {
 *                          "name": {"The name field is required."}
 *                      }
 *                  }
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Put(
 *      path="/api/categories/{id}",
 *      operationId="updateCategory",
 *      tags={"Categories"},
 *      summary="Update an existing category",
 *      description="Updates category data by ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the category to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for updating the category",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/CategoryRequest",
 *                  example={
 *                      "name": "Updated Pizza",
 *                      "description": "Updated variety of pizzas with premium toppings"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Category updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="success",
 *                  type="boolean",
 *                  example=true
 *              ),
 *              @OA\Property(
 *                  property="message",
 *                  type="string",
 *                  example="Successful category update"
 *              ),
 *              @OA\Property(
 *                  property="data",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="name", type="string", example="Updated Pizza"),
 *                  @OA\Property(property="description", type="string", example="Updated variety of pizzas with premium toppings"),
 *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-30T08:33:37.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-01T15:37:00.000000Z"),
 *                  example={
 *                      "id": 1,
 *                      "name": "Updated Pizza",
 *                      "description": "Updated variety of pizzas with premium toppings",
 *                      "created_at": "2025-07-30T08:33:37.000000Z",
 *                      "updated_at": "2025-08-01T15:37:00.000000Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Category not found",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/MessageResponse",
 *                  example={
 *                      "success": false,
 *                      "message": "Category not found",
 *                      "data": null
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/MessageResponse",
 *                  example={
 *                      "success": false,
 *                      "message": "Validation failed",
 *                      "data": {
 *                          "name": {"The name field is required."}
 *                      }
 *                  }
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/categories/{id}",
 *      operationId="deleteCategory",
 *      tags={"Categories"},
 *      summary="Delete a category",
 *      description="Deletes a category by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the category to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Category deleted successfully (No Content)"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Category not found",
 *          @OA\JsonContent(
 *              @OA\Schema(
 *                  ref="#/components/schemas/MessageResponse",
 *                  example={
 *                      "success": false,
 *                      "message": "Category not found",
 *                      "data": null
 *                  }
 *              )
 *          )
 *      )
 * )
 */
class CategoryController
{

}

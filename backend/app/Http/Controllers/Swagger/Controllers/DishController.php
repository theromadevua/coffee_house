<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Get(
 *      path="/api/dishes",
 *      operationId="getDishesList",
 *      tags={"Dishes"},
 *      summary="Get a list of dishes",
 *      description="Returns a list of all dishes. Can be filtered by category.",
 *      @OA\Parameter(
 *          name="category",
 *          in="query",
 *          description="Category name to filter by",
 *          required=false,
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Schema(ref="#/components/schemas/Dish")
 *              ),
 *              example={
 *                  {
 *                      "id": 1,
 *                      "category_id": 1,
 *                      "name": "Margherita Pizza",
 *                      "description": "Classic pizza with tomato sauce and mozzarella",
 *                      "price": 12.99,
 *                      "image_path": "/images/dishes/margherita.jpg",
 *                      "category": {
 *                          "id": 1,
 *                          "name": "Pizza"
 *                      },
 *                      "gallery": {
 *                          "id": 1,
 *                          "image_path": "/images/dishes/margherita.jpg"
 *                      }
 *                  },
 *                  {
 *                      "id": 2,
 *                      "category_id": 2,
 *                      "name": "Caesar Salad",
 *                      "description": "Fresh romaine with Caesar dressing",
 *                      "price": 8.99,
 *                      "image_path": "/images/dishes/caesar.jpg",
 *                      "category": {
 *                          "id": 2,
 *                          "name": "Salad"
 *                      },
 *                      "gallery": {
 *                          "id": 2,
 *                          "image_path": "/images/dishes/caesar.jpg"
 *                      }
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/dishes/{id}",
 *      operationId="getDishById",
 *      tags={"Dishes"},
 *      summary="Get dish information",
 *      description="Returns a single dish by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="The ID of the dish",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/Dish"),
 *              example={
 *                  "id": 1,
 *                  "category_id": 1,
 *                  "name": "Margherita Pizza",
 *                  "description": "Classic pizza with tomato sauce and mozzarella",
 *                  "price": 12.99,
 *                  "image_path": "/images/dishes/margherita.jpg",
 *                  "category": {
 *                      "id": 1,
 *                      "name": "Pizza"
 *                  },
 *                  "gallery": {
 *                      "id": 1,
 *                      "image_path": "/images/dishes/margherita.jpg"
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Dish not found",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "Dish not found",
 *                  "data": null
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/dishes",
 *      operationId="storeDish",
 *      tags={"Dishes"},
 *      summary="Create a new dish",
 *      description="Creates a new dish record and returns it",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for creating a new dish",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="category_id",
 *                      type="integer",
 *                      example=1
 *                  ),
 *                  @OA\Property(
 *                      property="name",
 *                      type="string",
 *                      example="Margherita Pizza"
 *                  ),
 *                  @OA\Property(
 *                      property="description",
 *                      type="string",
 *                      example="Classic pizza with tomato sauce and mozzarella"
 *                  ),
 *                  @OA\Property(
 *                      property="price",
 *                      type="number",
 *                      format="float",
 *                      example=12.99
 *                  ),
 *                  @OA\Property(
 *                      property="image",
 *                      type="string",
 *                      format="binary"
 *                  )
 *              )
 *          ),
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/StoreDishRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Dish created successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/Dish"),
 *              example={
 *                  "id": 1,
 *                  "category_id": 1,
 *                  "name": "Margherita Pizza",
 *                  "description": "Classic pizza with tomato sauce and mozzarella",
 *                  "price": 12.99,
 *                  "image_path": "/images/dishes/margherita.jpg",
 *                  "category": {
 *                      "id": 1,
 *                      "name": "Pizza"
 *                  },
 *                  "gallery": {
 *                      "id": 1,
 *                      "image_path": "/images/dishes/margherita.jpg"
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
 *                  "message": "Validation failed",
 *                  "data": {
 *                      "name": {"The name field is required."},
 *                      "price": {"The price must be a number."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/dishes/{id}",
 *      operationId="updateDish",
 *      tags={"Dishes"},
 *      summary="Update an existing dish",
 *      description="Updates dish data by ID. Use POST with _method=PUT to send multipart/form-data.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the dish to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for updating the dish",
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="_method",
 *                      type="string",
 *                      example="PUT"
 *                  ),
 *                  @OA\Property(
 *                      property="category_id",
 *                      type="integer",
 *                      example=1
 *                  ),
 *                  @OA\Property(
 *                      property="name",
 *                      type="string",
 *                      example="Margherita Pizza"
 *                  ),
 *                  @OA\Property(
 *                      property="description",
 *                      type="string",
 *                      example="Updated classic pizza with tomato sauce"
 *                  ),
 *                  @OA\Property(
 *                      property="price",
 *                      type="number",
 *                      format="float",
 *                      example=13.99
 *                  ),
 *                  @OA\Property(
 *                      property="image",
 *                      type="string",
 *                      format="binary"
 *                  )
 *              )
 *          ),
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/UpdateDishRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Dish updated successfully",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/Dish"),
 *              example={
 *                  "id": 1,
 *                  "category_id": 1,
 *                  "name": "Margherita Pizza",
 *                  "description": "Updated classic pizza with tomato sauce",
 *                  "price": 13.99,
 *                  "image_path": "/images/dishes/margherita_updated.jpg",
 *                  "category": {
 *                      "id": 1,
 *                      "name": "Pizza"
 *                  },
 *                  "gallery": {
 *                      "id": 1,
 *                      "image_path": "/images/dishes/margherita_updated.jpg"
 *                  }
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Dish not found",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "Dish not found",
 *                  "data": null
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
 *                  "message": "Validation failed",
 *                  "data": {
 *                      "price": {"The price must be a number."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/dishes/{id}",
 *      operationId="deleteDish",
 *      tags={"Dishes"},
 *      summary="Delete a dish",
 *      description="Deletes a dish by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the dish to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Dish deleted successfully (No Content)"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Dish not found",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse"),
 *              example={
 *                  "success": false,
 *                  "message": "Dish not found",
 *                  "data": null
 *              }
 *          )
 *      )
 * )
 */
class DishController
{

}

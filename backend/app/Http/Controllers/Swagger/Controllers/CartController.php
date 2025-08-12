<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Get(
 *      path="/api/cart",
 *      operationId="getCartContents",
 *      tags={"Cart"},
 *      summary="Get cart contents",
 *      description="Returns the contents of the current user's cart.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="cartResponse",
 *                  summary="Example cart response",
 *                  value={
 *                      "success": true,
 *                      "message": "Cart received successfully.",
 *                      "data": {
 *                          "id": 1,
 *                          "user_id": 1,
 *                          "created_at": "2025-07-21T08:15:48.000000Z",
 *                          "updated_at": "2025-07-21T08:15:48.000000Z",
 *                          "total_price": "29.13",
 *                          "items": {
 *                              {
 *                                  "id": 1,
 *                                  "category_id": 1,
 *                                  "name": "Grilled Chicken",
 *                                  "description": "Juicy grilled chicken breast with herbs.",
 *                                  "image_path": "images/chicken.jpg",
 *                                  "price": "12.99",
 *                                  "created_at": "2025-07-21T08:15:41.000000Z",
 *                                  "updated_at": "2025-07-21T08:15:41.000000Z",
 *                                  "pivot": {
 *                                      "cart_id": 1,
 *                                      "dish_id": 1,
 *                                      "quantity": 1,
 *                                      "created_at": "2025-07-21T11:01:02.000000Z",
 *                                      "updated_at": "2025-07-21T11:01:02.000000Z"
 *                                  },
 *                                  "gallery": {
 *                                      "id": 1,
 *                                      "dish_id": 1,
 *                                      "name": "Gallery for Grilled Chicken",
 *                                      "created_at": "2025-07-21T08:15:41.000000Z",
 *                                      "updated_at": "2025-07-21T08:15:41.000000Z"
 *                                  }
 *                              },
 *                              {
 *                                  "id": 4,
 *                                  "category_id": 2,
 *                                  "name": "veniam",
 *                                  "description": "Deserunt reprehenderit qui consequatur quia reiciendis.",
 *                                  "image_path": "Reiciendis qui et nulla dolor quis quam et.",
 *                                  "price": "16.14",
 *                                  "created_at": "2025-07-21T08:15:41.000000Z",
 *                                  "updated_at": "2025-07-21T08:15:41.000000Z",
 *                                  "pivot": {
 *                                      "cart_id": 1,
 *                                      "dish_id": 4,
 *                                      "quantity": 1,
 *                                      "created_at": "2025-07-21T11:34:50.000000Z",
 *                                      "updated_at": "2025-07-21T11:34:50.000000Z"
 *                                  },
 *                                  "gallery": {
 *                                      "id": 4,
 *                                      "dish_id": 4,
 *                                      "name": "Gallery for veniam",
 *                                      "created_at": "2025-07-21T08:15:41.000000Z",
 *                                      "updated_at": "2025-07-21T08:15:41.000000Z"
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/Cart")
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/cart/items",
 *      operationId="addCartItem",
 *      tags={"Cart"},
 *      summary="Add an item to the cart",
 *      description="Adds a specified quantity of a dish to the user's cart.",
 *      security={{"bearerAuth":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for adding an item to the cart",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="addItemRequest",
 *                  summary="Example request to add item",
 *                  value={
 *                      "dish_id": 1,
 *                      "quantity": 2
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/AddItemRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item added successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="successResponse",
 *                  summary="Example success response",
 *                  value={
 *                      "success": true,
 *                      "message": "Item added to the cart.",
 *                      "data": null
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      )
 * )
 *
 * @OA\Put(
 *      path="/api/cart/items/{dish_id}",
 *      operationId="updateCartItem",
 *      tags={"Cart"},
 *      summary="Update an item's quantity in the cart",
 *      description="Updates the quantity of a specific dish in the user's cart.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="dish_id",
 *          in="path",
 *          description="ID of the dish to update in the cart",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for updating the item quantity",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="updateItemRequest",
 *                  summary="Example request to update item quantity",
 *                  value={
 *                      "quantity": 3
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/UpdateCartItemRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item quantity updated successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="successResponse",
 *                  summary="Example success response",
 *                  value={
 *                      "success": true,
 *                      "message": "Item quantity updated in the cart.",
 *                      "data": null
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Item not found in cart",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/cart/items/{dish_id}",
 *      operationId="removeCartItem",
 *      tags={"Cart"},
 *      summary="Remove an item from the cart",
 *      description="Removes a dish completely from the user's cart.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *          name="dish_id",
 *          in="path",
 *          description="ID of the dish to remove from the cart",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item removed successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="successResponse",
 *                  summary="Example success response",
 *                  value={
 *                      "success": true,
 *                      "message": "Item removed from the cart.",
 *                      "data": null
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Item not found in cart",
 *          @OA\JsonContent(
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/cart",
 *      operationId="clearCart",
 *      tags={"Cart"},
 *      summary="Clear the entire cart",
 *      description="Removes all items from the user's cart.",
 *      security={{"bearerAuth":{}}},
 *      @OA\Response(
 *          response=200,
 *          description="Cart cleared successfully",
 *          @OA\JsonContent(
 *              @OA\Examples(
 *                  example="successResponse",
 *                  summary="Example success response",
 *                  value={
 *                      "success": true,
 *                      "message": "Cart cleared successfully.",
 *                      "data": null
 *                  }
 *              ),
 *              @OA\Schema(ref="#/components/schemas/MessageResponse")
 *          )
 *      )
 * )
 */
class CartController
{

}

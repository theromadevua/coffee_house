<?php

namespace App\Http\Controllers\Swagger\Controllers;

use OpenApi\Attributes as OA;

/**
 * @OA\Components(
 *     @OA\Schema(
 *         schema="Order",
 *         title="Order Model",
 *         description="The main order model",
 *         @OA\Property(property="id", type="integer", format="int64", description="Order ID", example=1),
 *         @OA\Property(property="user_id", type="integer", format="int64", description="ID of the user who placed the order", example=1),
 *         @OA\Property(property="status", type="string", description="Current status of the order", enum={"pending", "processing", "shipped", "delivered", "cancelled"}, example="processing"),
 *         @OA\Property(property="total_amount", type="number", format="float", description="Total amount of the order", example=200.50),
 *         @OA\Property(property="delivery_address", type="string", description="Delivery address for the order", example="123 Main St, Kyiv, Ukraine"),
 *         @OA\Property(property="contact_phone", type="string", description="Contact phone number", example="+380066666666"),
 *         @OA\Property(property="delivery_time", type="string", format="date-time", description="Scheduled delivery time", example="2025-08-02T18:00:00Z"),
 *         @OA\Property(
 *             property="order_items",
 *             type="array",
 *             description="List of items in the order",
 *             @OA\Items(ref="#/components/schemas/OrderItem"),
 *             example={
 *                 {
 *                     "id": 1,
 *                     "product_id": 101,
 *                     "product_name": "Margherita Pizza",
 *                     "quantity": 2,
 *                     "price": 100.25
 *                 },
 *                 {
 *                     "id": 2,
 *                     "product_id": 102,
 *                     "product_name": "Pepperoni Pizza",
 *                     "quantity": 1,
 *                     "price": 120.00
 *                 }
 *             }
 *         ),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Creation date", example="2025-08-01T10:00:00Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date", example="2025-08-01T12:30:00Z")
 *     ),
 *     @OA\Schema(
 *         schema="OrderItem",
 *         title="OrderItem Model",
 *         description="An item within an order",
 *         @OA\Property(property="id", type="integer", format="int64", description="Order item ID", example=1),
 *         @OA\Property(property="product_id", type="integer", format="int64", description="Product ID", example=101),
 *         @OA\Property(property="product_name", type="string", description="Product name", example="Margherita Pizza"),
 *         @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
 *         @OA\Property(property="price", type="number", format="float", description="Price per unit", example=100.25)
 *     ),
 *     @OA\Schema(
 *         schema="StoreOrderRequest",
 *         title="Store Order Request",
 *         description="Request body for creating a new order",
 *         required={"user_id", "items", "delivery_address", "contact_phone"},
 *         @OA\Property(property="user_id", type="integer", format="int64", description="User ID", example=1),
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             description="Array of products to order",
 *             @OA\Items(
 *                 type="object",
 *                 required={"product_id", "quantity"},
 *                 @OA\Property(property="product_id", type="integer", format="int64", description="Product ID", example=101),
 *                 @OA\Property(property="quantity", type="integer", description="Product quantity", example=2)
 *             ),
 *             example={
 *                 {"product_id": 101, "quantity": 2},
 *                 {"product_id": 102, "quantity": 1}
 *             }
 *         ),
 *         @OA\Property(property="delivery_address", type="string", description="Delivery address", example="123 Main St, Kyiv, Ukraine"),
 *         @OA\Property(property="contact_phone", type="string", description="Contact phone number", example="+380066666666"),
 *         @OA\Property(property="delivery_time", type="string", format="date-time", description="Preferred delivery time", nullable=true, example="2025-08-02T18:00:00Z")
 *     ),
 *     @OA\Schema(
 *         schema="UpdateOrderRequest",
 *         title="Update Order Request",
 *         description="Request body for updating an existing order",
 *         @OA\Property(property="user_id", type="integer", format="int64", description="User ID", example=1, nullable=true),
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             description="Array of products to order",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="product_id", type="integer", format="int64", description="Product ID", example=101),
 *                 @OA\Property(property="quantity", type="integer", description="Product quantity", example=2)
 *             ),
 *             example={
 *                 {"product_id": 101, "quantity": 3},
 *                 {"product_id": 103, "quantity": 1}
 *             }
 *         ),
 *         @OA\Property(property="delivery_address", type="string", description="Delivery address", example="456 Oak St, Kyiv, Ukraine", nullable=true),
 *         @OA\Property(property="contact_phone", type="string", description="Contact phone number", example="+380077777777", nullable=true),
 *         @OA\Property(property="delivery_time", type="string", format="date-time", description="Preferred delivery time", nullable=true, example="2025-08-03T19:00:00Z")
 *     ),
 *     @OA\Schema(
 *         schema="UpdateOrderStatusRequest",
 *         title="Update Order Status Request",
 *         description="Request body for updating the order status",
 *         required={"status"},
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             description="The new status of the order",
 *             enum={"pending", "processing", "shipped", "delivered", "cancelled"},
 *             example="shipped"
 *         )
 *     ),
 *     @OA\Schema(
 *         schema="ErrorResponse",
 *         title="Error Response",
 *         description="Standard error response",
 *         @OA\Property(property="success", type="boolean", example=false),
 *         @OA\Property(property="message", type="string", example="Order not found."),
 *         @OA\Property(property="data", type="object", nullable=true, example=null)
 *     ),
 *     @OA\Schema(
 *         schema="ValidationErrorResponse",
 *         title="Validation Error Response",
 *         description="Validation error response payload",
 *         @OA\Property(property="message", type="string", example="The given data was invalid."),
 *         @OA\Property(
 *             property="errors",
 *             type="object",
 *             example={
 *                 "user_id": {"The user_id field is required."},
 *                 "items.0.quantity": {"The quantity must be at least 1."}
 *             }
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *      path="/api/orders",
 *      operationId="listOrders",
 *      tags={"Orders"},
 *      summary="Get a list of all orders",
 *      description="Returns an array of orders.",
 *      @OA\Response(
 *          response=200,
 *          description="Operation successful",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Orders retrieved successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(ref="#/components/schemas/Order"),
 *                  example={
 *                      {
 *                          "id": 1,
 *                          "user_id": 1,
 *                          "status": "processing",
 *                          "total_amount": 200.50,
 *                          "delivery_address": "123 Main St, Kyiv, Ukraine",
 *                          "contact_phone": "+380066666666",
 *                          "delivery_time": "2025-08-02T18:00:00Z",
 *                          "order_items": {
 *                              {
 *                                  "id": 1,
 *                                  "product_id": 101,
 *                                  "product_name": "Margherita Pizza",
 *                                  "quantity": 2,
 *                                  "price": 100.25
 *                              }
 *                          },
 *                          "created_at": "2025-08-01T10:00:00Z",
 *                          "updated_at": "2025-08-01T12:30:00Z"
 *                      }
 *                  }
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/orders",
 *      operationId="createOrder",
 *      tags={"Orders"},
 *      summary="Create a new order",
 *      description="Creates a new order record and returns it.",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for creating the order",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/StoreOrderRequest",
 *              example={
 *                  "user_id": 1,
 *                  "items": {
 *                      {"product_id": 101, "quantity": 2},
 *                      {"product_id": 102, "quantity": 1}
 *                  },
 *                  "delivery_address": "123 Main St, Kyiv, Ukraine",
 *                  "contact_phone": "+380066666666",
 *                  "delivery_time": "2025-08-02T18:00:00Z"
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Order created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Order created successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  ref="#/components/schemas/Order",
 *                  example={
 *                      "id": 1,
 *                      "user_id": 1,
 *                      "status": "pending",
 *                      "total_amount": 320.50,
 *                      "delivery_address": "123 Main St, Kyiv, Ukraine",
 *                      "contact_phone": "+380066666666",
 *                      "delivery_time": "2025-08-02T18:00:00Z",
 *                      "order_items": {
 *                          {
 *                              "id": 1,
 *                              "product_id": 101,
 *                              "product_name": "Margherita Pizza",
 *                              "quantity": 2,
 *                              "price": 100.25
 *                          },
 *                          {
 *                              "id": 2,
 *                              "product_id": 102,
 *                              "product_name": "Pepperoni Pizza",
 *                              "quantity": 1,
 *                              "price": 120.00
 *                          }
 *                      },
 *                      "created_at": "2025-08-01T10:00:00Z",
 *                      "updated_at": "2025-08-01T10:00:00Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation Error",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ValidationErrorResponse",
 *              example={
 *                  "message": "The given data was invalid.",
 *                  "errors": {
 *                      "user_id": {"The user_id field is required."},
 *                      "items.0.quantity": {"The quantity must be at least 1."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/orders/{id}",
 *      operationId="findOrder",
 *      tags={"Orders"},
 *      summary="Find an order by ID",
 *      description="Returns a single order.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Order ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Operation successful",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Order retrieved successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  ref="#/components/schemas/Order",
 *                  example={
 *                      "id": 1,
 *                      "user_id": 1,
 *                      "status": "processing",
 *                      "total_amount": 200.50,
 *                      "delivery_address": "123 Main St, Kyiv, Ukraine",
 *                      "contact_phone": "+380066666666",
 *                      "delivery_time": "2025-08-02T18:00:00Z",
 *                      "order_items": {
 *                          {
 *                              "id": 1,
 *                              "product_id": 101,
 *                              "product_name": "Margherita Pizza",
 *                              "quantity": 2,
 *                              "price": 100.25
 *                          }
 *                      },
 *                      "created_at": "2025-08-01T10:00:00Z",
 *                      "updated_at": "2025-08-01T12:30:00Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Order not found",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ErrorResponse",
 *              example={
 *                  "success": false,
 *                  "message": "Order not found.",
 *                  "data": null
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Put(
 *      path="/api/orders/{id}",
 *      operationId="updateOrder",
 *      tags={"Orders"},
 *      summary="Update an existing order",
 *      description="Updates order data by its ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the order to update",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Data for updating the order",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/UpdateOrderRequest",
 *              example={
 *                  "user_id": 1,
 *                  "items": {
 *                      {"product_id": 101, "quantity": 3},
 *                      {"product_id": 103, "quantity": 1}
 *                  },
 *                  "delivery_address": "456 Oak St, Kyiv, Ukraine",
 *                  "contact_phone": "+380077777777",
 *                  "delivery_time": "2025-08-03T19:00:00Z"
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Order updated successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Order updated successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  ref="#/components/schemas/Order",
 *                  example={
 *                      "id": 1,
 *                      "user_id": 1,
 *                      "status": "processing",
 *                      "total_amount": 350.75,
 *                      "delivery_address": "456 Oak St, Kyiv, Ukraine",
 *                      "contact_phone": "+380077777777",
 *                      "delivery_time": "2025-08-03T19:00:00Z",
 *                      "order_items": {
 *                          {
 *                              "id": 1,
 *                              "product_id": 101,
 *                              "product_name": "Margherita Pizza",
 *                              "quantity": 3,
 *                              "price": 100.25
 *                          },
 *                          {
 *                              "id": 2,
 *                              "product_id": 103,
 *                              "product_name": "Hawaiian Pizza",
 *                              "quantity": 1,
 *                              "price": 50.00
 *                          }
 *                      },
 *                      "created_at": "2025-08-01T10:00:00Z",
 *                      "updated_at": "2025-08-01T14:00:00Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Order not found",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ErrorResponse",
 *              example={
 *                  "success": false,
 *                  "message": "Order not found.",
 *                  "data": null
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation Error",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ValidationErrorResponse",
 *              example={
 *                  "message": "The given data was invalid.",
 *                  "errors": {
 *                      "items.0.quantity": {"The quantity must be at least 1."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Patch(
 *      path="/api/orders/{id}/status",
 *      operationId="updateOrderStatus",
 *      tags={"Orders"},
 *      summary="Update an order's status",
 *      description="Updates only the status of an existing order.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="Order ID",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="The new status for the order",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/UpdateOrderStatusRequest",
 *              example={
 *                  "status": "shipped"
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Order status updated successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Order status updated successfully."),
 *              @OA\Property(
 *                  property="data",
 *                  ref="#/components/schemas/Order",
 *                  example={
 *                      "id": 1,
 *                      "user_id": 1,
 *                      "status": "shipped",
 *                      "total_amount": 200.50,
 *                      "delivery_address": "123 Main St, Kyiv, Ukraine",
 *                      "contact_phone": "+380066666666",
 *                      "delivery_time": "2025-08-02T18:00:00Z",
 *                      "order_items": {
 *                          {
 *                              "id": 1,
 *                              "product_id": 101,
 *                              "product_name": "Margherita Pizza",
 *                              "quantity": 2,
 *                              "price": 100.25
 *                          }
 *                      },
 *                      "created_at": "2025-08-01T10:00:00Z",
 *                      "updated_at": "2025-08-01T15:00:00Z"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Order not found",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ErrorResponse",
 *              example={
 *                  "success": false,
 *                  "message": "Order not found.",
 *                  "data": null
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation Error (e.g., invalid status)",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ValidationErrorResponse",
 *              example={
 *                  "message": "The given data was invalid.",
 *                  "errors": {
 *                      "status": {"The selected status is invalid."}
 *                  }
 *              }
 *          )
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/orders/{id}",
 *      operationId="deleteOrder",
 *      tags={"Orders"},
 *      summary="Delete an order",
 *      description="Deletes an order by its ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the order to delete",
 *          @OA\Schema(type="integer", example=1)
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Order deleted successfully (No Content)"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Order not found",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/ErrorResponse",
 *              example={
 *                  "success": false,
 *                  "message": "Order not found.",
 *                  "data": null
 *              }
 *          )
 *      )
 * )
 */
class OrderController
{
}

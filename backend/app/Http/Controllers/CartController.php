<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Dish;
use App\Services\CartService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    use ApiResponse;

    protected CartService $cartService;

    public function __construct(
        CartService $cartService
    )
    {
        $this->cartService = $cartService;
    }

    // ====== Cart ======

    /**
     * Get a current user's cart details.
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $cartData = $this->cartService->getCartContents();
        return $this->successResponse($cartData, 'Cart details retrieved successfully.');
    }

    /**
     * Retrieve a list of all carts.
     * @return JsonResponse
     */
    public function getAllCarts(): JsonResponse
    {
        $cartData = $this->cartService->getAllCarts();
        return $this->successResponse($cartData, 'Cart details retrieved successfully.');
    }

    /**
     * Get a cart by ID.
     * @param $id
     * @return JsonResponse
     */
    public function getCartById($id): JsonResponse
    {
        $cartData = $this->cartService->getCartById($id);
        return $this->successResponse($cartData, 'Cart received successfully.');
    }

    // ====== Items ======

    /**
     * Add a Dish Item to the cart of the current user.
     * @param AddItemRequest $request
     * @return JsonResponse
     */
    public function addItem(AddItemRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $message = $this->cartService->addItem(
            $validated['dish_id'],
            $validated['quantity']
        );

        return $this->successResponse(null, $message);
    }

    /**
     * Update the cart of the current user.
     * @param UpdateCartItemRequest $request
     * @param Dish $dish
     * @return JsonResponse
     */
    public function updateItem(UpdateCartItemRequest $request, Dish $dish): JsonResponse
    {
        $validated = $request->validated();

        $wasUpdated = $this->cartService->updateItem(
            $dish->id,
            $validated['quantity']
        );

        if (!$wasUpdated) {
            return $this->errorResponse('Item not found in the cart.', 404);
        }

        return $this->successResponse(null, 'Item quantity updated successfully.');
    }

    /**
     * Remove a Dish Item from the cart of the current user.
     * @param $id
     * @return JsonResponse
     */
    public function removeItem($id): JsonResponse
    {
        $wasRemoved = $this->cartService->removeItem($id);

        if (!$wasRemoved) {
            return $this->errorResponse('Item not found in the cart.', 404);
        }

        return $this->successResponse(null, 'Item removed from the cart.');
    }

    // ====== Clear Cart ======

    /**
     * Clear the cart of the current user.
     * @return JsonResponse
     */
    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return $this->successResponse(null, 'Cart cleared successfully.');
    }
}

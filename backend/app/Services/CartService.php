<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CartService
{

    // ===== Cart =====

    /**
     * @param User $user
     * @return Cart
     */
    private function findOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * @param Cart $cart
     * @return float
     */
    private function calculateTotalPrice(Cart $cart): float
    {
        return $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->price * $item->pivot->quantity);
        }, 0.0);
    }

    /**
     * @param string $id
     * @return Cart
     */
    public function getCartById(string $id): Cart
    {
        $cart = Cart::with('items.gallery')->find($id);
        $cart->total_price = number_format($this->calculateTotalPrice($cart), 2, '.', '');

        return $cart;
    }

    /**
     * @return Collection
     */
    public function getAllCarts(): Collection
    {
        $carts = Cart::with('items.gallery')->get();
        $carts->each(function ($cart) {
            $cart->total_price = number_format($this->calculateTotalPrice($cart), 2, '.', '');
        });

        return $carts;
    }

    /**
     * @return array
     */
    public function getCartContents(): array
    {
        $user = auth('api')->user();
        $cart = $this->findOrCreateCart($user);
        $cart->load('items.gallery.images');

        $totalPrice = $this->calculateTotalPrice($cart);

        return [
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price,
                    'quantity' => $item->pivot->quantity,
                    'image_path' => $item->image_path,
                    'gallery' => $item->gallery ? [
                        'id' => $item->gallery->id,
                        'name' => $item->gallery->name,
                        'images' => $item->gallery->images->map(function ($image) {
                            return [
                                'id' => $image->id,
                                'path' => $image->path,
                                'caption' => $image->caption,
                            ];
                        })->toArray(),
                    ] : null,
                ];
            })->toArray(),
            'total_price' => number_format($totalPrice, 2, '.', ''),
        ];
    }

    // ====== Items ======

    /**
     * @param int $dishId
     * @param int $quantity
     * @return string
     */
    public function addItem(int $dishId, int $quantity): string
    {
        $user = auth('api')->user();
        $cart = Cart::where('user_id', $user->id)->first();


        $existingItem = $cart->items()->where('dish_id', $dishId)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->pivot->quantity + $quantity;
            $cart->items()->updateExistingPivot($dishId, ['quantity' => $newQuantity]);
            return 'Item quantity in the cart updated.';
        }

        $cart->items()->attach($dishId, ['quantity' => $quantity]);
        return 'Item added to the cart.';
    }

    /**
     * @param int $dishId
     * @param int $quantity
     * @return bool
     */
    public function updateItem(int $dishId, int $quantity): bool
    {
        $user = auth('api')->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart->items()->where('dish_id', $dishId)->exists()) {
            return false;
        }

        $cart->items()->updateExistingPivot($dishId, ['quantity' => $quantity]);
        return true;
    }

    /**
     * @param int $dishId
     * @return bool
     */
    public function removeItem(int $dishId): bool
    {
        $user = auth('api')->user();
        $cart = Cart::where('user_id', $user->id)->first();

        $detachedCount = $cart->items()->detach($dishId);

        return $detachedCount > 0;
    }

    // ===== Clear Cart =====

    /**
     * @return void
     */
    public function clear(): void
    {
        $user = auth('api')->user();
        $cart = $this->findOrCreateCart($user);
        $cart->items()->detach();
    }
}

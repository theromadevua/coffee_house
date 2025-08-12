<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DishInOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // Используем `whenPivotLoaded`, чтобы избежать ошибок, если pivot не был загружен
            'quantity' => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->quantity;
            }),
            // Важно брать цену из pivot, так как она могла измениться с момента заказа
            'price_at_order' => $this->whenPivotLoaded('order_items', function () {
                return (float) $this->pivot->price;
            }),
            'subtotal' => $this->whenPivotLoaded('order_items', function () {
                return (float) $this->pivot->price * $this->pivot->quantity;
            }),
        ];
    }
}
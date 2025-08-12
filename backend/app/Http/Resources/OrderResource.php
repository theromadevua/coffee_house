<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount, 
            'table_id' => $this->table_id,
            'reservation_time' => $this->reservation_time,
            'created_at' => $this->created_at->toDateTimeString(),

            // `whenLoaded` гарантирует, что 'items' будут добавлены только если они были загружены с помощью with('items')
            'items' => DishInOrderResource::collection($this->whenLoaded('items')), 
        ];
    }
}
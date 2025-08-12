<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreDishRequest",
 *     title="Store Dish Request",
 *     description="Схема запроса для создания нового блюда",
 *     required={"name", "price", "category_id"},
 *     @OA\Property(property="name", type="string", description="Название блюда", example="Стейк Рибай"),
 *     @OA\Property(property="description", type="string", description="Описание блюда", example="Сочный стейк из мраморной говядины"),
 *     @OA\Property(property="price", type="number", format="float", description="Цена блюда", example=1200.00),
 *     @OA\Property(property="category_id", type="integer", description="ID категории", example=2),
 *     @OA\Property(property="image", type="string", format="binary", description="Файл изображения блюда")
 * )
 */

class StoreDishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:18048',
            'price' => 'required|numeric|min:0',
        ];
    }
}
<?php

namespace App\Http\Controllers\Swagger\Schemas;

use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="Dish",
 *     title="Dish",
 *     description="Схема модели Блюда",
 *     @OA\Property(property="id", type="integer", format="int64", description="ID блюда", example=1),
 *     @OA\Property(property="name", type="string", description="Название блюда", example="Борщ"),
 *     @OA\Property(property="description", type="string", description="Описание блюда", example="Классический украинский борщ с пампушками"),
 *     @OA\Property(property="price", type="number", format="float", description="Цена блюда", example=250.50),
 *     @OA\Property(property="category_id", type="integer", description="ID категории", example=1),
 *     @OA\Property(property="image_url", type="string", format="url", description="URL изображения блюда", example="http://example.com/images/borsch.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Дата создания"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Дата последнего обновления")
 * )
 */

/**
 * @OA\Schema(
 *     schema="UpdateDishRequest",
 *     title="Update Dish Request",
 *     description="Схема запроса для обновления существующего блюда",
 *     @OA\Property(property="name", type="string", description="Новое название блюда", example="Стейк Рибай (Prime)"),
 *     @OA\Property(property="description", type="string", description="Новое описание блюда", example="Сочный стейк из премиальной мраморной говядины"),
 *     @OA\Property(property="price", type="number", format="float", description="Новая цена блюда", example=1500.00),
 *     @OA\Property(property="category_id", type="integer", description="Новый ID категории", example=2),
 *     @OA\Property(property="image", type="string", format="binary", description="Новый файл изображения блюда (опционально)")
 * )
 */

/**
 * @OA\Schema(
 *      schema="MessageResponse",
 *      title="Message Response",
 *      description="Схема для ответа с сообщением (например, при ошибках)",
 *      @OA\Property(property="message", type="string", description="Текст сообщения", example="Запись не найдена.")
 * )
 */
class DishSchema {}
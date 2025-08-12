<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *      schema="CategoryRequest",
 *      title="Category Request",
 *      description="Schema for creating or updating a category",
 *      type="object",
 *      required={"name"},
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          maxLength=255,
 *          description="The name of the category",
 *          example="Appetizers"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          nullable=true,
 *          description="A brief description of the category",
 *          example="Starters and small bites to begin the meal."
 *      )
 * )
 */
class CategoryRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}

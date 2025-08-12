<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'delivery_address' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_phone' => ['sometimes', 'required', 'string', 'max:20'],
            'delivery_time' => ['sometimes', 'nullable', 'date'],

            'status' => ['sometimes', 'required', new Enum(OrderStatus::class)],

            'items' => ['sometimes', 'required', 'array', 'min:1'],
            'items.*.dish_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:dishes,id'
            ],
            'items.*.quantity' => ['sometimes', 'required', 'integer', 'min:1'],
        ];
    }
}
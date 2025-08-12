<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrderRequest extends FormRequest
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
            'delivery_address' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'delivery_time' => ['nullable', 'date', 'after_or_equal:now'],
            'total_amount' => ['required', 'numeric'],
            'items' => ['required', 'array', 'min:1'],
            'status' => '',
            'items.*.dish_id' => [
                'required',
                'integer',
                'exists:dishes,id'
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:30'],
        ];
    }
}

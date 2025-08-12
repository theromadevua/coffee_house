<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class SearchDishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'searchParams' => [],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


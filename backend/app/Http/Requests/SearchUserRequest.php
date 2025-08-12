<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'searchParams' => ['required', 'string', 'min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


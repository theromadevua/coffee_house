<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|file|image|max:15120',
        ];
    }
}

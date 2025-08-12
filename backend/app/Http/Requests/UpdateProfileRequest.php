<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateProfileRequest",
 *     title="Update Profile Request",
 *     description="Data for updating a user's profile. All fields are optional.",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="User's new name (optional)",
 *         example="Johnathan Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User's new email address (optional, must be unique)",
 *         example="johnathan.d@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="New password (optional, must be at least 8 characters and confirmed)",
 *         example="new_secure_password"
 *     ),
 *     @OA\Property(
 *         property="password_confirmation",
 *         type="string",
 *         format="password",
 *         description="Confirmation for the new password (required if password is provided)",
 *         example="new_secure_password"
 *     )
 * )
 */

class UpdateProfileRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => 'sometimes|string|max:255',
            
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            
            'password' => 'sometimes|string|min:8|confirmed',

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:18048',
        ];
    }
}
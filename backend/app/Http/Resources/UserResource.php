<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="Represents a user in the system",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="User's unique identifier",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="User's name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User's email address",
 *         example="john.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         description="User's role",
 *         example="user"
 *     ),
 *      @OA\Property(
 *         property="image_path",
 *         type="string",
 *         description="Image path",
 *         example="path"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp of user creation",
 *         example="2023-10-27T10:00:00.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp of last user update",
 *         example="2023-10-27T10:05:00.000000Z"
 *     )
 * )
 */

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->value,
            'gallery' => $this->gallery,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
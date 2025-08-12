<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TokenResource",
 *     title="Token Resource",
 *     description="Represents the authentication token data",
 *     @OA\Property(
 *         property="access_token",
 *         type="string",
 *         description="The JWT access token",
 *         example="eyJhbGciOiJIU..."
 *     ),
 *     @OA\Property(
 *         property="token_type",
 *         type="string",
 *         description="The type of token",
 *         example="Bearer"
 *     ),
 *     @OA\Property(
 *         property="expires_in",
 *         type="integer",
 *         description="The token's lifetime in seconds",
 *         example=3600
 *     )
 * )
 */

class TokenResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this['access_token'],
            'token_type' => $this['token_type'],
            'expires_in' => $this['expires_in'],
        ];
    }
}

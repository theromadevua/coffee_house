<?php

namespace App\Http\Controllers\Swagger\Schemas;

use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="MessageResponse",
 *     type="object",
 *     title="Message Response",
 *     description="Response with a message",
 *     @OA\Property(property="message", type="string", example="Hi, everybody!")
 * )
 */

class MessageResponse {}

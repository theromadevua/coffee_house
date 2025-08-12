<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Restaurant API Documentation",
 *      description="This is the API documentation for the server built with Laravel. It describes the endpoints used to manage dishes.",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://coffee.test/",
 *      description="Primary API Server"
 * )
 *
 * @OA\Tag(
 *     name="Dishes",
 *     description="API Endpoints for Dish Management"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to receive an authentication token",
 *     name="Token Based Authentication",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
abstract class Controller
{
    //
}

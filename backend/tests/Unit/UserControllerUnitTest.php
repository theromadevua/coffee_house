<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Services\UserService;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerUnitTest extends TestCase
{
    protected $userService;
    protected $userController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = Mockery::mock(UserService::class);
        $this->userController = new UserController($this->userService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_list_users_returns_success_response()
    {
        $users = new Collection([
            new User(['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com']),
            new User(['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com']),
        ]);

        $this->userService
            ->shouldReceive('listUsers')
            ->once()
            ->andReturn($users);

        $response = $this->userController->listUsers();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $users->toArray(),
            'message' => 'Users retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_create_user_returns_success_response()
    {
        $requestData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => Role::USER,
        ];

        $user = new User([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => Role::USER,
        ]);

        $request = Mockery::mock(\App\Http\Requests\StoreUserRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($requestData);

        $this->userService
            ->shouldReceive('createUser')
            ->once()
            ->with($requestData)
            ->andReturn($user);

        $response = $this->userController->createUser($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals([
            'data' => $user->toArray(),
            'message' => 'User created successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_find_user_returns_success_response()
    {
        $userId = 1;
        $user = new User([
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => Role::USER,
        ]);

        $this->userService
            ->shouldReceive('findUser')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $response = $this->userController->findUser($userId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $user->toArray(),
            'message' => 'User retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_update_user_returns_success_response()
    {
        $userId = 1;
        $requestData = [
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
        ];

        $user = new User([
            'id' => $userId,
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'role' => Role::USER,
        ]);

        $request = Mockery::mock(\App\Http\Requests\UpdateUserRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($requestData);

        $this->userService
            ->shouldReceive('updateUser')
            ->once()
            ->with($userId, $requestData)
            ->andReturn($user);

        $response = $this->userController->updateUser($request, $userId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $user->toArray(),
            'message' => 'User updated successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_delete_user_returns_success_response()
    {
        $userId = 1;

        $this->userService
            ->shouldReceive('deleteUser')
            ->once()
            ->with($userId)
            ->andReturn(true);

        $response = $this->userController->deleteUser($userId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals([
            'data' => null,
            'message' => 'User deleted successfully.',
            'success' => true,
        ], $response->getData(true));
    }
}

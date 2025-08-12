<?php

namespace Tests\Unit\Http\Controllers;

use App\Enums\Role;
use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchUserRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthControllerUnitTest extends TestCase
{
    protected $mockService;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockService = Mockery::mock(AuthService::class);
        $this->controller = new AuthController($this->mockService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register_returns_expected_response()
    {
        $expectedCredentials = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ];

        $mockRequest = Mockery::mock(RegisterRequest::class);
        $mockRequest->shouldReceive('validated')
            ->once()
            ->andReturn($expectedCredentials);

        $user = User::factory()->make([
            'name' => $expectedCredentials['name'],
            'email' => $expectedCredentials['email'],
            'password' => Hash::make($expectedCredentials['password']),
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('register')
            ->once()
            ->with($expectedCredentials)
            ->andReturn($user);

        $response = $this->controller->register($mockRequest);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('User registered successfully', $decoded['message']);
        $this->assertEquals($user->email, $decoded['data']['email']);
    }

    public function test_login_returns_expected_response()
    {
        $credentials = [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ];

        $mockRequest = Mockery::mock(LoginRequest::class);
        $mockRequest->shouldReceive('validated')
            ->once()
            ->andReturn($credentials);

        $result = [
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];

        $this->mockService->shouldReceive('login')
            ->once()
            ->with($credentials)
            ->andReturn($result);

        $response = $this->controller->login($mockRequest);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Login successful', $decoded['message']);
        $this->assertEquals($result['access_token'], $decoded['data']['access_token']);
        $this->assertEquals('refresh_token', $response->headers->getCookies()[0]->getName());
        $this->assertEquals($result['refresh_token'], $response->headers->getCookies()[0]->getValue());
    }

    public function test_refresh_returns_expected_response()
    {
        $refreshToken = 'refresh-token';
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('cookie')
            ->once()
            ->with('refresh_token')
            ->andReturn($refreshToken);

        $newTokens = [
            'access_token' => 'new-access-token',
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];

        $this->mockService->shouldReceive('refresh')
            ->once()
            ->with($refreshToken)
            ->andReturn($newTokens);

        $response = $this->controller->refresh($mockRequest);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Token refreshed successfully', $decoded['message']);
        $this->assertEquals($newTokens['access_token'], $decoded['data']['access_token']);
    }

    public function test_logout_returns_expected_response()
    {
        $this->mockService->shouldReceive('logout')
            ->once()
            ->andReturnNull();

        $response = $this->controller->logout();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Successfully logged out', $decoded['message']);
        $this->assertNull($decoded['data']);
        $this->assertNotEmpty($response->headers->getCookies());
        $this->assertEquals('refresh_token', $response->headers->getCookies()[0]->getName());
        $this->assertNull($response->headers->getCookies()[0]->getValue());
    }

    public function test_me_returns_expected_response()
    {
        $user = User::factory()->make([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('me')
            ->once()
            ->andReturn($user);

        $response = $this->controller->me();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('User data retrieved successfully', $decoded['message']);
        $this->assertEquals($user->email, $decoded['data']['email']);
    }

    public function test_update_profile_returns_expected_response()
    {
        $credentials = [
            'name' => 'Jane Doe Updated',
            'email' => 'jane.updated@example.com',
        ];

        $images = [
            UploadedFile::fake()->image('image.jpg')
        ];

        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('only')
            ->once()
            ->with(['name', 'email', 'password'])
            ->andReturn($credentials);
        $mockRequest->shouldReceive('file')
            ->once()
            ->with('images')
            ->andReturn($images);

        $user = User::factory()->make([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('updateProfile')
            ->once()
            ->with($credentials, $images)
            ->andReturn($user);

        $response = $this->controller->updateProfile($mockRequest);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Profile updated successfully', $decoded['message']);
        $this->assertEquals($user->email, $decoded['data']['email']);
    }

    public function test_search_returns_expected_response()
    {
        $searchParams = 'Jane';
        $mockRequest = Mockery::mock(SearchUserRequest::class);
        $mockRequest->shouldReceive('query')
            ->once()
            ->with('searchParams', '')
            ->andReturn($searchParams);

        $users = User::factory()->count(2)->make([
            'name' => 'Jane Doe',
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('searchUsers')
            ->once()
            ->with($searchParams)
            ->andReturn($users);

        $response = $this->controller->search($mockRequest);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Users retrieved successfully', $decoded['message']);
        $this->assertCount(2, $decoded['data']);
    }

    public function test_promote_to_admin_returns_expected_response()
    {
        $user = User::factory()->make([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => Role::USER,
        ]);

        $updatedUser = User::factory()->make([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => Role::ADMIN,
        ]);

        $this->mockService->shouldReceive('promoteToAdmin')
            ->once()
            ->with($user)
            ->andReturn($updatedUser);

        $response = $this->controller->promoteToAdmin($user);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('User promoted to admin successfully', $decoded['message']);
        $this->assertEquals(Role::ADMIN->value, $decoded['data']['role']);
    }

    public function test_add_image_returns_expected_response()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('file')
            ->once()
            ->with('image')
            ->andReturn($image);

        $user = User::factory()->make([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('addImage')
            ->once()
            ->withArgs(function ($file) {
                return $file instanceof \Illuminate\Http\Testing\File;
            })
            ->andReturn($user);

        $response = $this->controller->addImage($mockRequest);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('User updated successfully', $decoded['message']);
        $this->assertEquals($user->email, $decoded['data']['email']);
    }

    public function test_delete_image_returns_expected_response()
    {
        $imageId = 1;
        $user = User::factory()->make([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => Role::USER,
        ]);

        $this->mockService->shouldReceive('deleteImage')
            ->once()
            ->with($imageId)
            ->andReturn($user);

        $response = $this->controller->deleteImage($imageId);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $decoded = json_decode($response->getContent(), true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('Image deleted successfully', $decoded['message']);
        $this->assertEquals(null, $decoded['data']);
    }
}

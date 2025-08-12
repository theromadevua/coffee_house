<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\CategoryController;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use ApiResponse;

    protected $categoryService;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryService = Mockery::mock(CategoryService::class);
        $this->controller = new CategoryController($this->categoryService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_categories_returns_success_response()
    {
        $searchParams = 'test';
        $categories = new Collection([new Category(['id' => 1, 'name' => 'Test Category'])]);

        $request = Request::create('/categories', 'GET', ['searchParams' => $searchParams]);

        $this->categoryService
            ->shouldReceive('getAllCategories')
            ->once()
            ->with($searchParams)
            ->andReturn($categories);

        $response = $this->controller->getCategories($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Successful categories request', $responseData['message']);
        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('id', $responseData['data'][0]);
        $this->assertArrayHasKey('name', $responseData['data'][0]);
    }

    public function test_get_category_by_id_returns_success_response()
    {
        $category = new Category(['id' => 1, 'name' => 'Test Category']);

        $response = $this->controller->getCategoryById($category);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Successful category request', $responseData['message']);
        $this->assertArrayHasKey('id', $responseData['data']);
        $this->assertArrayHasKey('name', $responseData['data']);
    }

    public function test_get_category_by_id_returns_not_found_response()
    {
        // Instead of mocking errorResponse, we pass null to simulate not found
        // We use reflection to bypass the type hint for testing purposes
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('getCategoryById');
        $method->setAccessible(true);

        $response = $method->invoke($this->controller, null);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Category not found', $responseData['message']);
    }

    public function test_create_category_returns_success_response()
    {
        $categoryData = ['name' => 'New Category', 'description' => 'Test Description'];
        $category = new Category($categoryData);

        $request = Mockery::mock(CategoryRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($categoryData);

        $this->categoryService
            ->shouldReceive('createCategory')
            ->once()
            ->with($request)
            ->andReturn($category);

        $response = $this->controller->createCategory($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Successful category creation', $responseData['message']);
        $this->assertArrayHasKey('name', $responseData['data']);
        $this->assertArrayHasKey('description', $responseData['data']);
    }

    public function test_update_category_returns_success_response()
    {
        $id = 1;
        $categoryData = ['name' => 'Updated Category', 'description' => 'Updated Description'];
        $category = new Category($categoryData);

        $request = Mockery::mock(CategoryRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($categoryData);

        $this->categoryService
            ->shouldReceive('updateCategory')
            ->once()
            ->with($request, $id)
            ->andReturn($category);

        $response = $this->controller->updateCategory($request, $id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Successful category update', $responseData['message']);
        $this->assertArrayHasKey('name', $responseData['data']);
        $this->assertArrayHasKey('description', $responseData['data']);
    }

    public function test_delete_category_returns_success_response()
    {
        $id = 1;

        $this->categoryService
            ->shouldReceive('deleteCategory')
            ->once()
            ->with($id)
            ->andReturn(null);

        $response = $this->controller->deleteCategory($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertEquals('Category successfully deleted', $responseData['message']);
    }
}

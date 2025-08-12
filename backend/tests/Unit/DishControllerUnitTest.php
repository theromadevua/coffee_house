<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\DishController;
use App\Http\Requests\SearchDishRequest;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Models\Dish;
use App\Services\DishService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DishControllerUnitTest extends TestCase
{
    protected $dishService;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->dishService = Mockery::mock(DishService::class);
        $this->controller = new DishController($this->dishService);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_paginated_dishes()
    {
        $request = Mockery::mock(Request::class);
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $request->shouldReceive('query')
            ->with('category')
            ->andReturn('1');
        $request->shouldReceive('query')
            ->with('searchParams', '')
            ->andReturn('pizza');
        $request->shouldReceive('query')
            ->with('per_page', 8)
            ->andReturn(8);

        $this->dishService->shouldReceive('getDishes')
            ->with('1', 'pizza', 8)
            ->andReturn($paginator);

        // Expect paginator to serialize to an array
        $paginator->shouldReceive('jsonSerialize')
            ->andReturn(['data' => [], 'total' => 0]);

        $response = $this->controller->index($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => ['data' => [], 'total' => 0],
            'message' => 'Dishes successfully retrieved',
            'success' => true
        ], $response->getData(true));
    }

    public function test_store_creates_dish_and_returns_created_response()
    {
        $request = Mockery::mock(StoreDishRequest::class);
        $dish = Mockery::mock(Dish::class);
        $data = [
            'name' => 'Margherita',
            'description' => 'Classic pizza',
            'price' => 10.99,
            'category_id' => 1
        ];
        $serializedDish = [
            'id' => 1,
            'name' => 'Margherita',
            'gallery' => ['images' => []]
        ];

        $request->shouldReceive('validated')
            ->andReturn($data);
        $request->shouldReceive('file')
            ->with('images')
            ->andReturn([]);
        $request->shouldReceive('input')
            ->with('gallery_name')
            ->andReturn('Pizza Gallery');

        $this->dishService->shouldReceive('createDish')
            ->with($data, [], 'Pizza Gallery')
            ->andReturn($dish);

        $dish->shouldReceive('load')
            ->with('gallery.images')
            ->andReturnSelf();
        $dish->shouldReceive('getAttribute')
            ->with('gallery')
            ->andReturn((object)['images' => []]);
        $dish->shouldReceive('jsonSerialize')
            ->andReturn($serializedDish);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals([
            'data' => $serializedDish,
            'message' => 'The dish is successfully created',
            'success' => true
        ], $response->getData(true));
    }

    public function test_update_modifies_dish_and_returns_ok_response()
    {
        $request = Mockery::mock(UpdateDishRequest::class);
        $dish = Mockery::mock(Dish::class);
        $data = [
            'name' => 'Updated Margherita',
            'description' => 'Updated classic pizza',
            'price' => 11.99,
            'category_id' => 1
        ];
        $id = 1;
        $serializedDish = [
            'id' => 1,
            'name' => 'Updated Margherita',
            'gallery' => ['images' => []]
        ];

        $request->shouldReceive('only')
            ->with(['name', 'description', 'price', 'category_id'])
            ->andReturn($data);
        $request->shouldReceive('file')
            ->with('images', [])
            ->andReturn([]);
        $request->shouldReceive('input')
            ->with('gallery_name')
            ->andReturn('Updated Gallery');

        $this->dishService->shouldReceive('updateDish')
            ->with($id, $data, [], 'Updated Gallery')
            ->andReturn($dish);

        $dish->shouldReceive('load')
            ->with('gallery.images')
            ->andReturnSelf();
        $dish->shouldReceive('getAttribute')
            ->with('gallery')
            ->andReturn((object)['images' => []]);
        $dish->shouldReceive('jsonSerialize')
            ->andReturn($serializedDish);

        $response = $this->controller->update($request, $id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $serializedDish,
            'message' => 'The dish is successfully updated',
            'success' => true
        ], $response->getData(true));
    }

    public function test_show_returns_specific_dish()
    {
        $id = 1;
        $dish = Mockery::mock(Dish::class);
        $serializedDish = ['id' => 1, 'name' => 'Margherita'];

        $this->dishService->shouldReceive('getDishById')
            ->with($id)
            ->andReturn($dish);

        $dish->shouldReceive('jsonSerialize')
            ->andReturn($serializedDish);

        $response = $this->controller->show($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $serializedDish,
            'message' => 'The dish is successfully retrieved',
            'success' => true
        ], $response->getData(true));
    }

    public function test_search_returns_matching_dishes()
    {
        $request = Mockery::mock(SearchDishRequest::class);
        $dishes = Mockery::mock(EloquentCollection::class);
        $serializedDishes = [['id' => 1], ['id' => 2]];

        $request->shouldReceive('query')
            ->with('searchParams', '')
            ->andReturn('pizza');

        $this->dishService->shouldReceive('search')
            ->with('pizza')
            ->andReturn($dishes);

        $dishes->shouldReceive('jsonSerialize')
            ->andReturn($serializedDishes);

        $response = $this->controller->search($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $serializedDishes,
            'message' => 'Dishes successfully retrieved',
            'success' => true
        ], $response->getData(true));
    }

    public function test_destroy_deletes_dish_and_returns_no_content()
    {
        $id = 1;

        $this->dishService->shouldReceive('deleteDish')
            ->with($id)
            ->once();

        $response = $this->controller->destroy($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals([
            'data' => null,
            'message' => 'The dish is successfully deleted',
            'success' => true
        ], $response->getData(true));
    }
}

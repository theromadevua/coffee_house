<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\CartController;
use App\Http\Requests\AddItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\Dish;
use App\Services\CartService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class CartControllerUnitTest extends TestCase
{
    use ApiResponse;

    protected CartService $cartService;
    protected CartController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = Mockery::mock(CartService::class);
        $this->controller = new CartController($this->cartService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_show_returns_cart_contents()
    {
        $cartData = ['items' => [], 'total_price' => '0.00'];
        $this->cartService->shouldReceive('getCartContents')
            ->once()
            ->andReturn($cartData);

        $response = $this->controller->show();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => $cartData,
            'message' => 'Cart details retrieved successfully.'
        ], $response->getData(true));
    }

    public function test_get_all_carts_returns_all_carts()
    {
        $cartData = new EloquentCollection([
            new Cart(['id' => 1]),
            new Cart(['id' => 2]),
        ]);
        $this->cartService->shouldReceive('getAllCarts')
            ->once()
            ->andReturn($cartData);

        $response = $this->controller->getAllCarts();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => $cartData->toArray(),
            'message' => 'Cart details retrieved successfully.'
        ], $response->getData(true));
    }

    public function test_get_cart_by_id_returns_cart()
    {
        $cartId = '1';
        $cartData = new Cart(['id' => $cartId, 'total_price' => '10.00']);
        $this->cartService->shouldReceive('getCartById')
            ->once()
            ->with($cartId)
            ->andReturn($cartData);

        $response = $this->controller->getCartById($cartId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => $cartData->toArray(),
            'message' => 'Cart received successfully.'
        ], $response->getData(true));
    }

    public function test_add_item_success()
    {
        $request = Mockery::mock(AddItemRequest::class);
        $validatedData = ['dish_id' => 1, 'quantity' => 2];
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $this->cartService->shouldReceive('addItem')
            ->once()
            ->with($validatedData['dish_id'], $validatedData['quantity'])
            ->andReturn('Item added to the cart.');

        $response = $this->controller->addItem($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => null,
            'message' => 'Item added to the cart.'
        ], $response->getData(true));
    }

    public function test_update_item_success()
    {
        $request = Mockery::mock(UpdateCartItemRequest::class);
        $dish = Mockery::mock(Dish::class);
        $dishId = 1;
        $dish->shouldReceive('getAttribute')->with('id')->andReturn($dishId);
        $validatedData = ['quantity' => 3];

        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $this->cartService->shouldReceive('updateItem')
            ->once()
            ->with($dishId, $validatedData['quantity'])
            ->andReturn(true);

        $response = $this->controller->updateItem($request, $dish);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => null,
            'message' => 'Item quantity updated successfully.'
        ], $response->getData(true));
    }

    public function test_update_item_not_found()
    {
        $request = Mockery::mock(UpdateCartItemRequest::class);
        $dish = Mockery::mock(Dish::class);
        $dishId = 1;
        $dish->shouldReceive('getAttribute')->with('id')->andReturn($dishId);
        $validatedData = ['quantity' => 3];

        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $this->cartService->shouldReceive('updateItem')
            ->once()
            ->with($dishId, $validatedData['quantity'])
            ->andReturn(false);

        $response = $this->controller->updateItem($request, $dish);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals([
            'success' => false,
            'message' => 'Item not found in the cart.',
            'data' => null
        ], $response->getData(true));
    }

    public function test_remove_item_success()
    {
        $dishId = 1;
        $this->cartService->shouldReceive('removeItem')
            ->once()
            ->with($dishId)
            ->andReturn(true);

        $response = $this->controller->removeItem($dishId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => null,
            'message' => 'Item removed from the cart.'
        ], $response->getData(true));
    }

    public function test_remove_item_not_found()
    {
        $dishId = 1;
        $this->cartService->shouldReceive('removeItem')
            ->once()
            ->with($dishId)
            ->andReturn(false);

        $response = $this->controller->removeItem($dishId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals([
            'success' => false,
            'message' => 'Item not found in the cart.',
            'data' => null
        ], $response->getData(true));
    }

    public function test_clear_cart()
    {
        $this->cartService->shouldReceive('clear')
            ->once()
            ->andReturn(null);

        $response = $this->controller->clear();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'success' => true,
            'data' => null,
            'message' => 'Cart cleared successfully.'
        ], $response->getData(true));
    }
}

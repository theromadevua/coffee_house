<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\User; 
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase; 
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;


class CartControllerTest extends TestCase
{
    // use RefreshDatabase;

    // protected $cartService;
    // protected User $user; 

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     $this->cartService = $this->mock(CartService::class);

    //     $this->user = User::factory()->create();
    //     $this->actingAs($this->user);
    // }

    // // /** @test */
    // public function show_returns_cart_contents()
    // {
    //     $cartData = ['items' => [], 'total' => 0];
    //     $this->cartService->shouldReceive('getCartContents')
    //         ->once()
    //         ->andReturn($cartData);

    //     $response = $this->getJson('/api/cart'); 

    //     $response->assertOk() 
    //         ->assertJson([
    //             'data' => $cartData,
    //             'message' => 'Cart details retrieved successfully.',
    //             'success' => true
    //         ]);
    // }

    // /** @test */
    // public function add_item_successfully()
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt(["email"=> $user->email, 'password' => 'password']);

    //     $category = Category::factory()->create();
    //     $dish = Dish::factory()->create(['category_id' => $category->id]);
    //     $quantity = 2;

    //     $this->cartService->shouldReceive('addItem')
    //         ->with($dish->id, $quantity)
    //         ->once()
    //         ->andReturn('Item added to cart successfully.');

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->postJson('/api/cart', [
    //             'dish_id' => $dish->id,
    //             'quantity' => $quantity
    //         ]);

    //     if ($response->status() !== 200) {
    //         dd($response->json());
    //     }

    //     $response->assertOk()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Item added to cart successfully.',
    //             'success' => true
    //         ]);
    // }

    // /** @test */
    // public function update_item_successfully()
    // {
    //     $category = Category::factory()->create();

    //     $dish = Dish::factory()->create(['category_id' => $category->id]);

    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt([
    //         'email' => $user->email,
    //         'password' => 'password', 
    //     ]);

    //     if (!$token) {
    //         $this->fail('Failed to generate JWT token');
    //     }

    //     $newQuantity = 3;

    //     $this->cartService->shouldReceive('addItem')
    //         ->andReturn(true);

    //     $this->cartService->shouldReceive('updateItem')
    //         ->with($dish->id, $newQuantity)
    //         ->once()
    //         ->andReturn(true);

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->putJson("/api/cart/{$dish->id}", [
    //             'quantity' => $newQuantity
    //         ]);

    //     if ($response->status() !== 200) {
    //         dd($response->json());
    //     }

    //     $response->assertOk()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Item quantity updated successfully.',
    //             'success' => true
    //         ]);
    // }

    // /** @test */
    // public function update_item_fails_when_not_in_cart()
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt([
    //         'email' => $user->email,
    //         'password' => 'password', 
    //     ]);

    //     if (!$token) {
    //         $this->fail('Failed to generate JWT token');
    //     }


    //     $category = Category::factory()->create();

    //     $dish = Dish::factory()->create([
    //         'category_id' => $category->id
    //     ]);

    //     $newQuantity = 3;

    //     $this->cartService->shouldReceive('updateItem')
    //         ->with($dish->id, $newQuantity)
    //         ->once()
    //         ->andReturn(false); 

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->putJson("/api/cart/{$dish->id}", [
    //             'quantity' => $newQuantity
    //         ]);
        
    //     $response->assertNotFound()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Item not found in the cart.',
    //             'success' => false
    //         ]);
    // }

    // /** @test */
    // public function remove_item_successfully()
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt([
    //         'email' => $user->email,
    //         'password' => 'password', 
    //     ]);

    //     if (!$token) {
    //         $this->fail('Failed to generate JWT token');
    //     }

    //     $category = Category::factory()->create();
    //     $dish = Dish::factory()->create(['category_id' => $category->id]);

    //     $this->cartService->shouldReceive('removeItem')
    //         ->with($dish->id)
    //         ->once()
    //         ->andReturn(true);

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->deleteJson("/api/cart/{$dish->id}"); 
        

    //     $response->assertOk()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Item removed from the cart.',
    //             'success' => true
    //         ]);
    // }

    // /** @test */
    // public function remove_item_fails_when_not_in_cart()
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt([
    //         'email' => $user->email,
    //         'password' => 'password', 
    //     ]);

    //     if (!$token) {
    //         $this->fail('Failed to generate JWT token');
    //     }

    //     $category = Category::factory()->create();
    //     $dish = Dish::factory()->create(['category_id' => $category->id]);

    //     $this->cartService->shouldReceive('removeItem')
    //         ->with($dish->id)
    //         ->once()
    //         ->andReturn(false);

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->deleteJson("/api/cart/{$dish->id}"); 

    //     $response->assertNotFound()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Item not found in the cart.',
    //             'success' => false
    //         ]);
    // }

    // /** @test */
    // public function clear_cart_successfully()
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::attempt([
    //         'email' => $user->email,
    //         'password' => 'password', 
    //     ]);

    //     if (!$token) {
    //         $this->fail('Failed to generate JWT token');
    //     }

    //     $this->cartService->shouldReceive('clear')
    //         ->once();

    //     $response = $this->withHeaders(['Authorization' => "Bearer $token"])
    //         ->deleteJson("/api/cart"); 

    //     $response->assertOk()
    //         ->assertJson([
    //             'data' => null,
    //             'message' => 'Cart cleared successfully.',
    //             'success' => true
    //     ]);
    // }
}
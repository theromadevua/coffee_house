<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\OrderController;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Services\OrderService;
use App\Events\OrderCreated;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderControllerUnitTest extends TestCase
{
    protected $orderService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = Mockery::mock(OrderService::class);
        $this->controller = new OrderController($this->orderService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_my_orders_returns_success_response()
    {
        $orders = new Collection([
            new Order(['id' => 1, 'delivery_address' => '123 Test St']),
            new Order(['id' => 2, 'delivery_address' => '456 Test St']),
        ]);
        $this->orderService->shouldReceive('getMyOrders')
            ->once()
            ->andReturn($orders);

        $response = $this->controller->getMyOrders();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $orders->toArray(),
            'message' => 'Orders retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_get_orders_statistics_returns_success_response()
    {
        $statistics = [
            'total_dishes_ordered' => 100,
            'dish_popularity' => [],
            'top_users' => [],
            'orders_by_day' => [],
        ];
        $this->orderService->shouldReceive('getOrdersStatistics')
            ->once()
            ->andReturn($statistics);

        $response = $this->controller->getOrdersStatistics();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $statistics,
            'message' => 'Order Statistics retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_get_all_orders_returns_success_response()
    {
        $orders = new Collection([
            new Order(['id' => 1, 'delivery_address' => '123 Test St']),
            new Order(['id' => 2, 'delivery_address' => '456 Test St']),
        ]);
        $this->orderService->shouldReceive('getAllOrders')
            ->once()
            ->andReturn($orders);

        $response = $this->controller->getAllOrders();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $orders->toArray(),
            'message' => 'Orders retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_create_order_triggers_event_and_returns_success_response()
    {
        Event::fake();

        $request = Mockery::mock(StoreOrderRequest::class);
        $validatedData = [
            'delivery_address' => '123 Test St',
            'contact_phone' => '1234567890',
            'total_amount' => 50.00,
            'items' => [['dish_id' => 1, 'quantity' => 2]],
        ];
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $order = new Order(['id' => 1, 'delivery_address' => '123 Test St']);
        $this->orderService->shouldReceive('createOrder')
            ->once()
            ->with($validatedData)
            ->andReturn($order);

        $response = $this->controller->createOrder($request);

        Event::assertDispatched(OrderCreated::class, function ($event) use ($order) {
            return $event->order === $order;
        });

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals([
            'data' => $order->toArray(),
            'message' => 'Order created successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_find_order_returns_success_response()
    {
        $orderId = 1;
        $order = new Order(['id' => 1, 'delivery_address' => '123 Test St']);
        $this->orderService->shouldReceive('findOrder')
            ->once()
            ->with($orderId)
            ->andReturn($order);

        $response = $this->controller->findOrder($orderId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $order->toArray(),
            'message' => 'Order retrieved successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_update_order_returns_success_response()
    {
        $orderId = 1;
        $request = Mockery::mock(UpdateOrderRequest::class);
        $validatedData = [
            'delivery_address' => '456 Updated St',
            'contact_phone' => '0987654321',
        ];
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $order = new Order(['id' => 1, 'delivery_address' => '456 Updated St']);
        $this->orderService->shouldReceive('updateOrder')
            ->once()
            ->with($orderId, $validatedData)
            ->andReturn($order);

        $response = $this->controller->updateOrder($request, $orderId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $order->toArray(),
            'message' => 'Order updated successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_update_order_status_returns_success_response()
    {
        $orderId = 1;
        $request = Mockery::mock(UpdateOrderStatusRequest::class);
        $validatedData = ['status' => 'delivered'];
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $order = new Order(['id' => 1, 'status' => 'delivered']);
        $this->orderService->shouldReceive('updateOrderStatus')
            ->once()
            ->with($orderId, $validatedData)
            ->andReturn($order);

        $response = $this->controller->updateOrderStatus($request, $orderId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals([
            'data' => $order->toArray(),
            'message' => 'Order status updated successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_delete_order_returns_no_content_response()
    {
        $orderId = 1;
        $this->orderService->shouldReceive('deleteOrder')
            ->once()
            ->with($orderId)
            ->andReturn(null);

        $response = $this->controller->deleteOrder($orderId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals([
            'data' => null,
            'message' => 'Order deleted successfully.',
            'success' => true,
        ], $response->getData(true));
    }

    public function test_download_order_pdf_returns_pdf_response()
    {
        $orderId = 1;
        $order = new Order([
            'id' => 1,
            'delivery_address' => '123 Test St',
            'contact_phone' => '1234567890',
            'total_amount' => 50.00,
            'status' => 'processing',
        ]);

        $order->setRelation('items', collect([]));

        $this->orderService->shouldReceive('findOrder')
            ->once()
            ->with($orderId)
            ->andReturn($order);

        // Mock the View facade
        $viewContent = '<html>Order PDF</html>';
        \Illuminate\Support\Facades\View::shouldReceive('make')
            ->once()
            ->withArgs(function ($view, $data, $mergeData) use ($order) {
                return $view === 'pdf.order' &&
                    is_array($data) &&
                    array_key_exists('order', $data) &&
                    $data['order'] instanceof Order &&
                    $data['order']->id === $order->id &&
                    is_array($mergeData) &&
                    empty($mergeData);
            })
            ->andReturnSelf();
        \Illuminate\Support\Facades\View::shouldReceive('render')
            ->once()
            ->andReturn($viewContent);

        // Mock PDF generation
        $pdfMock = Mockery::mock('Barryvdh\DomPDF\PDF');
        $response = new \Illuminate\Http\Response('PDF Content', 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="order_' . $orderId . '.pdf"',
        ]);
        $pdfMock->shouldReceive('download')
            ->once()
            ->with('order_' . $orderId . '.pdf')
            ->andReturn($response);

        Pdf::shouldReceive('loadHTML')
            ->once()
            ->with($viewContent)
            ->andReturn($pdfMock);

        $result = $this->controller->downloadOrderPdf($orderId);

        $this->assertInstanceOf(\Illuminate\Http\Response::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('application/pdf', $result->headers->get('Content-Type'));
        $this->assertStringContainsString('filename="order_' . $orderId . '.pdf"', $result->headers->get('Content-Disposition'));
    }
}

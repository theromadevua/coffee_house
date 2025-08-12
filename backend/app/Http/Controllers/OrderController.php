<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    use ApiResponse;

    protected $orderService;


    public function __construct(
        OrderService $orderService
    )
    {
        $this->orderService = $orderService;
    }

    // ====== Orders ======

    /**
     * Retrieve all orders for the authenticated user.
     * @return JsonResponse
     */
    public function getMyOrders(): JsonResponse
    {
        $data = $this->orderService->getMyOrders();
        return $this->successResponse(
            $data,
            'Orders retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Retrieve a specific order by order number.
     * @param int $orderNumber
     * @return JsonResponse
     */
    public function findOrder(int $orderNumber): JsonResponse
    {
        $item = $this->orderService->findOrder($orderNumber);
        return $this->successResponse(
            $item,
            'Order retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Create a new order with validated request data and trigger OrderCreated event.
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function createOrder(StoreOrderRequest $request): JsonResponse
    {
        return app(PaymentController::class)->initiate($request);
    }

    /**
     * Retrieve a list of all orders in the system.
     * @return JsonResponse
     */
    public function getAllOrders(): JsonResponse
    {
        $data = $this->orderService->getAllOrders();
        return $this->successResponse(
            $data,
            'Orders retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Update an existing order with validated request data.
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateOrder(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $item = $this->orderService->updateOrder($id, $request->validated());
        return $this->successResponse(
            $item,
            'Order updated successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Delete an order by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function deleteOrder(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);
        return $this->successResponse(
            null,
            'Order deleted successfully.',
            Response::HTTP_NO_CONTENT
        );
    }

    // ====== Statistic ======

    /**
     * Retrieve statistics for all orders.
     * @return JsonResponse
     */
    public function getOrdersStatistics(): JsonResponse
    {
        $data = $this->orderService->getOrdersStatistics();
        return $this->successResponse(
            $data,
            'Order Statistics retrieved successfully.',
            Response::HTTP_OK
        );
    }

    // ====== Status ======

    /**
     * Update the status of an existing order with validated request data.
     * @param UpdateOrderStatusRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateOrderStatus(UpdateOrderStatusRequest $request, int $id): JsonResponse
    {
        $item = $this->orderService->updateOrderStatus($id, $request->validated());
        return $this->successResponse(
            $item,
            'Order status updated successfully.',
            Response::HTTP_OK
        );
    }

    // ====== Download PDF ======

    /**
     * Generate and download a PDF for a specific order by ID.
     * @param int $id
     * @return Response
     */
    public function downloadOrderPdf(int $id): Response
    {
        $order = $this->orderService->findOrder($id);
        $htmlContent = view('pdf.order', compact('order'))->render();
        $pdf = Pdf::loadHTML($htmlContent);
        return $pdf->download('order_' . $id . '.pdf');
    }
}

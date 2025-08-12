<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate an order creation.
     * @return JsonResponse
     */
    public function initiate(StoreOrderRequest $request): JsonResponse
    {
        $result = $this->paymentService->initiatePayment($request->validated());
        if ($result['success']) {
            return response()->json([
                'data' => ['redirect_url' => $result['redirect_url']],
            ], Response::HTTP_OK);
        }
        return response()->json(['error' => $result['error']], $result['status']);
    }

    /**
     * Redirect user back to site if payment is successful.
     * @return JsonResponse|RedirectResponse
     */
    public function success(Request $request): JsonResponse|RedirectResponse
    {
        $paymentId = $request->query('payment_id');
        $token = $request->query('token');
        $result = $this->paymentService->handleSuccess($token, $paymentId);
        if ($result['success']) {
            return redirect($result['redirect_url']);
        }
        return response()->json(['error' => $result['error']], $result['status']);
    }

    /**
     * Process canceled payment operations.
     * @return JsonResponse
     */
    public function cancel(Request $request): JsonResponse
    {
        $paymentId = $request->query('payment_id');
        $result = $this->paymentService->handleCancel($paymentId);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
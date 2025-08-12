<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    /**
     * Initiate a payment.
     *
     * @param array $data Validated payment data
     * @return array
     * @throws \Throwable
     */
    public function initiatePayment(array $data): array
    {
        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $dish = Dish::findOrFail($item['dish_id']);
            $totalAmount += $dish->price * $item['quantity'];
        }

        $payment = Payment::create([
            'user_id' => auth('api')->id(),
            'transaction_id' => 'TEMP-' . uniqid(),
            'amount' => $totalAmount,
            'currency' => config('paypal.currency', 'USD'),
            'status' => 'PENDING',
            'order_data' => json_encode([
                'items' => $data['items'],
                'delivery_address' => $data['delivery_address'],
                'contact_phone' => $data['contact_phone'],
                'delivery_time' => $data['delivery_time'],
            ]),
        ]);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency', 'USD'),
                        'value' => number_format($totalAmount, 2, '.', ''),
                    ],
                    'payee' => [
                        'email_address' => env('PAYPAL_BUSINESS_EMAIL', env('PAYPAL_BUSINESS_EMAIL', '')),
                    ],
                    'description' => 'Order Payment for User ' . auth('api')->id(),
                ],
            ],
            'application_context' => [
                'return_url' => route('paypal.success') . '?payment_id=' . $payment->id,
                'cancel_url' => route('paypal.cancel') . '?payment_id=' . $payment->id,
            ],
        ]);

        if (isset($response['id']) && $response['status'] == 'CREATED') {
            $payment->update(['transaction_id' => $response['id']]);
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return [
                        'success' => true,
                        'redirect_url' => $link['href'],
                        'payment_id' => $payment->id,
                    ];
                }
            }
        }

        $payment->update(['status' => 'FAILED']);
        return [
            'success' => false,
            'error' => 'Failed to create PayPal order',
            'status' => Response::HTTP_BAD_REQUEST,
        ];
    }

    /**
     * Handle successful payment.
     *
     * @param string $token PayPal token
     * @param int $paymentId Payment ID
     * @return array
     * @throws \Throwable
     */
    public function handleSuccess(string $token, int $paymentId): array
    {
        $payment = Payment::findOrFail($paymentId);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $payment->update(['status' => 'COMPLETED']);
            $orderData = json_decode($payment->order_data, true);

            $userId = $payment->user_id;
            $orderCount = Order::where('user_id', $userId)->count();
            $orderNumber = $orderCount + 1;

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_amount' => $payment->amount,
                'status' => OrderStatus::PROCESSING,
                'delivery_address' => $orderData['delivery_address'],
                'contact_phone' => $orderData['contact_phone'],
                'delivery_time' => $orderData['delivery_time'],
            ]);

            event(new OrderCreated($order));

            foreach ($orderData['items'] as $item) {
                $dish = Dish::findOrFail($item['dish_id']);
                $order->items()->attach($dish->id, [
                    'quantity' => $item['quantity'],
                    'price' => $dish->price,
                ]);
            }

            $payment->update(['order_id' => $order->id]);

            return [
                'success' => true,
                'order' => $order->load('items')->makeHidden(['id']),
                'payment' => $payment,
                'redirect_url' => config('app.order_url') . "/{$order->order_number}",
            ];
        }

        $payment->update(['status' => 'FAILED']);
        return [
            'success' => false,
            'error' => 'Payment failed',
            'status' => Response::HTTP_BAD_REQUEST,
        ];
    }

    /**
     * Handle canceled payment.
     *
     * @param int $paymentId Payment ID
     * @return array
     */
    public function handleCancel(int $paymentId): array
    {
        $payment = Payment::find($paymentId);
        if ($payment) {
            $payment->update(['status' => 'FAILED']);
        }
        return [
            'success' => false,
            'message' => 'Payment cancelled',
            'status' => Response::HTTP_OK,
        ];
    }
}
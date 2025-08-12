<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class OrderService
{

    // ====== Orders ======

    /**
     * @return Collection
     */
    public function getAllOrders(): Collection
    {
        return Order::with('items')->get();
    }

    /**
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        $data['user_id'] = auth('api')->id();

        $items = $data['items'] ?? [];
        unset($data['items']);

        $order = Order::create($data);

        foreach ($items as $item) {
            $dish = Dish::findOrFail($item['dish_id']);
            $order->items()->attach($dish->id, [
                'quantity' => $item['quantity'],
                'price' => $dish->price,
            ]);
        }

        return $order->load('items');
    }

/**
     * Retrieve all orders for the authenticated user.
     * @return Collection
     */
    public function getMyOrders(): Collection
    {
        $userId = auth('api')->id();
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->makeHidden(['id']); // Hide the database ID

        return $orders;
    }

    /**
     * Retrieve a specific order by its order number for the authenticated user.
     * @param int $orderNumber
     * @return Order
     */
    public function findOrder(int $orderNumber): Order
    {
        $userId = auth('api')->id();
        $order = Order::where('user_id', $userId)
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            throw new ModelNotFoundException('Order not found');
        }

        $order->makeHidden(['id']); // Hide the database ID
        $order->load('items'); // Load related items

        return $order;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function updateOrder(int $id, array $data): Order
    {
        $order = $this->findOrder($id);

        $items = $data['items'] ?? [];
        unset($data['items']);

        $order->update($data);

        if (!empty($items)) {
            $order->items()->detach();

            foreach ($items as $item) {
                $dish = Dish::findOrFail($item['dish_id']);
                $order->items()->attach($dish->id, [
                    'quantity' => $item['quantity'],
                    'price' => $dish->price,
                ]);
            }
        }

        return $order->refresh()->load('items');
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteOrder(int $id): void
    {
        $this->findOrder($id)->delete();
    }

    // ====== Status ======

    /**
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function updateOrderStatus(int $id, array $data): Order
    {
        $order = $this->findOrder($id);

        $status = OrderStatus::tryFrom($data['status']);
        if (!$status) {
            throw new \InvalidArgumentException('Invalid order status');
        }

        $order->status = $status;
        $order->save();

        return $order->refresh();
    }

    // ====== Statistic ======

    /**
     * @return array
     */
    public function getOrdersStatistics(): array
    {
        $totalDishesOrdered = DB::table('order_items')
            ->sum('quantity');

        $dishPopularity = Dish::select(['dishes.name', 'dishes.id'])
            ->join('order_items', 'dishes.id', '=', 'order_items.dish_id')
            ->groupBy('dishes.id', 'dishes.name')
            ->orderByDesc(DB::raw('SUM(order_items.quantity)'))
            ->selectRaw('SUM(order_items.quantity) as total_ordered')
            ->get()
            ->map(function ($dish) {
                return [
                    'id' => $dish->id,
                    'name' => $dish->name,
                    'total_ordered' => $dish->total_ordered
                ];
            })->toArray();

        $topUsers = User::select(['users.name', 'users.id'])
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc(DB::raw('COUNT(orders.id)'))
            ->selectRaw('COUNT(orders.id) as order_count')
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'order_count' => $user->order_count
                ];
            })->toArray();

        $ordersByDay = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as order_date, COUNT(id) as order_count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('order_date')
            ->get()
            ->map(function ($order) {
                return [
                    'order_date' => $order->order_date,
                    'order_count' => $order->order_count
                ];
            })->toArray();

        return [
            'total_dishes_ordered' => $totalDishesOrdered,
            'dish_popularity' => $dishPopularity,
            'top_users' => $topUsers,
            'orders_by_day' => $ordersByDay
        ];
    }
}

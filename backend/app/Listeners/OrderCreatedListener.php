<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\ProcessOrderCreated;
use Illuminate\Support\Facades\Log;

class OrderCreatedListener
{
    public function handle(OrderCreated $event)
    {
        Log::info('OrderCreatedListener triggered', ['order_id' => $event->order->id]);
        ProcessOrderCreated::dispatch($event->order)->onQueue('orders');
    }
}
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessOrderCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
        Log::debug('ProcessOrder job constructed', ['order_id' => $order->id]);
    }

    public function handle()
    {
        Log::info('Processing order in ProcessOrder job', ['order_id' => $this->order->id]);
        Redis::publish('order.created', json_encode([
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'total_amount' => $this->order->total_amount,
            'status' => $this->order->status->value,
            'delivery_address' => $this->order->delivery_address,
            'contact_phone' => $this->order->contact_phone,
            'delivery_time' => $this->order->delivery_time?->toISOString(),
        ]));
    }
}

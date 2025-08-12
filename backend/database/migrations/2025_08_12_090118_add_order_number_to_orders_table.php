<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderNumberToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_number')->after('user_id')->nullable();
        });

        // Populate order_number for existing orders
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $orders = \App\Models\Order::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();
            foreach ($orders as $index => $order) {
                $order->update(['order_number' => $index + 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
}
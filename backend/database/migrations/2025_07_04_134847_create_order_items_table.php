<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('dish_id')->constrained('dishes')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Цена блюда на момент заказа
            $table->timestamps();

            $table->unique(['order_id', 'dish_id']); // Одно и то же блюдо не может быть в заказе дважды (разными строками)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
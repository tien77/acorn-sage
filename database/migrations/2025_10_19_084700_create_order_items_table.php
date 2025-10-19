<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name'); // Lưu tên sản phẩm tại thời điểm đặt hàng
            $table->string('product_sku'); // Lưu SKU tại thời điểm đặt hàng
            $table->decimal('product_price', 10, 2); // Giá tại thời điểm đặt hàng
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // product_price * quantity
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
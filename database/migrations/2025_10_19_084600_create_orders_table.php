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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // Thông tin khách hàng
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Địa chỉ giao hàng
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_district')->nullable();
            $table->string('shipping_ward')->nullable();
            $table->string('shipping_postal_code')->nullable();
            
            // Thông tin đơn hàng
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Thanh toán
            $table->enum('payment_method', ['cod', 'bank_transfer', 'momo', 'vnpay'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            
            // Ghi chú
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            $table->foreign('user_id')->references('ID')->on('users')->onDelete('set null');
            $table->index(['status', 'created_at']);
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
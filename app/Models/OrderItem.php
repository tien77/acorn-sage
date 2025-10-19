<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_price',
        'quantity',
        'total_price'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Quan hệ với Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Lấy giá đơn vị đã format
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->product_price, 0, ',', '.') . '₫';
    }

    /**
     * Lấy tổng tiền đã format
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 0, ',', '.') . '₫';
    }

    /**
     * Tính toán lại tổng tiền
     */
    public function calculateTotalPrice(): float
    {
        return $this->product_price * $this->quantity;
    }

    /**
     * Kiểm tra xem sản phẩm còn tồn tại không
     */
    public function productExists(): bool
    {
        return $this->product !== null;
    }

    /**
     * Lấy thông tin sản phẩm hiện tại (có thể đã thay đổi so với lúc đặt hàng)
     */
    public function getCurrentProduct(): ?Product
    {
        return Product::find($this->product_id);
    }

    /**
     * So sánh giá hiện tại với giá lúc đặt hàng
     */
    public function getPriceDifference(): float
    {
        $currentProduct = $this->getCurrentProduct();
        if (!$currentProduct) {
            return 0;
        }

        return $currentProduct->price - $this->product_price;
    }

    /**
     * Kiểm tra xem giá có thay đổi không
     */
    public function hasPriceChanged(): bool
    {
        return abs($this->getPriceDifference()) > 0.01;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Tự động tính tổng tiền khi tạo/cập nhật
        static::creating(function ($orderItem) {
            $orderItem->total_price = $orderItem->calculateTotalPrice();
        });

        static::updating(function ($orderItem) {
            // Chỉ tự động tính lại nếu số lượng hoặc giá thay đổi
            if ($orderItem->isDirty(['quantity', 'product_price'])) {
                $orderItem->total_price = $orderItem->calculateTotalPrice();
            }
        });
    }
}
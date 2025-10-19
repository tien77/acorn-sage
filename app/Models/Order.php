<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'billing_address',
        'billing_phone',
        'shipping_address',
        'shipping_city',
        'shipping_district',
        'shipping_ward',
        'shipping_postal_code',
        'subtotal',
        'shipping_amount',
        'tax_amount',
        'discount_amount',
        'total',
        'payment_method',
        'shipping_method',
        'paid_at',
        'notes',
        'admin_notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    /**
     * Alias cho user relationship (để dễ hiểu hơn)
     */
    public function customer(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Quan hệ với OrderItem
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Lấy tổng số lượng sản phẩm trong đơn hàng
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Lấy text trạng thái đơn hàng
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã giao vận',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            default => 'Không xác định'
        };
    }

    /**
     * Lấy text trạng thái thanh toán
     */
    public function getPaymentStatusLabel(): string
    {
        return match($this->payment_status) {
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
            default => 'Không xác định'
        };
    }

    /**
     * Lấy text phương thức thanh toán
     */
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'momo' => 'Ví MoMo',
            'vnpay' => 'VNPay',
            default => 'Không xác định'
        };
    }

    /**
     * Lấy địa chỉ giao hàng đầy đủ
     */
    public function getFullShippingAddressAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->shipping_ward,
            $this->shipping_district,
            $this->shipping_city
        ]);

        return implode(', ', $parts);
    }

    /**
     * Kiểm tra xem đơn hàng có thể hủy không
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Kiểm tra xem đơn hàng có thể cập nhật không
     */
    public function canBeUpdated(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Tạo đơn hàng từ giỏ hàng
     */
    public static function createFromCart(array $customerData, array $orderData, ?int $userId = null, ?string $sessionId = null): self
    {
        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            throw new \Exception('Giỏ hàng trống');
        }

        // Kiểm tra tồn kho
        if (Cart::hasOutOfStockItems($userId, $sessionId)) {
            throw new \Exception('Có sản phẩm trong giỏ hàng đã hết hàng');
        }

        // Tính toán giá trị đơn hàng
        $subtotal = $cartItems->sum('total_price');
        $shippingFee = $orderData['shipping_fee'] ?? 0;
        $taxAmount = $orderData['tax_amount'] ?? 0;
        $discountAmount = $orderData['discount_amount'] ?? 0;
        $totalAmount = $subtotal + $shippingFee + $taxAmount - $discountAmount;

        // Tạo đơn hàng
        $order = self::create(array_merge($customerData, $orderData, [
            'order_number' => self::generateOrderNumber(),
            'user_id' => $userId,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
        ]));

        // Tạo order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'product_sku' => $cartItem->product->sku,
                'product_price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'total_price' => $cartItem->total_price,
            ]);

            // Cập nhật số lượng tồn kho
            if ($cartItem->product->manage_stock) {
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Cập nhật số lượt bán
            $cartItem->product->increment('sales_count', $cartItem->quantity);
        }

        // Xóa giỏ hàng
        Cart::clearCart($userId, $sessionId);

        return $order;
    }

    /**
     * Tạo số đơn hàng unique
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(string $status, ?string $adminNotes = null): bool
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $this->status = $status;
        
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }

        // Nếu hủy đơn hàng, hoàn lại tồn kho
        if ($status === 'cancelled' && $this->status !== 'cancelled') {
            foreach ($this->items as $item) {
                if ($item->product && $item->product->manage_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    $item->product->decrement('sales_count', $item->quantity);
                }
            }
        }

        return $this->save();
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePaymentStatus(string $paymentStatus): bool
    {
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
        
        if (!in_array($paymentStatus, $validStatuses)) {
            return false;
        }

        $this->payment_status = $paymentStatus;
        
        if ($paymentStatus === 'paid' && !$this->paid_at) {
            $this->paid_at = now();
        }

        return $this->save();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Quan hệ với Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    /**
     * Lấy tổng giá cho item này
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Lấy giỏ hàng theo session hoặc user
     */
    public static function getCartItems(?int $userId = null, ?string $sessionId = null): Collection
    {
        $query = static::with('product');

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return collect();
        }

        return $query->get();
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public static function addItem(int $productId, int $quantity = 1, ?int $userId = null, ?string $sessionId = null): self
    {
        $product = Product::findOrFail($productId);

        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $cartItem = static::where('product_id', $productId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($sessionId && !$userId, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        if ($cartItem) {
            // Nếu đã có thì cập nhật số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có thì tạo mới
            $cartItem = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->display_price
            ]);
        }

        return $cartItem;
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public static function updateQuantity(int $productId, int $quantity, ?int $userId = null, ?string $sessionId = null): bool
    {
        $cartItem = static::where('product_id', $productId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($sessionId && !$userId, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        if ($cartItem) {
            if ($quantity <= 0) {
                return $cartItem->delete();
            } else {
                $cartItem->quantity = $quantity;
                return $cartItem->save();
            }
        }

        return false;
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public static function removeItem(int $productId, ?int $userId = null, ?string $sessionId = null): bool
    {
        return static::where('product_id', $productId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($sessionId && !$userId, fn($q) => $q->where('session_id', $sessionId))
            ->delete() > 0;
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public static function clearCart(?int $userId = null, ?string $sessionId = null): bool
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return false;
        }

        return $query->delete() > 0;
    }

    /**
     * Tính tổng số lượng sản phẩm trong giỏ
     */
    public static function getTotalQuantity(?int $userId = null, ?string $sessionId = null): int
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return 0;
        }

        return $query->sum('quantity');
    }

    /**
     * Tính tổng giá trị giỏ hàng
     */
    public static function getTotalAmount(?int $userId = null, ?string $sessionId = null): float
    {
        $items = static::getCartItems($userId, $sessionId);
        return $items->sum('total_price');
    }

    /**
     * Chuyển giỏ hàng từ session sang user khi đăng nhập
     */
    public static function mergeSessionToUser(string $sessionId, int $userId): void
    {
        $sessionItems = static::where('session_id', $sessionId)->get();

        foreach ($sessionItems as $sessionItem) {
            $userItem = static::where('user_id', $userId)
                ->where('product_id', $sessionItem->product_id)
                ->first();

            if ($userItem) {
                // Nếu user đã có sản phẩm này, cộng thêm số lượng
                $userItem->quantity += $sessionItem->quantity;
                $userItem->save();
            } else {
                // Nếu chưa có, chuyển ownership
                $sessionItem->user_id = $userId;
                $sessionItem->session_id = null;
                $sessionItem->save();
            }
        }

        // Xóa các item session còn lại
        static::where('session_id', $sessionId)->delete();
    }

    /**
     * Kiểm tra xem có sản phẩm nào trong giỏ hết hàng không
     */
    public static function hasOutOfStockItems(?int $userId = null, ?string $sessionId = null): bool
    {
        $items = static::getCartItems($userId, $sessionId);

        foreach ($items as $item) {
            if (!$item->product->is_in_stock || 
                ($item->product->manage_stock && $item->product->stock_quantity < $item->quantity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lấy danh sách sản phẩm hết hàng trong giỏ
     */
    public static function getOutOfStockItems(?int $userId = null, ?string $sessionId = null): Collection
    {
        $items = static::getCartItems($userId, $sessionId);

        return $items->filter(function ($item) {
            return !$item->product->is_in_stock || 
                   ($item->product->manage_stock && $item->product->stock_quantity < $item->quantity);
        });
    }

    /**
     * Lấy tổng tiền giỏ hàng
     */
    public static function getCartTotal(?int $userId = null, ?string $sessionId = null): float
    {
        $items = static::getCartItems($userId, $sessionId);
        
        return $items->sum('total_price');
    }

    /**
     * Lấy tổng số lượng sản phẩm trong giỏ hàng
     */
    public static function getCartCount(?int $userId = null, ?string $sessionId = null): int
    {
        $items = static::getCartItems($userId, $sessionId);
        
        return $items->sum('quantity');
    }

    /**
     * Kiểm tra giỏ hàng có trống không
     */
    public static function isEmpty(?int $userId = null, ?string $sessionId = null): bool
    {
        return static::getCartCount($userId, $sessionId) === 0;
    }
}
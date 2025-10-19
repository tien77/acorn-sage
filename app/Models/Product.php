<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'featured_image',
        'gallery_images',
        'weight',
        'dimensions',
        'status',
        'is_featured',
        'views_count',
        'sales_count',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'sales_count' => 'integer',
        'gallery_images' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Scope để chỉ lấy sản phẩm đã published
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope để lấy sản phẩm nổi bật
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope để lấy sản phẩm còn hàng
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_status', 'in_stock')
                    ->where(function($q) {
                        $q->where('manage_stock', false)
                          ->orWhere('stock_quantity', '>', 0);
                    });
    }

    /**
     * Scope để sắp xếp theo giá
     */
    public function scopeOrderByPrice(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Scope để lọc theo khoảng giá
     */
    public function scopePriceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Quan hệ many-to-many với categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withTimestamps();
    }

    /**
     * Lấy giá hiển thị (sale_price nếu có, không thì price)
     */
    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Kiểm tra xem sản phẩm có đang sale không
     */
    public function getIsOnSaleAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->is_on_sale) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Lấy URL của sản phẩm
     */
    public function getUrlAttribute(): string
    {
        return route('products.show', $this->slug);
    }

    /**
     * Lấy ảnh đại diện
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    /**
     * Lấy danh sách ảnh gallery
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return [];
        }
        
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->gallery_images);
    }

    /**
     * Kiểm tra trạng thái tồn kho
     */
    public function getIsInStockAttribute(): bool
    {
        if ($this->stock_status === 'out_of_stock') {
            return false;
        }
        
        if ($this->manage_stock && $this->stock_quantity <= 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Kiểm tra sản phẩm đã được publish chưa
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at <= now();
    }

    /**
     * Lấy text trạng thái tồn kho
     */
    public function getStockStatusTextAttribute(): string
    {
        return match($this->stock_status) {
            'in_stock' => 'Còn hàng',
            'out_of_stock' => 'Hết hàng',
            'on_backorder' => 'Đặt trước',
            default => 'Không xác định'
        };
    }

    /**
     * Tăng số lượt xem
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Tăng số lượt bán
     */
    public function incrementSales(int $quantity = 1): void
    {
        $this->increment('sales_count', $quantity);
        
        if ($this->manage_stock) {
            $this->decrement('stock_quantity', $quantity);
            
            // Cập nhật trạng thái tồn kho
            if ($this->stock_quantity <= 0) {
                $this->update(['stock_status' => 'out_of_stock']);
            }
        }
    }

    /**
     * Tự động tạo slug và SKU khi tạo mới
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            // Tạo slug
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Đảm bảo slug là unique
                $originalSlug = $product->slug;
                $counter = 1;
                
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
            
            // Tạo SKU nếu không có
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
                
                // Đảm bảo SKU là unique
                while (static::where('sku', $product->sku)->exists()) {
                    $product->sku = 'PRD-' . strtoupper(Str::random(8));
                }
            }
            
            // Set published_at nếu status là published
            if ($product->status === 'published' && !$product->published_at) {
                $product->published_at = now();
            }
        });
        
        static::updating(function ($product) {
            // Set published_at khi chuyển sang published
            if ($product->isDirty('status') && $product->status === 'published' && !$product->published_at) {
                $product->published_at = now();
            }
        });
    }
}
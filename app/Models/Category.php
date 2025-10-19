<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
        'parent_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope để chỉ lấy các category active
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get status attribute for compatibility
     */
    public function getStatusAttribute(): string
    {
        return $this->is_active ? 'active' : 'inactive';
    }

    /**
     * Set status attribute for compatibility
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['is_active'] = $value === 'active';
    }

    /**
     * Scope để lấy category gốc (không có parent)
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope để sắp xếp theo thứ tự
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Quan hệ với category cha
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Quan hệ với các category con
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->ordered();
    }

    /**
     * Quan hệ many-to-many với products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories')
                    ->withTimestamps();
    }

    /**
     * Lấy số lượng sản phẩm trong category (bao gồm cả category con)
     */
    public function getProductCountAttribute(): int
    {
        $count = $this->products()->where('status', 'published')->count();
        
        // Cộng thêm số lượng sản phẩm từ category con
        foreach ($this->children as $child) {
            $count += $child->product_count;
        }
        
        return $count;
    }

    /**
     * Lấy đường dẫn đầy đủ của category (Parent > Child)
     */
    public function getFullNameAttribute(): string
    {
        $names = collect([$this->name]);
        $parent = $this->parent;
        
        while ($parent) {
            $names->prepend($parent->name);
            $parent = $parent->parent;
        }
        
        return $names->implode(' > ');
    }

    /**
     * Lấy tất cả category con (đệ quy)
     */
    public function getAllChildren()
    {
        $children = collect();
        
        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }
        
        return $children;
    }

    /**
     * Kiểm tra xem category có phải là cha của category khác không
     */
    public function isParentOf(Category $category): bool
    {
        return $this->getAllChildren()->contains('id', $category->id);
    }

    /**
     * Kiểm tra xem category có phải là con của category khác không
     */
    public function isChildOf(Category $category): bool
    {
        return $category->isParentOf($this);
    }

    /**
     * Lấy URL của category
     */
    public function getUrlAttribute(): string
    {
        return route('products.category', $this->slug);
    }

    /**
     * Tự động tạo slug từ name khi tạo mới
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
                
                // Đảm bảo slug là unique
                $originalSlug = $category->slug;
                $counter = 1;
                
                while (static::where('slug', $category->slug)->exists()) {
                    $category->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }
}
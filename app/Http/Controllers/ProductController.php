<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProductController
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request): View
    {
        $query = Product::published()->with('categories');
        
        // Lọc theo category
        if ($request->has('category') && $request->category) {
            if (is_numeric($request->category)) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('categories.id', $request->category);
                });
            } else {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('categories.slug', $request->category);
                });
            }
        }
        
        // Lọc theo khoảng giá
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', (float) $request->max_price);
        }
        
        // Lọc theo trạng thái tồn kho
        if ($request->has('in_stock') && $request->in_stock) {
            $query->inStock();
        }
        
        // Lọc sản phẩm nổi bật
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }
        
        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }
        
        // Sắp xếp
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderByPrice('asc');
                break;
            case 'price_high':
                $query->orderByPrice('desc');
                break;
            case 'name':
                $query->orderBy('name', $sortDirection);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('sales_count', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortDirection);
        }
        
        // Phân trang
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);
        
        // Lấy categories để hiển thị filter
        $categories = Category::active()
                             ->root()
                             ->with('children')
                             ->ordered()
                             ->get();
        
        return view('pages.products', compact('products', 'categories'));
    }
    
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show(Request $request, string $slug): View
    {
        $product = Product::published()
            ->with(['categories'])
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Tăng view count
        $product->incrementViews();
        
        // Lấy sản phẩm liên quan (cùng category)
        $relatedProducts = $this->getRelatedProducts($product, 4);
        
        // Lấy sản phẩm đã xem gần đây (từ session)
        $recentlyViewed = $this->getRecentlyViewedProducts($product->id, 4);
        
        // Lưu vào recently viewed
        $this->addToRecentlyViewed($product->id);
        
        return view('pages.product-detail', compact('product', 'relatedProducts', 'recentlyViewed'));
    }
    
    /**
     * Hiển thị sản phẩm theo category
     */
    public function category(Request $request, string $slug): View
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->with(['children'])
            ->firstOrFail();
            
        // Lấy tất cả category con
        $categoryIds = [$category->id];
        $categoryIds = array_merge($categoryIds, $category->getAllChildren()->pluck('id')->toArray());
        
        $query = Product::published()
            ->with('categories')
            ->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
            
        // Apply các filter khác như index
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', (float) $request->max_price);
        }
        
        if ($request->has('in_stock') && $request->in_stock) {
            $query->inStock();
        }
        
        // Sắp xếp
        $sortBy = $request->get('sort', 'created_at');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByPrice('asc');
                break;
            case 'price_high':
                $query->orderByPrice('desc');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        
        // Lấy categories để hiển thị filter
        $categories = Category::active()
                             ->withCount('products')
                             ->ordered()
                             ->get();
                             
        return view('pages.products', compact('category', 'products', 'categories'));
    }
    
    /**
     * API endpoint để lấy sản phẩm (cho AJAX)
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Product::published()->with('categories');
        
        // Apply filters giống như index
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.slug', $request->category);
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('per_page', 12));
        
        return response()->json([
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }
    
    /**
     * Lấy sản phẩm liên quan
     */
    private function getRelatedProducts(Product $product, int $limit = 4)
    {
        $categoryIds = $product->categories->pluck('id');
        
        return Product::published()
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Lấy sản phẩm đã xem gần đây từ session
     */
    private function getRecentlyViewedProducts(int $excludeId, int $limit = 4)
    {
        $recentlyViewed = session('recently_viewed_products', []);
        $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id !== $excludeId);
        
        if (empty($recentlyViewed)) {
            return collect();
        }
        
        return Product::published()
            ->whereIn('id', array_slice($recentlyViewed, 0, $limit))
            ->get();
    }
    
    /**
     * Thêm sản phẩm vào danh sách đã xem gần đây
     */
    private function addToRecentlyViewed(int $productId): void
    {
        $recentlyViewed = session('recently_viewed_products', []);
        
        // Remove if already exists
        $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id !== $productId);
        
        // Add to beginning
        array_unshift($recentlyViewed, $productId);
        
        // Keep only last 10
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        
        session(['recently_viewed_products' => $recentlyViewed]);
    }
}
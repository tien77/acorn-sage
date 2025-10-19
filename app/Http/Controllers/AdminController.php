<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'published_products' => Product::where('status', 'published')->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'this_month_revenue' => Order::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        $recent_orders = Order::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $low_stock_products = Product::where('manage_stock', true)
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'low_stock_products'));
    }

    /**
     * Quản lý sản phẩm
     */
    public function products(Request $request): View
    {
        $query = Product::query();

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by stock status
        if ($request->stock_status) {
            $query->where('stock_status', $request->stock_status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Form tạo sản phẩm mới
     */
    public function createProduct(): View
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'nullable|integer|min:0',
            'manage_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'status' => 'required|in:published,draft',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $data = $request->except(['featured_image', 'gallery_images', 'categories']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
            $data['gallery_images'] = $galleryImages;
        }

        $product = Product::create($data);

        // Attach categories
        if ($request->categories) {
            $product->categories()->attach($request->categories);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Form chỉnh sửa sản phẩm
     */
    public function editProduct(Product $product): View
    {
        $categories = Category::all();
        $product->load('categories');
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'manage_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'status' => 'required|in:published,draft',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $data = $request->except(['featured_image', 'gallery_images', 'categories']);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
            $data['gallery_images'] = $galleryImages;
        }

        $product->update($data);

        // Sync categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories ?? []);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Xóa sản phẩm
     */
    public function deleteProduct(Product $product): JsonResponse
    {
        try {
            // Delete images
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Detach categories
            $product->categories()->detach();

            // Delete product
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quản lý đơn hàng
     */
    public function orders(Request $request): View
    {
        $query = Order::with(['items.product']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order number or customer
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function showOrder(Order $order): View
    {
        $order->load(['items.product', 'customer']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật đơn hàng
     */
    public function updateOrder(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Đơn hàng đã được cập nhật!');
    }

    /**
     * Xóa đơn hàng
     */
    public function deleteOrder(Order $order): JsonResponse
    {
        try {
            // Delete order items first
            $order->items()->delete();
            
            // Delete order
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions cho sản phẩm
     */
    public function bulkProductAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->product_ids);

        switch ($request->action) {
            case 'delete':
                $products->delete();
                $message = 'Đã xóa các sản phẩm đã chọn';
                break;
            case 'publish':
                $products->update(['status' => 'published']);
                $message = 'Đã công bố các sản phẩm đã chọn';
                break;
            case 'unpublish':
                $products->update(['status' => 'draft']);
                $message = 'Đã chuyển các sản phẩm về bản nháp';
                break;
            case 'feature':
                $products->update(['is_featured' => true]);
                $message = 'Đã đánh dấu các sản phẩm là nổi bật';
                break;
            case 'unfeature':
                $products->update(['is_featured' => false]);
                $message = 'Đã bỏ đánh dấu nổi bật cho các sản phẩm';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Quản lý danh mục
     */
    public function categories(): View
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Lấy thông tin danh mục
     */
    public function getCategory(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Tạo danh mục mới
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['image']);

        // Auto generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được tạo thành công!',
            'category' => $category
        ]);
    }

    /**
     * Cập nhật danh mục
     */
    public function updateCategory(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['image']);

        // Auto generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được cập nhật thành công!',
            'category' => $category
        ]);
    }

    /**
     * Xóa danh mục
     */
    public function deleteCategory(Category $category): JsonResponse
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa danh mục đang có sản phẩm!'
                ], 400);
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa danh mục có danh mục con!'
                ], 400);
            }

            // Delete image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Delete category
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Danh mục đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
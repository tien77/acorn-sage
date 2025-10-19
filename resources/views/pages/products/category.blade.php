@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" wire:navigate class="hover:text-indigo-600">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('products.index') }}" wire:navigate class="hover:text-indigo-600">Sản phẩm</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-2 text-gray-600">{{ $category->description }}</p>
                @endif
                <p class="mt-1 text-sm text-gray-500">{{ $products->total() }} sản phẩm</p>
            </div>

            @if($category->image)
                <div class="w-24 h-24 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover">
                </div>
            @endif
        </div>

        <!-- Subcategories -->
        @if($category->children->count() > 0)
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">Danh mục con</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($category->children as $child)
                        <a href="{{ route('products.category', $child->slug) }}" wire:navigate
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            {{ $child->name }}
                            <span class="ml-2 text-xs text-gray-500">({{ $child->product_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Filters and Sort -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-4 sm:space-y-0">
        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-4">
            <!-- Price Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Giá:</label>
                <select name="price_filter" class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="0-500000" {{ request('price_filter') === '0-500000' ? 'selected' : '' }}>Dưới 500k</option>
                    <option value="500000-1000000" {{ request('price_filter') === '500000-1000000' ? 'selected' : '' }}>500k - 1tr</option>
                    <option value="1000000-2000000" {{ request('price_filter') === '1000000-2000000' ? 'selected' : '' }}>1tr - 2tr</option>
                    <option value="2000000-" {{ request('price_filter') === '2000000-' ? 'selected' : '' }}>Trên 2tr</option>
                </select>
            </div>

            <!-- Stock Filter -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="in_stock" name="in_stock" value="1" 
                       class="text-indigo-600 focus:ring-indigo-500" 
                       {{ request('in_stock') ? 'checked' : '' }}>
                <label for="in_stock" class="text-sm text-gray-700">Còn hàng</label>
            </div>
        </div>

        <!-- Sort -->
        <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-700">Sắp xếp:</label>
            <select name="sort" class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Phổ biến</option>
                <option value="bestselling" {{ request('sort') === 'bestselling' ? 'selected' : '' }}>Bán chạy</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition group">
                    <div class="relative overflow-hidden rounded-t-lg">
                        <a href="{{ $product->url }}" wire:navigate>
                            @if($product->featured_image_url)
                                <img src="{{ $product->featured_image_url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover group-hover:scale-105 transition">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <!-- Sale Badge -->
                        @if($product->is_on_sale)
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                -{{ $product->discount_percent }}%
                            </div>
                        @endif

                        <!-- Stock Status -->
                        @if(!$product->is_in_stock)
                            <div class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded">
                                Hết hàng
                            </div>
                        @endif

                        <!-- Featured Badge -->
                        @if($product->is_featured)
                            <div class="absolute bottom-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded">
                                Nổi bật
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <!-- Product Name -->
                        <h3>
                            <a href="{{ $product->url }}" wire:navigate
                               class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <!-- SKU -->
                        <p class="text-xs text-gray-500 mt-1">{{ $product->sku }}</p>

                        <!-- Short Description -->
                        @if($product->short_description)
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                                {{ $product->short_description }}
                            </p>
                        @endif

                        <!-- Price -->
                        <div class="mt-3 flex items-center space-x-2">
                            <span class="text-lg font-bold text-gray-900">
                                {{ number_format($product->display_price, 0, ',', '.') }}đ
                            </span>
                            @if($product->is_on_sale)
                                <span class="text-sm text-gray-500 line-through">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </span>
                            @endif
                        </div>

                        <!-- Stock Status -->
                        <div class="mt-2 text-sm">
                            <span class="text-{{ $product->is_in_stock ? 'green' : 'red' }}-600">
                                {{ $product->stock_status_text }}
                            </span>
                            @if($product->manage_stock && $product->is_in_stock)
                                <span class="text-gray-500 ml-1">({{ $product->stock_quantity }})</span>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-4 flex space-x-2">
                            @if($product->is_in_stock)
                                <button class="flex-1 bg-indigo-600 text-white text-sm py-2 px-3 rounded hover:bg-indigo-700 transition">
                                    Thêm vào giỏ
                                </button>
                            @else
                                <button disabled class="flex-1 bg-gray-400 text-white text-sm py-2 px-3 rounded cursor-not-allowed">
                                    Hết hàng
                                </button>
                            @endif
                            
                            <button class="px-3 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- No Products -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Không có sản phẩm nào</h3>
            <p class="mt-1 text-sm text-gray-500">Danh mục này hiện chưa có sản phẩm nào.</p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" wire:navigate
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Xem tất cả sản phẩm
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name]');
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name]');
    
    function updateUrl() {
        const params = new URLSearchParams(window.location.search);
        
        // Update select values
        selects.forEach(select => {
            if (select.value) {
                params.set(select.name, select.value);
            } else {
                params.delete(select.name);
            }
        });
        
        // Update checkbox values
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                params.set(checkbox.name, checkbox.value);
            } else {
                params.delete(checkbox.name);
            }
        });
        
        // Update URL
        const newUrl = window.location.pathname + '?' + params.toString();
        window.location.href = newUrl;
    }
    
    selects.forEach(select => {
        select.addEventListener('change', updateUrl);
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateUrl);
    });
});
</script>
@endpush

@endsection
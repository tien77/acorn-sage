@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Sản phẩm</h1>
            <p class="text-gray-600">Khám phá bộ sưu tập sản phẩm chất lượng của chúng tôi</p>
        </div>
        
        <!-- Filter Toggle Button (Mobile) -->
        <button id="filter-toggle" class="lg:hidden mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Bộ lọc
        </button>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div id="filters-sidebar" class="lg:w-64 space-y-6 hidden lg:block">
            <!-- Category Filter -->
            @if($categories->count() > 0)
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h3 class="font-semibold text-gray-900 mb-4">Danh mục</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="category" value="" class="text-indigo-600 focus:ring-indigo-500" 
                                   {{ !request('category') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Tất cả</span>
                        </label>
                        @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="radio" name="category" value="{{ $category->slug }}" class="text-indigo-600 focus:ring-indigo-500"
                                       {{ request('category') === $category->slug ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                <span class="ml-auto text-xs text-gray-500">({{ $category->product_count }})</span>
                            </label>
                            
                            @if($category->children->count() > 0)
                                @foreach($category->children as $child)
                                    <label class="flex items-center ml-4">
                                        <input type="radio" name="category" value="{{ $child->slug }}" class="text-indigo-600 focus:ring-indigo-500"
                                               {{ request('category') === $child->slug ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">{{ $child->name }}</span>
                                        <span class="ml-auto text-xs text-gray-500">({{ $child->product_count }})</span>
                                    </label>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Price Filter -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="font-semibold text-gray-900 mb-4">Khoảng giá</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Từ</label>
                        <input type="number" name="min_price" placeholder="0" 
                               value="{{ request('min_price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Đến</label>
                        <input type="number" name="max_price" placeholder="1,000,000" 
                               value="{{ request('max_price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Stock Filter -->
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h3 class="font-semibold text-gray-900 mb-4">Tình trạng</h3>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="in_stock" value="1" class="text-indigo-600 focus:ring-indigo-500"
                               {{ request('in_stock') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Còn hàng</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="featured" value="1" class="text-indigo-600 focus:ring-indigo-500"
                               {{ request('featured') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Sản phẩm nổi bật</span>
                    </label>
                </div>
            </div>

            <!-- Apply Filters Button -->
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                Áp dụng bộ lọc
            </button>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Search and Sort -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-4 sm:space-y-0">
                <!-- Search -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sort -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm text-gray-700">Sắp xếp:</label>
                    <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                                <!-- Categories -->
                                @if($product->categories->count() > 0)
                                    <div class="mb-2">
                                        @foreach($product->categories->take(2) as $category)
                                            <span class="inline-block text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded mr-1">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Product Name -->
                                <h3>
                                    <a href="{{ $product->url }}" wire:navigate
                                       class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <!-- Short Description -->
                                @if($product->short_description)
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <!-- No Products -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy sản phẩm</h3>
                    <p class="mt-1 text-sm text-gray-500">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mobile filter toggle
document.getElementById('filter-toggle').addEventListener('click', function() {
    const sidebar = document.getElementById('filters-sidebar');
    sidebar.classList.toggle('hidden');
});

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("products.index") }}';
    
    const inputs = document.querySelectorAll('input[name], select[name]');
    inputs.forEach(input => {
        const clone = input.cloneNode(true);
        form.appendChild(clone);
        
        input.addEventListener('change', function() {
            // Update form with current values
            const formData = new FormData();
            inputs.forEach(inp => {
                if (inp.type === 'checkbox' && !inp.checked) return;
                if (inp.type === 'radio' && !inp.checked) return;
                if (inp.value) formData.append(inp.name, inp.value);
            });
            
            // Submit form
            const params = new URLSearchParams(formData);
            window.location.href = '{{ route("products.index") }}?' + params.toString();
        });
    });
});
</script>
@endpush

@endsection
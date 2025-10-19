@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        @if(isset($category))
                            {{ $category->name }}
                        @else
                            Sản phẩm
                        @endif
                    </h1>
                    @if(isset($category) && $category->description)
                        <p class="text-gray-600">{{ $category->description }}</p>
                    @endif
                </div>

                <!-- View Toggle -->
                <div class="flex items-center space-x-2">
                    <button id="grid-view" class="p-2 text-blue-600 bg-blue-50 rounded">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button id="list-view" class="p-2 text-gray-400 hover:bg-gray-50 rounded">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Breadcrumb -->
            <nav class="text-sm mt-4">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
                <span class="mx-2 text-gray-500">/</span>
                @if(isset($category))
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Sản phẩm</a>
                    <span class="mx-2 text-gray-500">/</span>
                    <span class="text-gray-700">{{ $category->name }}</span>
                @else
                    <span class="text-gray-700">Sản phẩm</span>
                @endif
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-4">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}" 
                               class="block py-2 px-3 rounded {{ !isset($category) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                Tất cả sản phẩm
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('products.category', $cat->slug) }}" 
                                   class="block py-2 px-3 rounded {{ (isset($category) && $category->id === $cat->id) ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                    {{ $cat->name }}
                                    <span class="text-sm text-gray-500">({{ $cat->products_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Khoảng giá</h3>
                        <form id="price-filter" method="GET">
                            <div class="space-y-3">
                                <div>
                                    <input type="number" 
                                           name="min_price" 
                                           placeholder="Giá thấp nhất"
                                           value="{{ request('min_price') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <input type="number" 
                                           name="max_price" 
                                           placeholder="Giá cao nhất"
                                           value="{{ request('max_price') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                    Áp dụng
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Stock Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tình trạng</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="in_stock" 
                                       value="1"
                                       {{ request('in_stock') ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-gray-700">Còn hàng</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="on_sale" 
                                       value="1"
                                       {{ request('on_sale') ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-gray-700">Đang giảm giá</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="featured" 
                                       value="1"
                                       {{ request('featured') ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-gray-700">Sản phẩm nổi bật</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Toolbar -->
                <div class="flex items-center justify-between mb-6">
                    <!-- Search -->
                    <div class="flex-1 max-w-md">
                        <form method="GET" class="relative">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Tìm kiếm sản phẩm..."
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </form>
                    </div>

                    <!-- Sort -->
                    <div class="ml-4">
                        <select name="sort" 
                                onchange="window.location.href = updateUrlParameter(window.location.href, 'sort', this.value)"
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Bán chạy</option>
                        </select>
                    </div>
                </div>

                <!-- Results Info -->
                <div class="mb-6">
                    <p class="text-gray-600">
                        Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} 
                        của {{ $products->total() }} sản phẩm
                        @if(request('search'))
                            cho từ khóa "<strong>{{ request('search') }}</strong>"
                        @endif
                    </p>
                </div>

                @if($products->isEmpty())
                    <!-- No Products -->
                    <div class="text-center py-16">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy sản phẩm</h3>
                        <p class="text-gray-500 mb-6">Thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                @else
                    <!-- Products Grid -->
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow product-card">
                                <!-- Product Image -->
                                <div class="relative">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $product->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-48 object-cover rounded-t-lg">
                                    </a>

                                    <!-- Badges -->
                                    <div class="absolute top-2 left-2 space-y-1">
                                        @if($product->is_featured)
                                            <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">
                                                Nổi bật
                                            </span>
                                        @endif
                                        
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                            </span>
                                        @endif

                                        @if(!$product->is_in_stock)
                                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">
                                                Hết hàng
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="p-2 bg-white rounded-full shadow hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <!-- Categories -->
                                    @if($product->categories->isNotEmpty())
                                        <div class="mb-2">
                                            <a href="{{ route('products.category', $product->categories->first()->slug) }}" 
                                               class="text-sm text-blue-600 hover:text-blue-800">
                                                {{ $product->categories->first()->name }}
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Product Name -->
                                    <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="hover:text-blue-600">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Rating (placeholder) -->
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                     fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-1 text-xs text-gray-500">(12)</span>
                                    </div>

                                    <!-- Price and Add to Cart -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-lg font-bold text-red-600">
                                                        {{ number_format($product->sale_price, 0, ',', '.') }}₫
                                                    </span>
                                                    <span class="text-sm text-gray-500 line-through">
                                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-lg font-bold text-red-600">
                                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                                </span>
                                            @endif
                                        </div>

                                        @if($product->is_in_stock)
                                            <button class="quick-add-btn bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                                                    data-product-id="{{ $product->id }}">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13l1.5 6M12 17h6M9 17h.01"/>
                                                </svg>
                                                Thêm
                                            </button>
                                        @else
                                            <button disabled 
                                                    class="bg-gray-400 text-white px-3 py-2 rounded-lg cursor-not-allowed text-sm font-medium">
                                                Hết hàng
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductList();
});

// Reinit after SPA navigation
document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeProductList, 100);
});

function initializeProductList() {
    // Quick add to cart buttons
    const quickAddButtons = document.querySelectorAll('.quick-add-btn');
    quickAddButtons.forEach(btn => {
        // Remove old listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    // Re-attach listeners
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            quickAddToCart(productId, this);
        });
    });

    // Filter checkboxes
    const filterCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateFilters();
        });
    });

    // View toggle (placeholder)
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');

    if (gridViewBtn && listViewBtn) {
        gridViewBtn.addEventListener('click', function() {
            switchToGridView();
        });

        listViewBtn.addEventListener('click', function() {
            switchToListView();
        });
    }
}

// Quick add to cart
function quickAddToCart(productId, button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    button.disabled = true;

    fetch('{{ route("cart.quick-add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Update URL with filters
function updateFilters() {
    const url = new URL(window.location);
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    
    // Clear existing filter params
    url.searchParams.delete('in_stock');
    url.searchParams.delete('on_sale');
    url.searchParams.delete('featured');
    
    // Add checked filters
    checkboxes.forEach(checkbox => {
        url.searchParams.set(checkbox.name, checkbox.value);
    });
    
    window.location.href = url.toString();
}

// Update URL parameter
function updateUrlParameter(url, param, value) {
    const urlObj = new URL(url);
    urlObj.searchParams.set(param, value);
    return urlObj.toString();
}

// View toggles
function switchToGridView() {
    const grid = document.getElementById('products-grid');
    grid.className = 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6';
    
    document.getElementById('grid-view').className = 'p-2 text-blue-600 bg-blue-50 rounded';
    document.getElementById('list-view').className = 'p-2 text-gray-400 hover:bg-gray-50 rounded';
}

function switchToListView() {
    const grid = document.getElementById('products-grid');
    grid.className = 'space-y-4';
    
    document.getElementById('list-view').className = 'p-2 text-blue-600 bg-blue-50 rounded';
    document.getElementById('grid-view').className = 'p-2 text-gray-400 hover:bg-gray-50 rounded';
}

// Update cart count
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'inline' : 'none';
    });
}

// Show notification  
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
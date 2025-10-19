@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Sản phẩm</a>
                
                @if($product->categories->isNotEmpty())
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('products.category', $product->categories->first()->slug) }}" 
                       class="text-blue-600 hover:text-blue-800">
                        {{ $product->categories->first()->name }}
                    </a>
                @endif
                
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-700">{{ $product->name }}</span>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div>
                <!-- Main Image -->
                <div class="mb-4">
                    <img id="main-image" 
                         src="{{ $product->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-cover rounded-lg shadow-sm">
                </div>

                <!-- Gallery -->
                @if($product->gallery_images && count($product->gallery_images) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        <!-- Featured Image Thumbnail -->
                        <button class="gallery-thumb border-2 border-blue-500" 
                                data-image="{{ $product->featured_image }}">
                            <img src="{{ $product->featured_image }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-20 object-cover rounded">
                        </button>

                        <!-- Gallery Thumbnails -->
                        @foreach($product->gallery_images as $image)
                            <button class="gallery-thumb border-2 border-transparent hover:border-blue-300" 
                                    data-image="{{ $image }}">
                                <img src="{{ $image }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-20 object-cover rounded">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    <!-- SKU -->
                    @if($product->sku)
                        <p class="text-sm text-gray-500 mb-4">SKU: {{ $product->sku }}</p>
                    @endif

                    <!-- Rating and Reviews (placeholder) -->
                    <div class="flex items-center mb-4">
                        <div class="flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-600">(12 đánh giá)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl font-bold text-red-600">
                                    {{ number_format($product->sale_price, 0, ',', '.') }}₫
                                </span>
                                <span class="text-xl text-gray-500 line-through">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </span>
                                <span class="px-2 py-1 bg-red-100 text-red-600 text-sm rounded">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-red-600">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </span>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if($product->is_in_stock)
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-green-600 font-medium">Còn hàng</span>
                                @if($product->manage_stock && $product->stock_quantity)
                                    <span class="text-gray-500">
                                        ({{ $product->stock_quantity }} sản phẩm)
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-red-600 font-medium">Hết hàng</span>
                            </div>
                        @endif
                    </div>

                    <!-- Categories -->
                    @if($product->categories->isNotEmpty())
                        <div class="mb-6">
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-600">Danh mục:</span>
                                <div class="flex space-x-2">
                                    @foreach($product->categories as $category)
                                        <a href="{{ route('products.category', $category->slug) }}" 
                                           class="px-2 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                @if($product->is_in_stock)
                    <form id="add-to-cart-form" class="mb-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <label for="quantity" class="text-gray-700 font-medium">Số lượng:</label>
                            <div class="flex items-center border rounded-lg">
                                <button type="button" id="decrease-qty" class="p-2 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->manage_stock ? $product->stock_quantity : 99 }}"
                                       class="w-16 px-3 py-2 text-center border-0 focus:ring-0">
                                <button type="button" id="increase-qty" class="p-2 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13l1.5 6M12 17h6M9 17h.01"/>
                                </svg>
                                Thêm vào giỏ hàng
                            </button>
                            
                            <button type="button" 
                                    class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="mb-6">
                        <button disabled 
                                class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-medium cursor-not-allowed">
                            Hết hàng
                        </button>
                    </div>
                @endif

                <!-- Product Features -->
                <div class="border-t pt-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Bảo hành chính hãng</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Giao hàng toàn quốc</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Đổi trả trong 7 ngày</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-blue-600" 
                            data-tab="description">
                        Mô tả sản phẩm
                    </button>
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-tab="specifications">
                        Thông số kỹ thuật
                    </button>
                    <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-tab="reviews">
                        Đánh giá (12)
                    </button>
                </nav>
            </div>

            <div class="py-8">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose max-w-none">
                        {!! $product->description ?: '<p>Chưa có mô tả cho sản phẩm này.</p>' !!}
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($product->dimensions)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Kích thước</h4>
                                <p class="text-gray-700">{{ $product->dimensions }}</p>
                            </div>
                        @endif

                        @if($product->weight)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Trọng lượng</h4>
                                <p class="text-gray-700">{{ $product->weight }}</p>
                            </div>
                        @endif

                        @if($product->sku)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Mã sản phẩm</h4>
                                <p class="text-gray-700">{{ $product->sku }}</p>
                            </div>
                        @endif

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Trạng thái</h4>
                            <p class="text-gray-700">{{ $product->is_in_stock ? 'Còn hàng' : 'Hết hàng' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-content hidden">
                    <div class="text-center py-8">
                        <p class="text-gray-500">Chức năng đánh giá sản phẩm đang được phát triển.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts && $relatedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Sản phẩm liên quan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                <img src="{{ $relatedProduct->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                                     alt="{{ $relatedProduct->name }}"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            </a>
                            
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" 
                                       class="hover:text-blue-600">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                            <span class="text-lg font-bold text-red-600">
                                                {{ number_format($relatedProduct->sale_price, 0, ',', '.') }}₫
                                            </span>
                                            <span class="text-sm text-gray-500 line-through ml-2">
                                                {{ number_format($relatedProduct->price, 0, ',', '.') }}₫
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-red-600">
                                                {{ number_format($relatedProduct->price, 0, ',', '.') }}₫
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($relatedProduct->is_in_stock)
                                        <button class="quick-add-btn bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition-colors"
                                                data-product-id="{{ $relatedProduct->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13l1.5 6M12 17h6M9 17h.01"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductDetail();
});

// Reinit after SPA navigation
document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeProductDetail, 100);
});

function initializeProductDetail() {
    // Gallery
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.getElementById('main-image');

    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imageUrl = this.dataset.image;
            mainImage.src = imageUrl;
            
            // Update active thumbnail
            galleryThumbs.forEach(t => t.classList.remove('border-blue-500'));
            galleryThumbs.forEach(t => t.classList.add('border-transparent'));
            this.classList.add('border-blue-500');
            this.classList.remove('border-transparent');
        });
    });

    // Quantity controls
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.max);
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }

    // Add to cart form
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const quantity = parseInt(quantityInput.value);
            const productId = {{ $product->id }};
            
            addToCart(productId, quantity);
        });
    }

    // Quick add buttons
    const quickAddButtons = document.querySelectorAll('.quick-add-btn');
    quickAddButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            quickAddToCart(productId);
        });
    });

    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            // Update button states
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
    });
}

// Add to cart function
function addToCart(productId, quantity) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
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
    });
}

// Quick add to cart
function quickAddToCart(productId) {
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
    });
}

// Update cart count in navigation
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
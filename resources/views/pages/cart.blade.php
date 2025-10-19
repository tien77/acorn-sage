@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Giỏ hàng</h1>
            <nav class="text-sm">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
                <span class="mx-2 text-gray-500">/</span>
                <span class="text-gray-700">Giỏ hàng</span>
            </nav>
        </div>

        @if(isset($error))
            <!-- Debug Error -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Lỗi debug:</strong>
                <span class="block sm:inline">{{ $error }}</span>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="mb-8">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6M17 13l1.5 6M12 17h6M9 17h.01"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Giỏ hàng trống</h2>
                <p class="text-gray-500 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Tiếp tục mua sắm
                </a>
            </div>
        @else
            <!-- Cart Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Items List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Sản phẩm trong giỏ hàng ({{ $cartCount }})
                            </h2>
                        </div>

                        <div class="divide-y">
                            @foreach($cartItems as $item)
                                <div class="p-6 cart-item" data-product-id="{{ $item->product_id }}">
                                    <div class="flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->product->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="h-20 w-20 object-cover rounded-lg">
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">
                                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            
                                            @if($item->product->sku)
                                                <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product->sku }}</p>
                                            @endif

                                            <div class="flex items-center space-x-4">
                                                <!-- Price -->
                                                <span class="text-lg font-semibold text-red-600">
                                                    {{ number_format($item->price, 0, ',', '.') }}₫
                                                </span>

                                                <!-- Stock Status -->
                                                @if($item->product->is_in_stock)
                                                    <span class="text-sm text-green-600">Còn hàng</span>
                                                @else
                                                    <span class="text-sm text-red-600">Hết hàng</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-3">
                                            <div class="flex items-center border rounded-lg">
                                                <button type="button" 
                                                        class="quantity-btn quantity-decrease p-2 hover:bg-gray-100"
                                                        data-product-id="{{ $item->product_id }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                
                                                <input type="number" 
                                                       class="quantity-input w-16 px-3 py-2 text-center border-0 focus:ring-0"
                                                       value="{{ $item->quantity }}"
                                                       min="1" 
                                                       max="99"
                                                       data-product-id="{{ $item->product_id }}">
                                                
                                                <button type="button" 
                                                        class="quantity-btn quantity-increase p-2 hover:bg-gray-100"
                                                        data-product-id="{{ $item->product_id }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="text-right">
                                            <div class="text-lg font-semibold text-gray-900 item-total">
                                                {{ number_format($item->total_price, 0, ',', '.') }}₫
                                            </div>
                                            <button type="button" 
                                                    class="remove-item text-sm text-red-600 hover:text-red-800 mt-2"
                                                    data-product-id="{{ $item->product_id }}">
                                                Xóa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Cart Actions -->
                        <div class="p-6 border-t bg-gray-50">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('products.index') }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    ← Tiếp tục mua sắm
                                </a>
                                
                                <button type="button" 
                                        id="clear-cart"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                    Xóa tất cả
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border sticky top-4">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tóm tắt đơn hàng</h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tạm tính:</span>
                                    <span class="font-medium cart-subtotal">{{ number_format($cartTotal, 0, ',', '.') }}₫</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phí vận chuyển:</span>
                                    <span class="font-medium">Tính khi thanh toán</span>
                                </div>
                                
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-red-600 cart-total">{{ number_format($cartTotal, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('orders.checkout') }}" 
                               class="w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors block">
                                Tiến hành thanh toán
                            </a>

                            <div class="mt-4 text-center">
                                <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Thanh toán bảo mật</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeCartFunctions();
});

// Reinit after SPA navigation
document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeCartFunctions, 100);
});

function initializeCartFunctions() {
    // Quantity controls
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const removeButtons = document.querySelectorAll('.remove-item');
    const clearCartBtn = document.getElementById('clear-cart');

    // Remove old listeners
    quantityInputs.forEach(input => {
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
    });

    quantityBtns.forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    // Quantity input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(this.value);
            
            if (quantity < 1) {
                this.value = 1;
                return;
            }
            
            updateCartQuantity(productId, quantity);
        });
    });

    // Quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
            let quantity = parseInt(input.value);

            if (this.classList.contains('quantity-decrease')) {
                quantity = Math.max(1, quantity - 1);
            } else {
                quantity = Math.min(99, quantity + 1);
            }

            input.value = quantity;
            updateCartQuantity(productId, quantity);
        });
    });

    // Remove item buttons
    removeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromCart(productId);
        });
    });

    // Clear cart button
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?')) {
                clearCart();
            }
        });
    }
}

// Update cart quantity
function updateCartQuantity(productId, quantity) {
    fetch('{{ route("cart.update") }}', {
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
            // Update item total
            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            const itemTotal = cartItem.querySelector('.item-total');
            itemTotal.textContent = data.item_total;

            // Update cart totals
            updateCartDisplay(data.cart_total, data.cart_count);
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

// Remove from cart
function removeFromCart(productId) {
    fetch('{{ route("cart.remove") }}', {
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
            // Remove item from DOM
            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            cartItem.remove();

            // Update cart display
            updateCartDisplay(data.cart_total, data.cart_count);

            // If cart is empty, reload page to show empty state
            if (data.cart_count === 0) {
                location.reload();
            }

            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

// Clear cart
function clearCart() {
    fetch('{{ route("cart.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

// Update cart display
function updateCartDisplay(total, count) {
    // Update totals
    const cartSubtotal = document.querySelector('.cart-subtotal');
    const cartTotal = document.querySelector('.cart-total');
    
    if (cartSubtotal) cartSubtotal.textContent = total;
    if (cartTotal) cartTotal.textContent = total;

    // Update global cart count (if exists)
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'inline' : 'none';
    });
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
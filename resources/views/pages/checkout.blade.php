@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Thanh toán</h1>
            <nav class="text-sm">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
                <span class="mx-2 text-gray-500">/</span>
                <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800">Giỏ hàng</a>
                <span class="mx-2 text-gray-500">/</span>
                <span class="text-gray-700">Thanh toán</span>
            </nav>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Customer Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Details -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin khách hàng</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Họ tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ old('customer_name', $user ? $user->display_name : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                                       required>
                                @error('customer_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_phone') border-red-500 @enderror"
                                       required>
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="customer_email" 
                                   name="customer_email" 
                                   value="{{ old('customer_email', $user ? $user->user_email : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                                   required>
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Địa chỉ giao hàng</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Địa chỉ cụ thể <span class="text-red-500">*</span>
                                </label>
                                <textarea id="shipping_address" 
                                          name="shipping_address" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address') border-red-500 @enderror"
                                          placeholder="Số nhà, tên đường..."
                                          required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tỉnh/Thành phố <span class="text-red-500">*</span>
                                    </label>
                                    <select id="shipping_city" 
                                            name="shipping_city"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_city') border-red-500 @enderror"
                                            required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        <option value="Hà Nội" {{ old('shipping_city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                        <option value="TP.HCM" {{ old('shipping_city') == 'TP.HCM' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                                        <option value="Đà Nẵng" {{ old('shipping_city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                        <option value="Hải Phòng" {{ old('shipping_city') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                                        <option value="Cần Thơ" {{ old('shipping_city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                    </select>
                                    @error('shipping_city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_district" class="block text-sm font-medium text-gray-700 mb-2">
                                        Quận/Huyện <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="shipping_district" 
                                           name="shipping_district" 
                                           value="{{ old('shipping_district') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_district') border-red-500 @enderror"
                                           placeholder="Quận/Huyện"
                                           required>
                                    @error('shipping_district')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_ward" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phường/Xã <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="shipping_ward" 
                                           name="shipping_ward" 
                                           value="{{ old('shipping_ward') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_ward') border-red-500 @enderror"
                                           placeholder="Phường/Xã"
                                           required>
                                    @error('shipping_ward')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="md:w-1/3">
                                <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mã bưu điện
                                </label>
                                <input type="text" 
                                       id="shipping_postal_code" 
                                       name="shipping_postal_code" 
                                       value="{{ old('shipping_postal_code') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Mã bưu điện (tùy chọn)">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Phương thức thanh toán</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="cod" 
                                       class="text-blue-600 focus:ring-blue-500"
                                       {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                    <div class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="bank_transfer" 
                                       class="text-blue-600 focus:ring-blue-500"
                                       {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="font-medium">Chuyển khoản ngân hàng</div>
                                    <div class="text-sm text-gray-500">Chuyển khoản trước khi giao hàng</div>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="momo" 
                                       class="text-blue-600 focus:ring-blue-500"
                                       disabled>
                                <div class="ml-3">
                                    <div class="font-medium">Ví MoMo</div>
                                    <div class="text-sm text-gray-500">Đang phát triển</div>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="vnpay" 
                                       class="text-blue-600 focus:ring-blue-500"
                                       disabled>
                                <div class="ml-3">
                                    <div class="font-medium">VNPay</div>
                                    <div class="text-sm text-gray-500">Đang phát triển</div>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ghi chú đơn hàng</h2>
                        
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Ghi chú thêm về đơn hàng của bạn...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border sticky top-4">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Đơn hàng của bạn</h3>
                            
                            <!-- Product List -->
                            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item->product->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                                             alt="{{ $item->product->name }}"
                                             class="h-12 w-12 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item->product->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}₫
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium">
                                            {{ number_format($item->total_price, 0, ',', '.') }}₫
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Order Totals -->
                            <div class="space-y-3 border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tạm tính:</span>
                                    <span class="font-medium">{{ number_format($cartTotal, 0, ',', '.') }}₫</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phí vận chuyển:</span>
                                    <span class="font-medium shipping-fee">{{ number_format($shippingFee, 0, ',', '.') }}₫</span>
                                </div>
                                
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span>Tổng cộng:</span>
                                        <span class="text-red-600 final-total">{{ number_format($cartTotal + $shippingFee, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors mt-6"
                                    id="submit-order">
                                Đặt hàng
                            </button>

                            <!-- Security Info -->
                            <div class="mt-4 text-center">
                                <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Thông tin được bảo mật</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCheckout();
});

// Reinit after SPA navigation  
document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeCheckout, 100);
});

function initializeCheckout() {
    const form = document.getElementById('checkout-form');
    const citySelect = document.getElementById('shipping_city');
    const districtInput = document.getElementById('shipping_district');
    const submitBtn = document.getElementById('submit-order');

    // Calculate shipping when city/district changes
    if (citySelect && districtInput) {
        [citySelect, districtInput].forEach(element => {
            element.addEventListener('change', calculateShipping);
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';
        });
    }
}

// Calculate shipping fee
function calculateShipping() {
    const city = document.getElementById('shipping_city').value;
    const district = document.getElementById('shipping_district').value;

    if (!city || !district) return;

    fetch('{{ route("api.orders.shipping") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            city: city,
            district: district
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update shipping fee display
            const shippingFeeElement = document.querySelector('.shipping-fee');
            const finalTotalElement = document.querySelector('.final-total');
            
            if (shippingFeeElement) {
                shippingFeeElement.textContent = data.shipping_fee_formatted;
            }
            
            if (finalTotalElement) {
                finalTotalElement.textContent = data.final_total_formatted;
            }
        }
    })
    .catch(error => {
        console.error('Error calculating shipping:', error);
    });
}
</script>
@endpush
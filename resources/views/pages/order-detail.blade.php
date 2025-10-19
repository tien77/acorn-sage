@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Chi tiết đơn hàng</h1>
                    <p class="text-gray-600">Đơn hàng #{{ $order->order_number }}</p>
                </div>

                <div class="text-right">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                        $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                        ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                        ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                        ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                        'bg-gray-100 text-gray-800')))
                    }}">
                        {{ $order->status_text }}
                    </div>
                </div>
            </div>

            <nav class="text-sm mt-4">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Trang chủ</a>
                @if($order->user_id)
                    <span class="mx-2 text-gray-500">/</span>
                    <a href="{{ route('orders.mine') }}" class="text-blue-600 hover:text-blue-800">Đơn hàng của tôi</a>
                @endif
                <span class="mx-2 text-gray-500">/</span>
                <span class="text-gray-700">Chi tiết đơn hàng</span>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Sản phẩm đã đặt</h2>
                    </div>

                    <div class="divide-y">
                        @foreach($order->items as $item)
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product)
                                            <img src="{{ $item->product->featured_image ?: '/wp-content/themes/sage/resources/images/placeholder.jpg' }}" 
                                                 alt="{{ $item->product_name }}"
                                                 class="h-16 w-16 object-cover rounded-lg">
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $item->product_name }}
                                                </a>
                                            @else
                                                {{ $item->product_name }}
                                                <span class="text-sm text-gray-500">(Sản phẩm không còn tồn tại)</span>
                                            @endif
                                        </h3>
                                        
                                        @if($item->product_sku)
                                            <p class="text-sm text-gray-500 mb-2">SKU: {{ $item->product_sku }}</p>
                                        @endif

                                        <div class="flex items-center space-x-4">
                                            <span class="text-sm text-gray-600">
                                                Số lượng: {{ $item->quantity }}
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                Đơn giá: {{ $item->formatted_unit_price }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900">
                                            {{ $item->formatted_total_price }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Status History -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Trạng thái đơn hàng</h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Current Status -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $order->status_text }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $order->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Order Created -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">Đơn hàng đã được tạo</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($order->admin_notes)
                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="font-medium text-yellow-800 mb-2">Ghi chú từ shop:</h4>
                                <p class="text-yellow-700">{{ $order->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Actions -->
                @if($order->canBeCancelled())
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Thao tác</h2>

                        <form action="{{ route('orders.cancel', $order) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Hủy đơn hàng
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mã đơn hàng:</span>
                            <span class="font-medium">{{ $order->order_number }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ngày đặt:</span>
                            <span class="font-medium">{{ $order->created_at->format('d/m/Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="font-medium">{{ $order->status_text }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Thanh toán:</span>
                            <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $order->payment_status_text }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600">Họ tên:</span>
                            <div class="font-medium">{{ $order->customer_name }}</div>
                        </div>
                        
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <div class="font-medium">{{ $order->customer_email }}</div>
                        </div>
                        
                        <div>
                            <span class="text-gray-600">Số điện thoại:</span>
                            <div class="font-medium">{{ $order->customer_phone }}</div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Địa chỉ giao hàng</h3>
                    
                    <div class="text-gray-700">
                        {{ $order->full_shipping_address }}
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thanh toán</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phương thức:</span>
                            <span class="font-medium">{{ $order->payment_method_text }}</span>
                        </div>

                        @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'pending')
                            <div class="mt-4">
                                <a href="{{ route('orders.payment-info', $order) }}" 
                                   class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                    Xem thông tin chuyển khoản
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Total -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tổng tiền</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-medium">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-medium text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Tổng cộng:</span>
                                <span class="text-red-600">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú</h3>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Back to Orders -->
        <div class="mt-8 text-center">
            @if($order->user_id)
                <a href="{{ route('orders.mine') }}" 
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Quay lại danh sách đơn hàng
                </a>
            @else
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    Tiếp tục mua sắm
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
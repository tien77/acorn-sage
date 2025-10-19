@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Chi tiết đơn hàng #{{ $order->id }}</h1>
      <p class="text-gray-600">
        Đặt hàng lúc {{ $order->created_at->format('d/m/Y H:i') }}
        @if($order->customer)
          bởi {{ $order->customer->display_name }}
        @endif
      </p>
    </div>
    <div class="flex space-x-3">
      <button onclick="printOrder()" 
              class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        In đơn hàng
      </button>
      <a href="{{ route('admin.orders.index') }}" 
         class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
        </svg>
        Quay lại danh sách
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Order Items -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Sản phẩm đã đặt</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($order->items as $item)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      @if($item->product && $item->product->featured_image)
                        <img src="{{ asset('storage/' . $item->product->featured_image) }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-12 h-12 object-cover rounded-lg mr-3">
                      @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                        </div>
                      @endif
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                        @if($item->product_sku)
                          <div class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</div>
                        @endif
                        @if($item->product && !$item->product->exists)
                          <div class="text-xs text-red-500">Sản phẩm đã bị xóa</div>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ number_format($item->price) }}₫
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $item->quantity }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ number_format($item->total) }}₫
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <!-- Order Total -->
        <div class="bg-gray-50 px-6 py-4">
          <div class="flex justify-end space-y-2">
            <div class="text-right space-y-1">
              <div class="flex justify-between items-center w-64">
                <span class="text-sm text-gray-600">Tạm tính:</span>
                <span class="text-sm text-gray-900">{{ number_format($order->subtotal) }}₫</span>
              </div>
              @if($order->tax_amount > 0)
                <div class="flex justify-between items-center w-64">
                  <span class="text-sm text-gray-600">Thuế:</span>
                  <span class="text-sm text-gray-900">{{ number_format($order->tax_amount) }}₫</span>
                </div>
              @endif
              @if($order->shipping_amount > 0)
                <div class="flex justify-between items-center w-64">
                  <span class="text-sm text-gray-600">Phí vận chuyển:</span>
                  <span class="text-sm text-gray-900">{{ number_format($order->shipping_amount) }}₫</span>
                </div>
              @endif
              @if($order->discount_amount > 0)
                <div class="flex justify-between items-center w-64">
                  <span class="text-sm text-gray-600">Giảm giá:</span>
                  <span class="text-sm text-red-600">-{{ number_format($order->discount_amount) }}₫</span>
                </div>
              @endif
              <div class="flex justify-between items-center w-64 pt-2 border-t border-gray-200">
                <span class="text-base font-semibold text-gray-900">Tổng cộng:</span>
                <span class="text-lg font-bold text-blue-600">{{ number_format($order->total) }}₫</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Notes -->
      @if($order->notes)
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú đơn hàng</h3>
          <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-700">{{ $order->notes }}</p>
          </div>
        </div>
      @endif

      <!-- Order History -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch sử đơn hàng</h3>
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
              </div>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-gray-900">Đơn hàng được tạo</h4>
                <span class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
              </div>
              <p class="text-sm text-gray-600">Đơn hàng #{{ $order->id }} đã được tạo với trạng thái "{{ $order->getStatusLabel() }}"</p>
            </div>
          </div>
          
          @if($order->updated_at != $order->created_at)
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </div>
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between">
                  <h4 class="text-sm font-medium text-gray-900">Đơn hàng được cập nhật</h4>
                  <span class="text-sm text-gray-500">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="text-sm text-gray-600">Trạng thái hiện tại: {{ $order->getStatusLabel() }}</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Order Status -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái đơn hàng</h3>
        
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
          @csrf
          @method('PUT')
          
          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái đơn hàng</label>
            <select name="status" 
                    onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
              <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
              <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
              <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
              <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
              <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
          </div>

          <!-- Payment Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái thanh toán</label>
            <select name="payment_status" 
                    onchange="this.form.submit()"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
              <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
              <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Thanh toán lỗi</option>
              <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
          </div>
        </form>

        <!-- Status Badge -->
        <div class="mt-4 pt-4 border-t border-gray-200">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Trạng thái hiện tại:</span>
            <span class="px-2 py-1 rounded-full text-xs font-medium
              @switch($order->status)
                @case('pending')
                  bg-yellow-100 text-yellow-800
                  @break
                @case('processing')
                  bg-blue-100 text-blue-800
                  @break
                @case('shipped')
                  bg-purple-100 text-purple-800
                  @break
                @case('delivered')
                  bg-green-100 text-green-800
                  @break
                @case('cancelled')
                  bg-red-100 text-red-800
                  @break
                @case('refunded')
                  bg-gray-100 text-gray-800
                  @break
                @default
                  bg-gray-100 text-gray-800
              @endswitch
            ">
              {{ $order->getStatusLabel() }}
            </span>
          </div>
          <div class="flex items-center justify-between text-sm mt-2">
            <span class="text-gray-600">Thanh toán:</span>
            <span class="px-2 py-1 rounded-full text-xs font-medium
              @switch($order->payment_status)
                @case('pending')
                  bg-yellow-100 text-yellow-800
                  @break
                @case('paid')
                  bg-green-100 text-green-800
                  @break
                @case('failed')
                  bg-red-100 text-red-800
                  @break
                @case('refunded')
                  bg-gray-100 text-gray-800
                  @break
                @default
                  bg-gray-100 text-gray-800
              @endswitch
            ">
              {{ $order->getPaymentStatusLabel() }}
            </span>
          </div>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng</h3>
        
        <div class="space-y-3">
          @if($order->customer)
            <div>
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tên khách hàng</label>
              <p class="text-sm text-gray-900">{{ $order->customer->display_name }}</p>
            </div>
            
            <div>
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
              <p class="text-sm text-gray-900">{{ $order->customer->user_email }}</p>
            </div>
            
            @if($order->customer->ID)
              <div>
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">ID khách hàng</label>
                <p class="text-sm text-gray-900">#{{ $order->customer->ID }}</p>
              </div>
            @endif
          @else
            <p class="text-sm text-gray-500">Khách hàng không còn tồn tại</p>
          @endif

          <!-- Contact Info -->
          @if($order->billing_phone)
            <div>
              <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Số điện thoại</label>
              <p class="text-sm text-gray-900">{{ $order->billing_phone }}</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Billing Address -->
      @if($order->billing_address)
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Địa chỉ thanh toán</h3>
          
          <div class="text-sm text-gray-900 leading-relaxed">
            {!! nl2br(e($order->billing_address)) !!}
          </div>
        </div>
      @endif

      <!-- Shipping Address -->
      @if($order->shipping_address && $order->shipping_address !== $order->billing_address)
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Địa chỉ giao hàng</h3>
          
          <div class="text-sm text-gray-900 leading-relaxed">
            {!! nl2br(e($order->shipping_address)) !!}
          </div>
        </div>
      @endif

      <!-- Order Info -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin đơn hàng</h3>
        
        <div class="space-y-3 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Mã đơn hàng:</span>
            <span class="text-gray-900 font-medium">#{{ $order->id }}</span>
          </div>
          
          <div class="flex justify-between">
            <span class="text-gray-600">Ngày đặt:</span>
            <span class="text-gray-900">{{ $order->created_at->format('d/m/Y') }}</span>
          </div>
          
          <div class="flex justify-between">
            <span class="text-gray-600">Thời gian:</span>
            <span class="text-gray-900">{{ $order->created_at->format('H:i') }}</span>
          </div>
          
          @if($order->payment_method)
            <div class="flex justify-between">
              <span class="text-gray-600">Phương thức thanh toán:</span>
              <span class="text-gray-900">{{ $order->payment_method }}</span>
            </div>
          @endif

          @if($order->shipping_method)
            <div class="flex justify-between">
              <span class="text-gray-600">Phương thức vận chuyển:</span>
              <span class="text-gray-900">{{ $order->shipping_method }}</span>
            </div>
          @endif
        </div>
      </div>

      <!-- Actions -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hành động</h3>
        
        <div class="space-y-3">
          <button onclick="sendOrderEmail()" 
                  class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
            Gửi email xác nhận
          </button>
          
          <button onclick="printOrder()" 
                  class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700">
            In đơn hàng
          </button>
          
          <button onclick="deleteOrder({{ $order->id }})" 
                  class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
            Xóa đơn hàng
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function printOrder() {
    window.print();
}

function sendOrderEmail() {
    if (!confirm('Gửi email xác nhận đơn hàng tới khách hàng?')) {
        return;
    }
    
    fetch(`/admin/orders/{{ $order->id }}/send-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email đã được gửi thành công!');
        } else {
            alert(data.message || 'Có lỗi xảy ra khi gửi email');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi gửi email');
    });
}

function deleteOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn xóa đơn hàng này?\n\nHành động này không thể hoàn tác!')) {
        return;
    }

    fetch(`/admin/orders/${orderId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/admin/orders';
        } else {
            alert(data.message || 'Có lỗi xảy ra khi xóa đơn hàng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa đơn hàng');
    });
}
</script>

<style media="print">
  @media print {
    .no-print {
      display: none !important;
    }
    
    body * {
      visibility: hidden;
    }
    
    .print-section, .print-section * {
      visibility: visible;
    }
    
    .print-section {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
</style>
@endpush
@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý đơn hàng</h1>
      <p class="text-gray-600">Xem và quản lý tất cả đơn hàng của khách hàng</p>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white p-4 rounded-lg shadow">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
      <!-- Search -->
      <div class="flex-1 min-w-64">
        <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
        <input type="text" 
               name="search" 
               value="{{ request('search') }}"
               placeholder="Số đơn hàng, tên khách hàng, email..." 
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <!-- Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
        <select name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">Tất cả</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
          <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
          <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Đã giao vận</option>
          <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
          <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
        </select>
      </div>

      <!-- Date Range -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
        <input type="date" 
               name="from_date" 
               value="{{ request('from_date') }}"
               class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
        <input type="date" 
               name="to_date" 
               value="{{ request('to_date') }}"
               class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <!-- Filter Button -->
      <div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
          Lọc
        </button>
        @if(request()->hasAny(['search', 'status', 'from_date', 'to_date']))
          <a href="{{ route('admin.orders.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">
            Xóa bộ lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Orders Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4">
                <div>
                  <div class="font-medium text-gray-900">#{{ $order->order_number }}</div>
                  <div class="text-sm text-gray-500">{{ $order->items->count() }} sản phẩm</div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div>
                  <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                  <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                  @if($order->customer_phone)
                    <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                {{ number_format($order->total_amount, 0, ',', '.') }}₫
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                  $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                  ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                  ($order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' :
                  ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                  ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                  ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))))
                }}">
                  @switch($order->status)
                    @case('pending') Chờ xử lý @break
                    @case('processing') Đang xử lý @break
                    @case('shipped') Đã giao vận @break
                    @case('delivered') Đã giao hàng @break
                    @case('completed') Hoàn thành @break
                    @case('cancelled') Đã hủy @break
                    @case('refunded') Đã hoàn tiền @break
                    @default {{ $order->status }}
                  @endswitch
                </span>
              </td>
              <td class="px-6 py-4">
                <div>
                  <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                    $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                    ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))
                  }}">
                    @switch($order->payment_status)
                      @case('pending') Chờ thanh toán @break
                      @case('paid') Đã thanh toán @break
                      @case('failed') Thất bại @break
                      @case('refunded') Đã hoàn tiền @break
                      @default {{ $order->payment_status }}
                    @endswitch
                  </span>
                  <div class="text-xs text-gray-500 mt-1">
                    @switch($order->payment_method)
                      @case('cod') COD @break
                      @case('bank_transfer') Chuyển khoản @break
                      @case('momo') MoMo @break
                      @case('vnpay') VNPay @break
                      @default {{ $order->payment_method }}
                    @endswitch
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ $order->created_at->format('d/m/Y H:i') }}
              </td>
              <td class="px-6 py-4 text-right text-sm font-medium">
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="text-blue-600 hover:text-blue-900 mr-3"
                   title="Xem chi tiết">
                  <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </a>

                <!-- Status Change Dropdown -->
                <div class="inline-block relative">
                  <button onclick="toggleStatusDropdown({{ $order->id }})" 
                          class="text-gray-600 hover:text-gray-900"
                          title="Cập nhật trạng thái">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                  </button>
                  
                  <div id="status-dropdown-{{ $order->id }}" 
                       class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                    <div class="py-1">
                      @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <button onclick="updateOrderStatus({{ $order->id }}, 'processing')" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                          Đang xử lý
                        </button>
                        <button onclick="updateOrderStatus({{ $order->id }}, 'shipped')" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                          Đã giao vận
                        </button>
                        <button onclick="updateOrderStatus({{ $order->id }}, 'delivered')" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                          Đã giao hàng
                        </button>
                        <button onclick="updateOrderStatus({{ $order->id }}, 'completed')" 
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                          Hoàn thành
                        </button>
                        <hr class="my-1">
                        <button onclick="updateOrderStatus({{ $order->id }}, 'cancelled')" 
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                          Hủy đơn hàng
                        </button>
                      @endif
                      
                      @if($order->payment_status === 'pending')
                        <hr class="my-1">
                        <button onclick="updatePaymentStatus({{ $order->id }}, 'paid')" 
                                class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                          Đánh dấu đã thanh toán
                        </button>
                      @endif
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</p>
                <p class="text-gray-600">Đợi khách hàng đặt hàng đầu tiên</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  @if($orders->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeOrderManagement();
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeOrderManagement, 100);
});

function initializeOrderManagement() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="status-dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
}

function toggleStatusDropdown(orderId) {
    const dropdown = document.getElementById(`status-dropdown-${orderId}`);
    
    // Hide other dropdowns
    document.querySelectorAll('[id^="status-dropdown-"]').forEach(d => {
        if (d !== dropdown) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

function updateOrderStatus(orderId, status) {
    if (!confirm(`Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này?`)) {
        return;
    }

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}

function updatePaymentStatus(orderId, paymentStatus) {
    if (!confirm(`Đánh dấu đơn hàng này đã được thanh toán?`)) {
        return;
    }

    // This would need to be implemented in the backend
    fetch(`/admin/orders/${orderId}/payment-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ payment_status: paymentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
    });
}
</script>
@endpush
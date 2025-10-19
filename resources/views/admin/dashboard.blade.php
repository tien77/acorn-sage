@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-gray-600">Tổng quan hoạt động website</p>
    </div>
    <div class="text-sm text-gray-500">
      {{ now()->format('d/m/Y H:i') }}
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Products -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_products']) }}</h3>
          <p class="text-sm text-gray-600">Tổng sản phẩm</p>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex items-center text-sm">
          <span class="text-green-600">{{ number_format($stats['published_products']) }} đã đăng</span>
          @if($stats['out_of_stock'] > 0)
            <span class="ml-2 text-red-600">{{ number_format($stats['out_of_stock']) }} hết hàng</span>
          @endif
        </div>
      </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-green-100 text-green-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_orders']) }}</h3>
          <p class="text-sm text-gray-600">Tổng đơn hàng</p>
        </div>
      </div>
      <div class="mt-4">
        <div class="flex items-center text-sm space-x-2">
          <span class="text-yellow-600">{{ number_format($stats['pending_orders']) }} chờ</span>
          <span class="text-blue-600">{{ number_format($stats['processing_orders']) }} xử lý</span>
          <span class="text-green-600">{{ number_format($stats['completed_orders']) }} hoàn thành</span>
        </div>
      </div>
    </div>

    <!-- Today's Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['today_orders']) }}</h3>
          <p class="text-sm text-gray-600">Đơn hàng hôm nay</p>
        </div>
      </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-red-100 text-red-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
          </svg>
        </div>
        <div class="ml-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
          <p class="text-sm text-gray-600">Tổng doanh thu</p>
        </div>
      </div>
      <div class="mt-4">
        <div class="text-sm text-gray-600">
          Tháng này: {{ number_format($stats['this_month_revenue'], 0, ',', '.') }}₫
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">Đơn hàng mới nhất</h3>
          <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm">Xem tất cả</a>
        </div>
      </div>
      <div class="p-6">
        @if($recent_orders->isEmpty())
          <p class="text-gray-500 text-center py-4">Chưa có đơn hàng nào</p>
        @else
          <div class="space-y-4">
            @foreach($recent_orders as $order)
              <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                  <div class="font-medium text-gray-900">#{{ $order->order_number }}</div>
                  <div class="text-sm text-gray-600">{{ $order->customer_name }}</div>
                  <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="text-right">
                  <div class="font-medium text-gray-900">{{ number_format($order->total_amount, 0, ',', '.') }}₫</div>
                  <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                    $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                    ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                    ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                  }}">
                    @switch($order->status)
                      @case('pending') Chờ xử lý @break
                      @case('processing') Đang xử lý @break
                      @case('completed') Hoàn thành @break
                      @case('cancelled') Đã hủy @break
                      @default {{ $order->status }}
                    @endswitch
                  </span>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">Sản phẩm sắp hết hàng</h3>
          <a href="{{ route('admin.products.index') }}?stock_status=low" class="text-blue-600 hover:text-blue-700 text-sm">Xem tất cả</a>
        </div>
      </div>
      <div class="p-6">
        @if($low_stock_products->isEmpty())
          <p class="text-gray-500 text-center py-4">Tất cả sản phẩm đều còn đủ hàng</p>
        @else
          <div class="space-y-4">
            @foreach($low_stock_products as $product)
              <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                  @if($product->featured_image)
                    <img src="{{ asset('storage/' . $product->featured_image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-10 h-10 object-cover rounded">
                  @else
                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </div>
                  @endif
                  <div class="ml-3">
                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                    <div class="text-sm text-gray-600">SKU: {{ $product->sku }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                    $product->stock_quantity <= 1 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'
                  }}">
                    {{ $product->stock_quantity }} còn lại
                  </span>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao tác nhanh</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <a href="{{ route('admin.products.create') }}" 
         class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        <div>
          <div class="font-medium text-gray-900">Thêm sản phẩm mới</div>
          <div class="text-sm text-gray-600">Tạo sản phẩm mới để bán</div>
        </div>
      </a>

      <a href="{{ route('admin.orders.index') }}?status=pending" 
         class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <div class="font-medium text-gray-900">Đơn hàng chờ xử lý</div>
          <div class="text-sm text-gray-600">{{ number_format($stats['pending_orders']) }} đơn cần xử lý</div>
        </div>
      </a>

      <a href="{{ route('admin.products.index') }}?stock_status=out_of_stock" 
         class="flex items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
        <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <div class="font-medium text-gray-900">Sản phẩm hết hàng</div>
          <div class="text-sm text-gray-600">{{ number_format($stats['out_of_stock']) }} sản phẩm cần nhập thêm</div>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection
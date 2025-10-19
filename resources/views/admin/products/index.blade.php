@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý sản phẩm</h1>
      <p class="text-gray-600">Thêm, sửa, xóa và quản lý tất cả sản phẩm</p>
    </div>
    <a href="{{ route('admin.products.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
      <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
      </svg>
      Thêm sản phẩm mới
    </a>
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
               placeholder="Tên sản phẩm hoặc SKU..." 
               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <!-- Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
        <select name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">Tất cả</option>
          <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã đăng</option>
          <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
        </select>
      </div>

      <!-- Stock Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tồn kho</label>
        <select name="stock_status" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">Tất cả</option>
          <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
          <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
          <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Sắp hết</option>
        </select>
      </div>

      <!-- Filter Button -->
      <div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
          Lọc
        </button>
        @if(request()->hasAny(['search', 'status', 'stock_status']))
          <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">
            Xóa bộ lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Bulk Actions -->
  <div class="bg-white p-4 rounded-lg shadow">
    <form id="bulk-form" method="POST" action="{{ route('admin.products.bulk-action') }}">
      @csrf
      <div class="flex items-center space-x-4">
        <select name="action" id="bulk-action" class="border border-gray-300 rounded-md px-3 py-2">
          <option value="">Chọn thao tác...</option>
          <option value="publish">Đăng sản phẩm</option>
          <option value="unpublish">Chuyển về bản nháp</option>
          <option value="feature">Đánh dấu nổi bật</option>
          <option value="unfeature">Bỏ đánh dấu nổi bật</option>
          <option value="delete">Xóa</option>
        </select>
        <button type="submit" id="bulk-submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 disabled:opacity-50" disabled>
          Thực hiện
        </button>
        <span id="selected-count" class="text-sm text-gray-600">0 sản phẩm được chọn</span>
      </div>
    </form>
  </div>

  <!-- Products Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left">
              <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($products as $product)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4">
                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center">
                  @if($product->featured_image)
                    <img src="{{ asset('storage/' . $product->featured_image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-12 h-12 object-cover rounded">
                  @else
                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                      <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                    </div>
                  @endif
                  <div class="ml-4">
                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                    @if($product->is_featured)
                      <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                        Nổi bật
                      </span>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">{{ $product->sku }}</td>
              <td class="px-6 py-4 text-sm text-gray-900">
                @if($product->sale_price)
                  <div class="flex flex-col">
                    <span class="text-red-600 font-medium">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                    <span class="text-gray-500 line-through text-xs">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                  </div>
                @else
                  <span class="font-medium">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @endif
              </td>
              <td class="px-6 py-4 text-sm">
                @if($product->manage_stock)
                  <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                    $product->stock_quantity <= 0 ? 'bg-red-100 text-red-800' : 
                    ($product->stock_quantity <= 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')
                  }}">
                    {{ $product->stock_quantity }} còn lại
                  </span>
                @else
                  <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                    Không quản lý
                  </span>
                @endif
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ 
                  $product->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                }}">
                  {{ $product->status === 'published' ? 'Đã đăng' : 'Bản nháp' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-500">
                {{ $product->created_at->format('d/m/Y') }}
              </td>
              <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                <a href="{{ route('products.show', $product->slug) }}" 
                   target="_blank"
                   class="text-gray-600 hover:text-gray-900" 
                   title="Xem sản phẩm">
                  <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="text-blue-600 hover:text-blue-900"
                   title="Chỉnh sửa">
                  <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <button onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                        class="text-red-600 hover:text-red-900"
                        title="Xóa">
                  <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Không có sản phẩm nào</p>
                <p class="text-gray-600 mb-4">Bắt đầu bằng cách tạo sản phẩm đầu tiên của bạn</p>
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  Thêm sản phẩm mới
                </a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  @if($products->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $products->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductManagement();
});

document.addEventListener('livewire:navigated', function() {
    setTimeout(initializeProductManagement, 100);
});

function initializeProductManagement() {
    const selectAllCheckbox = document.getElementById('select-all');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkForm = document.getElementById('bulk-form');
    const bulkAction = document.getElementById('bulk-action');
    const bulkSubmit = document.getElementById('bulk-submit');
    const selectedCount = document.getElementById('selected-count');

    if (!selectAllCheckbox) return;

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox functionality
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAll();
            updateBulkActions();
        });
    });

    // Bulk action form
    bulkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedProducts = Array.from(productCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        const action = bulkAction.value;
        
        if (!action || selectedProducts.length === 0) {
            return;
        }

        let confirmMessage = '';
        switch(action) {
            case 'delete':
                confirmMessage = `Bạn có chắc chắn muốn xóa ${selectedProducts.length} sản phẩm đã chọn?`;
                break;
            case 'publish':
                confirmMessage = `Đăng ${selectedProducts.length} sản phẩm đã chọn?`;
                break;
            case 'unpublish':
                confirmMessage = `Chuyển ${selectedProducts.length} sản phẩm về bản nháp?`;
                break;
            default:
                confirmMessage = `Thực hiện thao tác này cho ${selectedProducts.length} sản phẩm?`;
        }

        if (!confirm(confirmMessage)) {
            return;
        }

        // Add selected product IDs to form
        selectedProducts.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = id;
            bulkForm.appendChild(input);
        });

        bulkForm.submit();
    });

    function updateSelectAll() {
        const checkedCount = Array.from(productCheckboxes).filter(cb => cb.checked).length;
        selectAllCheckbox.checked = checkedCount === productCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < productCheckboxes.length;
    }

    function updateBulkActions() {
        const checkedCount = Array.from(productCheckboxes).filter(cb => cb.checked).length;
        selectedCount.textContent = `${checkedCount} sản phẩm được chọn`;
        bulkSubmit.disabled = checkedCount === 0 || !bulkAction.value;
    }

    bulkAction.addEventListener('change', updateBulkActions);
}

// Delete product function
window.deleteProduct = function(productId, productName) {
    if (!confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${productName}"?`)) {
        return;
    }

    fetch(`/admin/products/${productId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sản phẩm');
    });
};
</script>
@endpush
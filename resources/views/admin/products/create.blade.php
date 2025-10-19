@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Thêm sản phẩm mới</h1>
      <p class="text-gray-600">Tạo sản phẩm mới để bán trên website</p>
    </div>
    <a href="{{ route('admin.products.index') }}" 
       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
      <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
      </svg>
      Quay lại danh sách
    </a>
  </div>

  <!-- Form -->
  <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h3>
          
          <div class="space-y-4">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm *</label>
              <input type="text" 
                     name="name" 
                     value="{{ old('name') }}"
                     required
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
              @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- SKU -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
              <input type="text" 
                     name="sku" 
                     value="{{ old('sku') }}"
                     placeholder="Để trống để tạo tự động"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
              @error('sku')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Short Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả ngắn</label>
              <textarea name="short_description" 
                        rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
              @error('short_description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
              <textarea name="description" 
                        rows="8"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
              @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Giá bán</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Price -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc (₫) *</label>
              <input type="number" 
                     name="price" 
                     value="{{ old('price') }}"
                     min="0"
                     step="1000"
                     required
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
              @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Sale Price -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mại (₫)</label>
              <input type="number" 
                     name="sale_price" 
                     value="{{ old('sale_price') }}"
                     min="0"
                     step="1000"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('sale_price') border-red-500 @enderror">
              @error('sale_price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <!-- Inventory -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Quản lý kho</h3>
          
          <div class="space-y-4">
            <!-- Manage Stock -->
            <div class="flex items-center">
              <input type="checkbox" 
                     name="manage_stock" 
                     id="manage_stock"
                     value="1"
                     {{ old('manage_stock') ? 'checked' : '' }}
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <label for="manage_stock" class="ml-2 text-sm text-gray-700">
                Quản lý số lượng tồn kho
              </label>
            </div>

            <!-- Stock Quantity -->
            <div id="stock-quantity-section" style="display: none;">
              <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho</label>
              <input type="number" 
                     name="stock_quantity" 
                     value="{{ old('stock_quantity', 0) }}"
                     min="0"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_quantity') border-red-500 @enderror">
              @error('stock_quantity')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Stock Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái tồn kho *</label>
              <select name="stock_status" 
                      required
                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('stock_status') border-red-500 @enderror">
                <option value="in_stock" {{ old('stock_status') === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="out_of_stock" {{ old('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                <option value="on_backorder" {{ old('stock_status') === 'on_backorder' ? 'selected' : '' }}>Đặt trước</option>
              </select>
              @error('stock_status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Cân nặng (kg)</label>
              <input type="number" 
                     name="weight" 
                     value="{{ old('weight') }}"
                     min="0"
                     step="0.1"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('weight') border-red-500 @enderror">
              @error('weight')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Hình ảnh</h3>
          
          <div class="space-y-4">
            <!-- Featured Image -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
              <input type="file" 
                     name="featured_image" 
                     accept="image/*"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('featured_image') border-red-500 @enderror">
              @error('featured_image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">Định dạng: JPG, PNG, GIF. Tối đa 2MB</p>
            </div>

            <!-- Gallery Images -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Thư viện ảnh</label>
              <input type="file" 
                     name="gallery_images[]" 
                     accept="image/*"
                     multiple
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('gallery_images.*') border-red-500 @enderror">
              @error('gallery_images.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">Có thể chọn nhiều ảnh. Mỗi ảnh tối đa 2MB</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Publish -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Xuất bản</h3>
          
          <div class="space-y-4">
            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái *</label>
              <select name="status" 
                      required
                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Xuất bản</option>
              </select>
            </div>

            <!-- Featured -->
            <div class="flex items-center">
              <input type="checkbox" 
                     name="is_featured" 
                     id="is_featured"
                     value="1"
                     {{ old('is_featured') ? 'checked' : '' }}
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <label for="is_featured" class="ml-2 text-sm text-gray-700">
                Sản phẩm nổi bật
              </label>
            </div>
          </div>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
          
          <div class="space-y-2 max-h-60 overflow-y-auto">
            @forelse($categories as $category)
              <div class="flex items-center">
                <input type="checkbox" 
                       name="categories[]" 
                       value="{{ $category->id }}"
                       id="category_{{ $category->id }}"
                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                  {{ $category->full_name }}
                </label>
              </div>
            @empty
              <p class="text-gray-500 text-sm">Chưa có danh mục nào. 
                <a href="#" class="text-blue-600 hover:text-blue-700">Tạo danh mục mới</a>
              </p>
            @endforelse
          </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="space-y-3">
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Tạo sản phẩm
            </button>
            
            <a href="{{ route('admin.products.index') }}" 
               class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 block text-center">
              Hủy bỏ
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductForm();
});

function initializeProductForm() {
    const manageStockCheckbox = document.getElementById('manage_stock');
    const stockQuantitySection = document.getElementById('stock-quantity-section');

    if (manageStockCheckbox) {
        // Initial state
        toggleStockQuantity();

        manageStockCheckbox.addEventListener('change', toggleStockQuantity);

        function toggleStockQuantity() {
            if (manageStockCheckbox.checked) {
                stockQuantitySection.style.display = 'block';
            } else {
                stockQuantitySection.style.display = 'none';
            }
        }
    }

    // Auto-format price inputs
    const priceInputs = document.querySelectorAll('input[name="price"], input[name="sale_price"]');
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-digit characters except for the decimal point
            let value = this.value.replace(/[^\d]/g, '');
            
            // Format with thousands separator
            if (value) {
                this.value = parseInt(value).toLocaleString('vi-VN');
            }
        });

        input.addEventListener('blur', function() {
            // Convert back to plain number for form submission
            this.value = this.value.replace(/[^\d]/g, '');
        });
    });
}
</script>
@endpush
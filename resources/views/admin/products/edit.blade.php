@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa sản phẩm</h1>
      <p class="text-gray-600">Cập nhật thông tin sản phẩm: {{ $product->name }}</p>
    </div>
    <div class="flex space-x-3">
      <a href="{{ route('products.show', $product->slug) }}" 
         target="_blank"
         class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Xem sản phẩm
      </a>
      <a href="{{ route('admin.products.index') }}" 
         class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
        </svg>
        Quay lại danh sách
      </a>
    </div>
  </div>

  <!-- Form -->
  <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
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
                     value="{{ old('name', $product->name) }}"
                     required
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- SKU -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
              <input type="text" 
                     name="sku" 
                     value="{{ old('sku', $product->sku) }}"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              @error('sku')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Short Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả ngắn</label>
              <textarea name="short_description" 
                        rows="3"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('short_description', $product->short_description) }}</textarea>
              @error('short_description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
              <textarea name="description" 
                        rows="8"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $product->description) }}</textarea>
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
                     value="{{ old('price', $product->price) }}"
                     min="0"
                     step="1000"
                     required
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Sale Price -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giá khuyến mại (₫)</label>
              <input type="number" 
                     name="sale_price" 
                     value="{{ old('sale_price', $product->sale_price) }}"
                     min="0"
                     step="1000"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                     {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <label for="manage_stock" class="ml-2 text-sm text-gray-700">
                Quản lý số lượng tồn kho
              </label>
            </div>

            <!-- Stock Quantity -->
            <div id="stock-quantity-section" style="{{ old('manage_stock', $product->manage_stock) ? 'display: block;' : 'display: none;' }}">
              <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn kho</label>
              <input type="number" 
                     name="stock_quantity" 
                     value="{{ old('stock_quantity', $product->stock_quantity) }}"
                     min="0"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              @error('stock_quantity')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <!-- Stock Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái tồn kho *</label>
              <select name="stock_status" 
                      required
                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="in_stock" {{ old('stock_status', $product->stock_status) === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="out_of_stock" {{ old('stock_status', $product->stock_status) === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                <option value="on_backorder" {{ old('stock_status', $product->stock_status) === 'on_backorder' ? 'selected' : '' }}>Đặt trước</option>
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
                     value="{{ old('weight', $product->weight) }}"
                     min="0"
                     step="0.1"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
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
            <!-- Current Featured Image -->
            @if($product->featured_image)
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện hiện tại</label>
                <div class="flex items-center space-x-4">
                  <img src="{{ asset('storage/' . $product->featured_image) }}" 
                       alt="{{ $product->name }}" 
                       class="w-20 h-20 object-cover rounded-lg border">
                  <div>
                    <p class="text-sm text-gray-600">{{ basename($product->featured_image) }}</p>
                    <button type="button" 
                            onclick="removeCurrentImage('featured')"
                            class="text-red-600 hover:text-red-800 text-sm">
                      Xóa ảnh hiện tại
                    </button>
                  </div>
                </div>
              </div>
            @endif

            <!-- Featured Image Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $product->featured_image ? 'Thay đổi ảnh đại diện' : 'Ảnh đại diện' }}
              </label>
              <input type="file" 
                     name="featured_image" 
                     accept="image/*"
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
              @error('featured_image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">Định dạng: JPG, PNG, GIF. Tối đa 2MB</p>
            </div>

            <!-- Current Gallery Images -->
            @if($product->gallery_images && count($product->gallery_images) > 0)
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thư viện ảnh hiện tại</label>
                <div class="grid grid-cols-4 gap-4">
                  @foreach($product->gallery_images as $index => $image)
                    <div class="relative">
                      <img src="{{ asset('storage/' . $image) }}" 
                           alt="Gallery image {{ $index + 1 }}" 
                           class="w-full h-20 object-cover rounded-lg border">
                      <button type="button" 
                              onclick="removeGalleryImage({{ $index }})"
                              class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        ×
                      </button>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- Gallery Images Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $product->gallery_images && count($product->gallery_images) > 0 ? 'Thêm ảnh vào thư viện' : 'Thư viện ảnh' }}
              </label>
              <input type="file" 
                     name="gallery_images[]" 
                     accept="image/*"
                     multiple
                     class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>Xuất bản</option>
              </select>
            </div>

            <!-- Featured -->
            <div class="flex items-center">
              <input type="checkbox" 
                     name="is_featured" 
                     id="is_featured"
                     value="1"
                     {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <label for="is_featured" class="ml-2 text-sm text-gray-700">
                Sản phẩm nổi bật
              </label>
            </div>

            <!-- Stats -->
            <div class="text-sm text-gray-600 space-y-1">
              <div>Lượt xem: {{ number_format($product->views_count) }}</div>
              <div>Đã bán: {{ number_format($product->sales_count) }}</div>
              <div>Tạo: {{ $product->created_at->format('d/m/Y H:i') }}</div>
              <div>Cập nhật: {{ $product->updated_at->format('d/m/Y H:i') }}</div>
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
                       {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                  {{ $category->full_name ?? $category->name }}
                </label>
              </div>
            @empty
              <p class="text-gray-500 text-sm">Chưa có danh mục nào.</p>
            @endforelse
          </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="space-y-3">
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Cập nhật sản phẩm
            </button>
            
            <a href="{{ route('admin.products.index') }}" 
               class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 block text-center">
              Hủy bỏ
            </a>

            <button type="button" 
                    onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')"
                    class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
              Xóa sản phẩm
            </button>
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
    initializeProductEditForm();
});

function initializeProductEditForm() {
    const manageStockCheckbox = document.getElementById('manage_stock');
    const stockQuantitySection = document.getElementById('stock-quantity-section');

    if (manageStockCheckbox) {
        manageStockCheckbox.addEventListener('change', function() {
            if (this.checked) {
                stockQuantitySection.style.display = 'block';
            } else {
                stockQuantitySection.style.display = 'none';
            }
        });
    }
}

function removeCurrentImage(type) {
    if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        return;
    }
    
    // Add hidden input to mark for deletion
    const form = document.querySelector('form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remove_' + type + '_image';
    input.value = '1';
    form.appendChild(input);
    
    // Hide the current image section
    event.target.closest('div').style.display = 'none';
}

function removeGalleryImage(index) {
    if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        return;
    }
    
    // Add hidden input to mark for deletion
    const form = document.querySelector('form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remove_gallery_images[]';
    input.value = index;
    form.appendChild(input);
    
    // Hide the image
    event.target.closest('div').style.display = 'none';
}

function deleteProduct(productId, productName) {
    if (!confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${productName}"?\n\nHành động này không thể hoàn tác!`)) {
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
            window.location.href = '/admin/products';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sản phẩm');
    });
}
</script>
@endpush
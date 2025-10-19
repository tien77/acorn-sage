@extends('admin.layout')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý danh mục</h1>
      <p class="text-gray-600">Tổng cộng {{ $categories->count() }} danh mục</p>
    </div>
    <div>
      <button onclick="openCreateModal()" 
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Thêm danh mục
      </button>
    </div>
  </div>

  <!-- Categories Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục cha</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số sản phẩm</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($categories as $category)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="w-10 h-10 object-cover rounded-lg mr-3">
                  @else
                    <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                      </svg>
                    </div>
                  @endif
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                    @if($category->description)
                      <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $category->slug }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $category->parent ? $category->parent->name : '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $category->products_count ?? 0 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                  {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                  {{ $category->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <button onclick="editCategory({{ $category->id }})" 
                          class="text-indigo-600 hover:text-indigo-900">
                    Sửa
                  </button>
                  <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                          class="text-red-600 hover:text-red-900">
                    Xóa
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-lg font-medium">Chưa có danh mục nào</p>
                <p class="text-sm">Bắt đầu bằng cách tạo danh mục đầu tiên</p>
                <button onclick="openCreateModal()" 
                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                  Thêm danh mục
                </button>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Create/Edit Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Thêm danh mục mới</h3>
      </div>
      
      <form id="categoryForm" enctype="multipart/form-data" class="p-6 space-y-4">
        @csrf
        <input type="hidden" id="categoryId" name="category_id">
        <input type="hidden" id="formMethod" name="_method" value="POST">
        
        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục *</label>
          <input type="text" 
                 id="categoryName" 
                 name="name" 
                 required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Slug -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
          <input type="text" 
                 id="categorySlug" 
                 name="slug"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
          <p class="text-xs text-gray-500 mt-1">Để trống để tự động tạo từ tên danh mục</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
          <textarea id="categoryDescription" 
                    name="description" 
                    rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <!-- Parent Category -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục cha</label>
          <select id="categoryParent" 
                  name="parent_id"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">-- Không có --</option>
            @foreach($categories->where('parent_id', null) as $parent)
              <option value="{{ $parent->id }}">{{ $parent->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Image -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh</label>
          <input type="file" 
                 id="categoryImage" 
                 name="image" 
                 accept="image/*"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
          <div id="currentImage" class="mt-2 hidden">
            <img id="currentImagePreview" src="" alt="Current image" class="w-20 h-20 object-cover rounded-lg">
          </div>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
          <select id="categoryStatus" 
                  name="status"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="active">Hoạt động</option>
            <option value="inactive">Không hoạt động</option>
          </select>
        </div>
      </form>
      
      <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
        <button onclick="closeModal()" 
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
          Hủy
        </button>
        <button onclick="saveCategory()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
          Lưu
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let isEditing = false;
let currentCategoryId = null;

function openCreateModal() {
    isEditing = false;
    currentCategoryId = null;
    
    document.getElementById('modalTitle').textContent = 'Thêm danh mục mới';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('currentImage').classList.add('hidden');
    
    document.getElementById('categoryModal').classList.remove('hidden');
}

function editCategory(categoryId) {
    isEditing = true;
    currentCategoryId = categoryId;
    
    document.getElementById('modalTitle').textContent = 'Chỉnh sửa danh mục';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('categoryId').value = categoryId;
    
    // Fetch category data
    fetch(`/admin/categories/${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categorySlug').value = category.slug;
                document.getElementById('categoryDescription').value = category.description || '';
                document.getElementById('categoryParent').value = category.parent_id || '';
                document.getElementById('categoryStatus').value = category.status;
                
                if (category.image) {
                    document.getElementById('currentImagePreview').src = `/storage/${category.image}`;
                    document.getElementById('currentImage').classList.remove('hidden');
                }
                
                document.getElementById('categoryModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin danh mục');
        });
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryForm').reset();
}

function saveCategory() {
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);
    
    // Add CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    const url = isEditing ? `/admin/categories/${currentCategoryId}` : '/admin/categories';
    const method = isEditing ? 'PUT' : 'POST';
    
    // For PUT method, we need to convert FormData to regular data for fetch
    if (isEditing) {
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (key !== 'image' || value.size > 0) {
                data[key] = value;
            }
        }
        
        fetch(url, {
            method: 'POST', // Laravel form method spoofing
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                location.reload(); // Reload to show updated data
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi lưu danh mục');
        });
    } else {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                location.reload(); // Reload to show new data
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tạo danh mục');
        });
    }
}

function deleteCategory(categoryId, categoryName) {
    if (!confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"?\n\nHành động này không thể hoàn tác!`)) {
        return;
    }

    fetch(`/admin/categories/${categoryId}`, {
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
            alert(data.message || 'Có lỗi xảy ra khi xóa danh mục');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa danh mục');
    });
}

// Auto-generate slug from name
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('categoryName');
    const slugInput = document.getElementById('categorySlug');
    
    nameInput.addEventListener('input', function() {
        if (!isEditing) { // Only auto-generate for new categories
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });
});

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endpush
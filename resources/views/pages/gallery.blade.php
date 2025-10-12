@extends('layouts.app')

@section('content')

{{-- Gallery Header --}}
<div class="bg-gradient-to-r from-purple-600 to-blue-600 dark:from-blue-800 dark:to-indigo-900 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">
        Bộ sưu tập hình ảnh
      </h1>
      <p class="text-xl text-purple-100 dark:text-blue-400">
        Khám phá những hình ảnh đẹp nhất từ khắp nơi trên thế giới
      </p>
    </div>
  </div>
</div>

{{-- Filter Tabs --}}
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-16 z-40">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex space-x-1 py-4 overflow-x-auto">
      <button 
        class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 whitespace-nowrap bg-blue-600 text-white"
        data-category="all"
      >
        Tất cả
      </button>
      
      @foreach($categories as $category)
        <button 
          class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 whitespace-nowrap text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700"
          data-category="{{ $category }}"
        >
          {{ ucfirst($category) }}
        </button>
      @endforeach
    </div>
  </div>
</div>

{{-- Gallery Grid --}}
<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Loading State --}}
    <div id="loading" class="hidden text-center py-12">
      <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-blue-600 dark:bg-blue-700">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Đang tải...
      </div>
    </div>

    {{-- Gallery Images --}}
    <div id="gallery-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
      @foreach($images as $image)
        <div class="gallery-item group" data-category="{{ $image['category'] }}">
          <div class="relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 bg-white dark:bg-gray-800">
            {{-- Image --}}
            <div class="aspect-square overflow-hidden">
              <img 
                src="{{ $image['url'] }}" 
                alt="{{ $image['title'] }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer"
                onclick="openModal('{{ $image['id'] }}', '{{ $image['url'] }}', '{{ $image['title'] }}', '{{ $image['description'] }}')"
                loading="lazy"
              >
            </div>
            
            {{-- Overlay Info --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                <h3 class="font-semibold text-sm mb-1">{{ $image['title'] }}</h3>
                <p class="text-xs text-gray-200 dark:text-gray-300">{{ $image['description'] }}</p>
              </div>
            </div>

            {{-- Category Badge --}}
            <div class="absolute top-2 left-2 bg-black/50 text-white px-2 py-1 rounded text-xs font-medium">
              {{ ucfirst($image['category']) }}
            </div>

            {{-- Action Buttons --}}
            <div class="absolute top-2 right-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <button 
                onclick="openModal('{{ $image['id'] }}', '{{ $image['url'] }}', '{{ $image['title'] }}', '{{ $image['description'] }}')"
                class="p-1.5 bg-black/50 text-white rounded-full hover:bg-black/70 transition-colors duration-200"
                title="Xem chi tiết"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              
              <a 
                href="{{ $image['url'] }}" 
                download="{{ $image['title'] }}"
                class="p-1.5 bg-black/50 text-white rounded-full hover:bg-black/70 transition-colors duration-200"
                title="Tải xuống"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-6 4H6a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v6a2 2 0 01-2 2z" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- No Results --}}
    <div id="no-results" class="hidden text-center py-12">
      <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0120 12c0-4.411-3.589-8-8-8s-8 3.589-8 8a7.962 7.962 0 002 5.291m2-1.705a6 6 0 118 0" />
      </svg>
      <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không tìm thấy hình ảnh</h3>
      <p class="text-gray-500 dark:text-gray-400">Thử chọn category khác để xem thêm hình ảnh.</p>
    </div>
  </div>
</div>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="background-color: rgba(0, 0, 0, 0.8);">
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    {{-- Background overlay --}}
    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

    {{-- Modal content --}}
    <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-10">
      {{-- Modal header --}}
      <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="flex items-center justify-between">
          <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
            Image Detail
          </h3>
          <button onclick="closeModal()" class="text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      {{-- Modal body --}}
      <div class="px-4 pb-4 sm:px-6 sm:pb-6 bg-white dark:bg-gray-800">
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden mb-4" style="min-height: 300px;">
          <img id="modal-image" src="" alt="" class="w-full h-auto max-h-96 object-contain mx-auto" style="display: block;">
        </div>
        
        <div>
          <h4 id="modal-image-title" class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2"></h4>
          <p id="modal-image-description" class="text-gray-600 dark:text-gray-300 mb-4"></p>
          
          <div class="flex space-x-3">
            <a 
              id="modal-download-link" 
              href="#" 
              download
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-6 4H6a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4v6a2 2 0 01-2 2z" />
              </svg>
              Tải xuống
            </a>
            
            <button 
              onclick="shareImage()" 
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
              </svg>
              Chia sẻ
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- JavaScript --}}
@push('scripts')
<script>
// Function to initialize gallery functionality
function initializeGallery() {
  console.log('Initializing gallery...');
  
  // Filter functionality
  const filterButtons = document.querySelectorAll('.filter-btn');
  const galleryItems = document.querySelectorAll('.gallery-item');
  const galleryContainer = document.getElementById('gallery-container');
  const loading = document.getElementById('loading');
  const noResults = document.getElementById('no-results');

  if (filterButtons.length === 0) {
    console.log('No filter buttons found, skipping filter initialization');
    return;
  }

  console.log('Found', filterButtons.length, 'filter buttons');

  // Remove existing event listeners (if any)
  filterButtons.forEach(button => {
    button.removeEventListener('click', handleFilterClick);
  });

  // Add new event listeners
  filterButtons.forEach(button => {
    button.addEventListener('click', handleFilterClick);
  });

  function handleFilterClick(e) {
    const button = e.target;
    const category = button.dataset.category;
    
    console.log('Filter clicked:', category);
    
    // Update active button
    filterButtons.forEach(btn => {
      btn.classList.remove('bg-blue-600', 'text-white');
      btn.classList.add('text-gray-600', 'dark:text-gray-300', 'hover:text-blue-600', 'dark:hover:text-blue-400', 'hover:bg-blue-50', 'dark:hover:bg-gray-700');
    });
    
    button.classList.add('bg-blue-600', 'text-white');
    button.classList.remove('text-gray-600', 'dark:text-gray-300', 'hover:text-blue-600', 'dark:hover:text-blue-400', 'hover:bg-blue-50', 'dark:hover:bg-gray-700');

    // Show loading
    if (loading) loading.classList.remove('hidden');
    if (galleryContainer) galleryContainer.classList.add('hidden');
    if (noResults) noResults.classList.add('hidden');

    // Simulate loading delay
    setTimeout(() => {
      let visibleCount = 0;
      
      galleryItems.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
          item.style.display = 'block';
          visibleCount++;
        } else {
          item.style.display = 'none';
        }
      });

      if (loading) loading.classList.add('hidden');
      if (galleryContainer) galleryContainer.classList.remove('hidden');
      
      if (visibleCount === 0) {
        if (noResults) noResults.classList.remove('hidden');
      } else {
        if (noResults) noResults.classList.add('hidden');
      }
    }, 300);
  }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initializeGallery);

// Initialize on Livewire navigation (SPA)
document.addEventListener('livewire:navigated', function() {
  console.log('Livewire navigated, reinitializing gallery...');
  // Small delay to ensure DOM is ready
  setTimeout(initializeGallery, 100);
});

// Fallback: Initialize when page becomes visible (for wire:navigate)
document.addEventListener('visibilitychange', function() {
  if (!document.hidden && window.location.pathname === '/gallery') {
    console.log('Page became visible, reinitializing gallery...');
    setTimeout(initializeGallery, 100);
  }
});

// Global modal functions (persist through SPA navigation)
window.openModal = function(id, url, title, description) {
  console.log('Opening modal with:', { id, url, title, description });
  
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modal-image');
  const modalTitle = document.getElementById('modal-image-title');
  const modalDescription = document.getElementById('modal-image-description');
  const modalDownloadLink = document.getElementById('modal-download-link');
  
  if (!modal) {
    console.error('Modal not found!');
    return;
  }
  
  // Set image source and wait for it to load
  if (modalImage) {
    modalImage.onload = function() {
      console.log('Image loaded successfully');
    };
    
    modalImage.onerror = function() {
      console.error('Failed to load image:', url);
    };
    
    modalImage.src = url;
    modalImage.alt = title;
  }
  
  if (modalTitle) modalTitle.textContent = title;
  if (modalDescription) modalDescription.textContent = description;
  if (modalDownloadLink) {
    modalDownloadLink.href = url;
    modalDownloadLink.download = title;
  }
  
  // Show modal
  modal.classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
  
  console.log('Modal should be visible now');
};

window.closeModal = function() {
  console.log('Closing modal');
  const modal = document.getElementById('imageModal');
  if (modal) {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    console.log('Modal closed');
  }
};

window.shareImage = function() {
  const modalImage = document.getElementById('modal-image');
  const modalTitle = document.getElementById('modal-image-title');
  
  if (!modalImage || !modalTitle) return;
  
  const imageUrl = modalImage.src;
  const imageTitle = modalTitle.textContent;
  
  if (navigator.share) {
    navigator.share({
      title: imageTitle,
      url: imageUrl
    });
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(imageUrl).then(() => {
      alert('Link hình ảnh đã được sao chép!');
    });
  }
};

// Global keyboard event listener (only add once)
if (!window.galleryKeyboardListenerAdded) {
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && window.closeModal) {
      window.closeModal();
    }
  });
  window.galleryKeyboardListenerAdded = true;
}
</script>
@endpush

@endsection
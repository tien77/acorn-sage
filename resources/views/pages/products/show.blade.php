@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" wire:navigate class="hover:text-indigo-600">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('products.index') }}" wire:navigate class="hover:text-indigo-600">Sản phẩm</a></li>
            @if($product->categories->count() > 0)
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.category', $product->categories->first()->slug) }}" wire:navigate class="hover:text-indigo-600">{{ $product->categories->first()->name }}</a></li>
            @endif
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-w-1 aspect-h-1">
                @if($product->featured_image_url)
                    <img id="main-image" src="{{ $product->featured_image_url }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-cover rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Gallery Images -->
            @if($product->gallery_image_urls && count($product->gallery_image_urls) > 0)
                <div class="grid grid-cols-4 gap-2">
                    @if($product->featured_image_url)
                        <img src="{{ $product->featured_image_url }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-20 object-cover rounded cursor-pointer border-2 border-indigo-500 gallery-thumb"
                             onclick="changeMainImage('{{ $product->featured_image_url }}', this)">
                    @endif
                    
                    @foreach($product->gallery_image_urls as $image)
                        <img src="{{ $image }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-20 object-cover rounded cursor-pointer border-2 border-transparent hover:border-gray-300 gallery-thumb"
                             onclick="changeMainImage('{{ $image }}', this)">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <!-- Categories -->
            @if($product->categories->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($product->categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}" wire:navigate
                           class="inline-block text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full hover:bg-indigo-100 transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Product Name -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                @if($product->is_featured)
                    <span class="inline-block mt-2 bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                        ⭐ Sản phẩm nổi bật
                    </span>
                @endif
            </div>

            <!-- SKU -->
            <div class="text-sm text-gray-600">
                <span class="font-medium">Mã sản phẩm:</span> {{ $product->sku }}
            </div>

            <!-- Price -->
            <div class="space-y-2">
                <div class="flex items-center space-x-4">
                    <span class="text-3xl font-bold text-indigo-600">
                        {{ number_format($product->display_price, 0, ',', '.') }}đ
                    </span>
                    @if($product->is_on_sale)
                        <span class="text-xl text-gray-500 line-through">
                            {{ number_format($product->price, 0, ',', '.') }}đ
                        </span>
                        <span class="bg-red-100 text-red-800 text-sm px-2 py-1 rounded">
                            Giảm {{ $product->discount_percent }}%
                        </span>
                    @endif
                </div>
            </div>

            <!-- Stock Status -->
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full {{ $product->is_in_stock ? 'bg-green-500' : 'bg-red-500' }}"></div>
                <span class="text-sm font-medium text-{{ $product->is_in_stock ? 'green' : 'red' }}-600">
                    {{ $product->stock_status_text }}
                </span>
                @if($product->manage_stock && $product->is_in_stock)
                    <span class="text-sm text-gray-600">
                        ({{ $product->stock_quantity }} sản phẩm có sẵn)
                    </span>
                @endif
            </div>

            <!-- Short Description -->
            @if($product->short_description)
                <div class="prose prose-sm">
                    <p class="text-gray-700">{{ $product->short_description }}</p>
                </div>
            @endif

            <!-- Product Details -->
            <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                @if($product->weight)
                    <div class="flex justify-between">
                        <span class="font-medium">Trọng lượng:</span>
                        <span>{{ $product->weight }}kg</span>
                    </div>
                @endif
                
                @if($product->dimensions)
                    <div class="flex justify-between">
                        <span class="font-medium">Kích thước:</span>
                        <span>{{ $product->dimensions }}</span>
                    </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="font-medium">Lượt xem:</span>
                    <span>{{ $product->views_count }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Đã bán:</span>
                    <span>{{ $product->sales_count }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                @if($product->is_in_stock)
                    <button class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6m1.5-6h10m0 0v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6z"></path>
                        </svg>
                        Thêm vào giỏ hàng
                    </button>
                @else
                    <button disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-medium cursor-not-allowed">
                        Hết hàng
                    </button>
                @endif
                
                <div class="flex space-x-4">
                    <button class="flex-1 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Yêu thích
                    </button>
                    
                    <button class="flex-1 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Chia sẻ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
        <div class="mb-12">
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="description">
                        Mô tả sản phẩm
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="reviews">
                        Đánh giá (0)
                    </button>
                </nav>
            </div>

            <div class="tab-content" id="description">
                <div class="prose max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <div class="tab-content hidden" id="reviews">
                <p class="text-gray-600">Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
        </div>
    @endif

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition group">
                        <div class="relative overflow-hidden rounded-t-lg">
                            <a href="{{ $relatedProduct->url }}" wire:navigate>
                                @if($relatedProduct->featured_image_url)
                                    <img src="{{ $relatedProduct->featured_image_url }}" 
                                         alt="{{ $relatedProduct->name }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            @if($relatedProduct->is_on_sale)
                                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    -{{ $relatedProduct->discount_percent }}%
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3>
                                <a href="{{ $relatedProduct->url }}" wire:navigate
                                   class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>

                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-lg font-bold text-gray-900">
                                    {{ number_format($relatedProduct->display_price, 0, ',', '.') }}đ
                                </span>
                                @if($relatedProduct->is_on_sale)
                                    <span class="text-sm text-gray-500 line-through">
                                        {{ number_format($relatedProduct->price, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Recently Viewed -->
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm đã xem</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($recentlyViewed as $viewedProduct)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition group">
                        <div class="relative overflow-hidden rounded-t-lg">
                            <a href="{{ $viewedProduct->url }}" wire:navigate>
                                @if($viewedProduct->featured_image_url)
                                    <img src="{{ $viewedProduct->featured_image_url }}" 
                                         alt="{{ $viewedProduct->name }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <div class="p-4">
                            <h3>
                                <a href="{{ $viewedProduct->url }}" wire:navigate
                                   class="font-medium text-gray-900 hover:text-indigo-600 transition">
                                    {{ $viewedProduct->name }}
                                </a>
                            </h3>

                            <div class="mt-2">
                                <span class="text-lg font-bold text-gray-900">
                                    {{ number_format($viewedProduct->display_price, 0, ',', '.') }}đ
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
// Gallery image switching
function changeMainImage(src, element) {
    document.getElementById('main-image').src = src;
    
    // Update border styling
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('border-indigo-500');
        thumb.classList.add('border-transparent');
    });
    
    element.classList.remove('border-transparent');
    element.classList.add('border-indigo-500');
}

// Tab switching
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Update button styles
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600');

            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
});
</script>
@endpush

@endsection
<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>Admin Dashboard - {{ get_bloginfo('name', 'display') }}</title>
  
  @php(wp_head())
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
  
  <style>
    .admin-sidebar {
      min-height: calc(100vh - 64px);
    }
  </style>
</head>

<body @php(body_class('admin-body'))>
  @php(wp_body_open())

  <div id="app" class="min-h-screen bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 fixed w-full top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-900">
                <svg class="w-8 h-8 text-blue-600 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Admin Dashboard
              </a>
            </div>
          </div>

          <!-- User menu -->
          <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a4.5 4.5 0 01-1.5-3.4V7a6 6 0 00-12 0v2.1c0 1.4-.6 2.7-1.5 3.4L3 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
            </button>

            <!-- View site -->
            <a href="{{ route('home') }}" target="_blank" class="text-gray-500 hover:text-gray-700" title="Xem trang web">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>

            <!-- User dropdown -->
            <div class="relative">
              <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                  {{ substr(wp_get_current_user()->display_name ?? 'A', 0, 1) }}
                </div>
                <span class="hidden md:block">{{ wp_get_current_user()->display_name ?? 'Admin' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex pt-16">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-sm admin-sidebar">
        <div class="p-4">
          <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
              </svg>
              Dashboard
            </a>

            <!-- Products -->
            <div class="space-y-1">
              <a href="{{ route('admin.products.index') }}" 
                 class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Sản phẩm
              </a>
              
              @if(request()->routeIs('admin.products.*'))
                <div class="ml-8 space-y-1">
                  <a href="{{ route('admin.products.index') }}" 
                     class="block px-3 py-1 text-sm {{ request()->routeIs('admin.products.index') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Tất cả sản phẩm
                  </a>
                  <a href="{{ route('admin.products.create') }}" 
                     class="block px-3 py-1 text-sm {{ request()->routeIs('admin.products.create') ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Thêm mới
                  </a>
                </div>
              @endif
            </div>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
              Danh mục
            </a>

            <!-- Orders -->
            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Đơn hàng
            </a>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-4"></div>

            <!-- Settings -->
            <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Cài đặt
            </a>
          </nav>
        </div>
      </aside>

      <!-- Main content -->
      <main class="flex-1 p-6">
        <!-- Notifications -->
        @if(session('success'))
          <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  @php(wp_footer())
  @livewireScripts
  @stack('scripts')
  
  <script>
    // Global notification function
    window.showNotification = function(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
      }`;
      notification.textContent = message;

      document.body.appendChild(notification);

      setTimeout(() => {
        notification.remove();
      }, 3000);
    };
  </script>
</body>
</html>
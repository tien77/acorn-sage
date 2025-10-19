<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <meta name="color-scheme" content="light dark">

    <script>
      // ✅ IMMEDIATE Dark Mode - Zero Flash Solution
      (function() {
        // Đọc theme ngay lập tức
        let theme = 'light';
        try {
          const stored = localStorage.getItem('theme');
          if (stored === 'dark' || stored === 'light') {
            theme = stored;
          } else {
            // Fallback to system preference
            theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
          }
        } catch (e) {
          // localStorage không available, fallback to system
          theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }
        
        // Apply IMMEDIATELY - trước khi browser render
        const html = document.documentElement;
        const isDark = theme === 'dark';
        
        if (isDark) {
          html.classList.add('dark');
          html.setAttribute('data-theme', 'dark');
        } else {
          html.classList.remove('dark');
          html.setAttribute('data-theme', 'light');
        }
        
        html.style.colorScheme = isDark ? 'dark' : 'light';
        
        // Global persistent functions
        window.THEME_STATE = {
          current: theme,
          isDark: isDark
        };
        
        window.applyDarkMode = function(newTheme) {
          if (newTheme && (newTheme === 'dark' || newTheme === 'light')) {
            window.THEME_STATE.current = newTheme;
          }
          
          const theme = window.THEME_STATE.current;
          const isDark = theme === 'dark';
          const html = document.documentElement;
          
          // Force apply - không dùng toggle để tránh race condition
          if (isDark) {
            html.classList.add('dark');
            html.setAttribute('data-theme', 'dark');
          } else {
            html.classList.remove('dark');
            html.setAttribute('data-theme', 'light');
          }
          
          html.style.colorScheme = isDark ? 'dark' : 'light';
          window.THEME_STATE.isDark = isDark;
          
          // Update meta theme-color
          const metaTheme = document.querySelector('meta[name="theme-color"]');
          if (metaTheme) {
            metaTheme.setAttribute('content', isDark ? '#111827' : '#ffffff');
          }
          
          // Store to localStorage
          try {
            localStorage.setItem('theme', theme);
          } catch (e) {}
          
          return theme;
        };
        
        window.getCurrentTheme = function() {
          return window.THEME_STATE.current;
        };
        
        window.toggleDarkMode = function() {
          const newTheme = window.THEME_STATE.current === 'dark' ? 'light' : 'dark';
          return window.applyDarkMode(newTheme);
        };
      })();
    </script>
    
    @php(do_action('get_header'))
    @php(wp_head())
    
    <script>
      // Tell Alpine to wait for Livewire
      window.deferLoadingAlpine = function (callback) {
        window.addEventListener('livewire:load', callback)
      }
    </script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @vite(
      ['resources/css/app.css', 'resources/js/app.js'],
      null,
      ['data-navigate-once' => true]   {{-- ✅ rất quan trọng --}}
    )
    
    @livewireStyles

  </head>

  <body @php(body_class("dark:bg-gray-900 bg-white text-gray-900 dark:text-gray-100 transition-colors duration-200"))>
    @php(wp_body_open())
    
    {{-- ✅ BODY-LEVEL Dark Mode Protection - chạy ngay khi body load --}}
    <script>
      (function() {
        if (window.applyDarkMode) {
          window.applyDarkMode();
        }
      })();
    </script>

    <div id="app">

      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
          {{ $slot ?? '' }} {{-- dành cho Livewire, sẽ được chèn vào Slot --}}
      </main>

      {{-- @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif --}}

      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())

    @livewireScripts

    {{-- Stack for custom scripts from child views --}}
    @stack('scripts')

    {{-- Global Cart Script --}}
    <script>
    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCartCount();
    });

    // Load cart count after SPA navigation
    document.addEventListener('livewire:navigated', function() {
        setTimeout(loadCartCount, 100);
    });

    // Global function to load cart count
    window.loadCartCount = function() {
        // Only load if not already loading
        if (window.cartCountLoading) return;
        window.cartCountLoading = true;

        fetch('{{ route("api.cart.info") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount(data.cart_count);
            }
        })
        .catch(error => {
            console.log('Cart count load failed:', error);
        })
        .finally(() => {
            window.cartCountLoading = false;
        });
    };

    // Global function to update cart count
    window.updateCartCount = function(count) {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(el => {
            el.textContent = count;
            el.style.display = count > 0 ? 'inline-block' : 'none';
        });
    };
    </script>

  </body>
</html>

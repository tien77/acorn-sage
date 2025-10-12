<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

  <body @php(body_class())>
    @php(wp_body_open())

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

  </body>
</html>

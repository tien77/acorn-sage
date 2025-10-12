<header class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      
      {{-- Logo/Brand --}}
      <div class="flex-shrink-0">
        <a class="text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200" 
           href="{{ home_url('/') }}">
          {!! $siteName !!}
        </a>
      </div>

      {{-- Desktop Navigation --}}
      <nav class="hidden md:block" aria-label="Main Navigation">
        <div class="flex space-x-8">
          <a href="{{ home_url('/') }}"
             class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('/') ? 'text-blue-600 bg-blue-50' : '' }}"
             wire:navigate>
            Home
          </a>
          <a href="{{ route('gallery.index') }}" 
             class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('gallery*') ? 'text-blue-600 bg-blue-50' : '' }}"
             wire:navigate>
            Gallery
          </a>
          <a href="/prices" 
             class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('prices') ? 'text-blue-600 bg-blue-50' : '' }}"
             wire:navigate>
            Prices
          </a>
          <a href="/contact" 
             class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->is('contact') ? 'text-blue-600 bg-blue-50' : '' }}"
             wire:navigate>
            Contact
          </a>
        </div>
      </nav>

      {{-- Auth Status --}}
      <div class="hidden md:block">
        <livewire:auth-status />
      </div>

      {{-- Mobile menu button --}}
      <div class="md:hidden">
        <button type="button" 
                class="mobile-menu-button bg-white p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                aria-controls="mobile-menu" 
                aria-expanded="false">
          <span class="sr-only">Mở menu</span>
          {{-- Menu icon --}}
          <svg class="menu-icon block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          {{-- Close icon --}}
          <svg class="close-icon hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    {{-- Mobile Navigation --}}
    <div class="mobile-menu hidden md:hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 border-t border-gray-200">
        <a href="{{ home_url('/') }}"
           class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 {{ request()->is('/') ? 'text-blue-600 bg-blue-50' : '' }}"
           wire:navigate>
          Home
        </a>
        <a href="{{ route('gallery.index') }}" 
           class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 {{ request()->is('gallery*') ? 'text-blue-600 bg-blue-50' : '' }}"
           wire:navigate>
          Gallery
        </a>
        <a href="/prices" 
           class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 {{ request()->is('prices') ? 'text-blue-600 bg-blue-50' : '' }}"
           wire:navigate>
          Prices
        </a>
        <a href="/contact" 
           class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 {{ request()->is('contact') ? 'text-blue-600 bg-blue-50' : '' }}"
           wire:navigate>
          Contact
        </a>
        
        {{-- Mobile Auth --}}
        <div class="border-t border-gray-300 pt-3 mt-3">
          <livewire:auth-status />
        </div>
      </div>
    </div>
  </div>
</header>
<div class="flex items-center space-x-4">
    @if($isLoggedIn)
        {{-- Hiển thị khi đã đăng nhập --}}
        <div class="flex items-center space-x-3">
            <img 
                src="{{ $userAvatar }}" 
                alt="{{ $userDisplayName }}" 
                class="w-8 h-8 rounded-full"
            >
            
            <div class="hidden md:block">
                <p class="text-sm font-medium text-gray-900">{{ $userDisplayName }}</p>
                <p class="text-xs text-gray-500">{{ $userEmail }}</p>
            </div>
            
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <div 
                    x-show="open" 
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                >
                    <a 
                        href="{{ route('dashboard') }}" 
                        wire:navigate
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                        Dashboard
                    </a>
                    
                    <a 
                        href="{{ route('contacts.mine') }}" 
                        wire:navigate
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                        Liên hệ của tôi
                    </a>
                    
                    <a 
                        href="{{ get_admin_url() }}" 
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                        WordPress Admin
                    </a>
                    
                    <hr class="my-1">
                    
                    <button 
                        wire:click="logout" 
                        class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50"
                    >
                        Đăng xuất
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Hiển thị khi chưa đăng nhập --}}
        <div class="flex items-center space-x-3">
            <a 
                href="{{ route('login') }}" 
                wire:navigate
                class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
            >
                Đăng nhập
            </a>
            
            @if(get_option('users_can_register'))
                <a 
                    href="{{ wp_registration_url() }}" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out"
                >
                    Đăng ký
                </a>
            @endif
        </div>
    @endif
</div>

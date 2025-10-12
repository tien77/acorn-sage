<div x-data="darkToggle()" x-init="init()" class="flex items-center">
  <button
    type="button"
    @click="toggle()"
    class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg
           bg-gray-100 hover:bg-gray-200
           dark:bg-gray-800 dark:hover:bg-gray-700
           transition-colors duration-200
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    title="Toggle Dark Mode">

    {{-- Sun icon (hiện khi LIGHT) --}}
    <svg
      x-show="!isDark"
      x-cloak
      class="sun-icon w-5 h-5 text-yellow-500 transition-all duration-300"
      viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
      <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707z" clip-rule="evenodd"/>
    </svg>

    {{-- Moon icon (hiện khi DARK) --}}
    <svg
      x-show="isDark"
      x-cloak
      class="moon-icon w-5 h-5 text-blue-400 transition-all duration-300 absolute"
      viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
      <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
    </svg>
  </button>
</div>

<script>
  // Alpine component: dark mode thuần client - sử dụng global state
  function darkToggle() {
    return {
      get mode() { 
        return window.THEME_STATE ? window.THEME_STATE.current : 'light'; 
      },
      get isDark() { 
        return window.THEME_STATE ? window.THEME_STATE.isDark : false; 
      },

      init() {
        // Sync với global state ngay lập tức
        this.sync();
        
        // Listen for system preference changes
        const mq = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
        if (mq) {
          const handleChange = () => {
            // Chỉ apply system preference nếu user chưa set manual
            try {
              const stored = localStorage.getItem('theme');
              if (!stored) {
                this.set(mq.matches ? 'dark' : 'light');
              }
            } catch (e) {}
          };
          
          if (mq.addEventListener) mq.addEventListener('change', handleChange);
          else if (mq.addListener) mq.addListener(handleChange);
        }
        
        // Listen for localStorage changes from other tabs
        window.addEventListener('storage', (e) => {
          if (e.key === 'theme') {
            this.sync();
          }
        });
        
        // Listen for Livewire navigation
        document.addEventListener('livewire:navigated', () => {
          this.$nextTick(() => this.sync());
        });
        
        // Periodic sync as backup
        setInterval(() => this.sync(), 1000);
      },

      toggle() {
        if (window.toggleDarkMode) {
          window.toggleDarkMode();
        }
        // Force Alpine reactivity update
        this.$nextTick(() => {});
      },

      set(mode) {
        if (window.applyDarkMode) {
          window.applyDarkMode(mode);
        }
        this.$nextTick(() => {});
      },

      sync() {
        // Force Alpine to update its reactive properties
        this.$nextTick(() => {});
      },
    };
  }
</script>

// import.meta.glob([
//   '../images/**',
//   '../fonts/**',
// ]);


import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// Import CSS chính
import '../css/app.css'

// Import assets
import.meta.glob([
  '../images/**',
  '../fonts/**',
])

// Import JS custom
import { initNavigation } from './components/nav-menu.js';

// Alpine: import tĩnh, KHÔNG gọi Alpine.start() ở đây
import Alpine from 'alpinejs'

// Nếu vì lý do nào đó window.Alpine đã tồn tại, đừng ghi đè
if (!window.Alpine) {
  window.Alpine = Alpine
}

// (tuỳ chọn) Khai báo store/plugin ở sự kiện alpine:init
document.addEventListener('alpine:init', () => {
  // Alpine.plugin(...);
  // Alpine.store('demo', { count: 0, inc(){ this.count++ } })
})

// khởi động initNavigation lần đầu
document.addEventListener('DOMContentLoaded', initNavigation);

// Re-init Alpine sau mỗi lần SPA navigate
document.addEventListener('livewire:navigated', () => {
  console.log('livewire:navigated - reinit Alpine & navigation');
  window.Alpine.flushAndStopDeferringMutations?.()
  window.Alpine.initTree(document.body)
  initNavigation();

  NProgress.start();
});


document.addEventListener('livewire:navigated', () => {
  NProgress.done()
});
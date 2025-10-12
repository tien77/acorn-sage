// import.meta.glob([
//   '../images/**',
//   '../fonts/**',
// ]);


import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

// Import CSS chính
import '../css/app.css'

NProgress.configure({
  showSpinner: false,   // tắt spinner cho gọn
  trickleSpeed: 120,    // nhỏ giọt chậm hơn
  minimum: 0.08         // bắt đầu thấp để mượt
});


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

let lwSpa = { timer: null, started: false };
// Bắt đầu khi SPA điều hướng (không phải lần load đầu)
document.addEventListener('livewire:navigating', () => {
  // anti-flicker: chỉ start nếu >120ms
  lwSpa.started = false;
  lwSpa.timer = setTimeout(() => {
    NProgress.start();
    lwSpa.started = true;
  }, 120);
});


// Re-init Alpine sau mỗi lần SPA navigate
document.addEventListener('livewire:navigated', () => {
  console.log('livewire:navigated - reinit Alpine & navigation');
  window.Alpine.flushAndStopDeferringMutations?.()
  window.Alpine.initTree(document.body)
  initNavigation();

  if (lwSpa.timer) clearTimeout(lwSpa.timer);
  // nếu đã start thì đợi 90ms cho DOM ổn định rồi mới done
  if (lwSpa.started) {
    setTimeout(() => NProgress.done(), 90);
  }
  lwSpa.started = false;

});


// Nếu có lỗi trong khi SPA nav
document.addEventListener('livewire:navigate-error', () => {
  if (lwSpa.timer) clearTimeout(lwSpa.timer);
  if (lwSpa.started) NProgress.done();
  lwSpa.started = false;
});
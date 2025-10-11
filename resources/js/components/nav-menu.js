// resources/js/navigation.js
let teardown = null;

export function initNavigation() {
  // Tránh nhân đôi listener khi re-init
  if (teardown) teardown();

  const doc = document;
  const root = doc; // có thể đổi sang header cụ thể nếu bạn muốn

  const qs = (sel) => root.querySelector(sel);

  // Query lại node mỗi lần init (DOM có thể đã thay đổi sau SPA)
  let mobileMenuButton = qs('.mobile-menu-button');
  let mobileMenu       = qs('.mobile-menu');
  let menuIcon         = qs('.menu-icon');
  let closeIcon        = qs('.close-icon');

  if (!mobileMenuButton || !mobileMenu) {
    teardown = null;
    return; // không có menu trên page này
  }

  const onToggle = () => {
    const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
    mobileMenuButton.setAttribute('aria-expanded', (!isExpanded).toString());
    mobileMenu.classList.toggle('hidden');
    menuIcon?.classList.toggle('hidden');
    closeIcon?.classList.toggle('hidden');
  };

  const onDocClick = (e) => {
    // nếu bấm chính nút toggle thì bỏ qua, đã xử lý ở onToggle
    if (mobileMenuButton.contains(e.target)) return;

    // nếu click ngoài menu -> đóng
    if (!mobileMenu.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      menuIcon?.classList.remove('hidden');
      closeIcon?.classList.add('hidden');
    }
  };

  mobileMenuButton.addEventListener('click', onToggle);
  document.addEventListener('click', onDocClick);

  // Hàm huỷ để lần re-init sau không bị chồng listener
  teardown = () => {
    mobileMenuButton?.removeEventListener('click', onToggle);
    document.removeEventListener('click', onDocClick);
  };
}

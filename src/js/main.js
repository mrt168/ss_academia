// SS Academia JavaScript
// =======================

document.addEventListener('DOMContentLoaded', () => {
  // Mobile menu toggle
  const menuToggle = document.querySelector('.header__menu-toggle');
  const mobileMenu = document.querySelector('.header__mobile-menu');

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      menuToggle.classList.toggle('is-active');
      mobileMenu.classList.toggle('is-active');

      // Update aria-label for accessibility
      const isOpen = menuToggle.classList.contains('is-active');
      menuToggle.setAttribute('aria-label', isOpen ? 'メニューを閉じる' : 'メニューを開く');

      // Lock body scroll when menu is open
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });
  }

  // Close mobile menu when clicking outside
  if (menuToggle && mobileMenu) {
    document.addEventListener('click', (e) => {
      if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
        menuToggle.classList.remove('is-active');
        mobileMenu.classList.remove('is-active');
        menuToggle.setAttribute('aria-label', 'メニューを開く');
        document.body.style.overflow = '';
      }
    });

    // Close mobile menu when clicking nav links
    const mobileNavLinks = mobileMenu.querySelectorAll('.header__mobile-nav-link');
    mobileNavLinks.forEach(link => {
      link.addEventListener('click', () => {
        menuToggle.classList.remove('is-active');
        mobileMenu.classList.remove('is-active');
        menuToggle.setAttribute('aria-label', 'メニューを開く');
        document.body.style.overflow = '';
      });
    });
  }

  // Smooth scroll for navigation links
  const navLinks = document.querySelectorAll('.nav__link[href^="#"]');
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      if (targetId && targetId !== '#') {
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          e.preventDefault();
          targetElement.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });
});

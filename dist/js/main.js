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

  // Smooth scroll for navigation links with header offset
  const scrollToTarget = (targetId) => {
    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      const header = document.querySelector('.header');
      const nav = document.querySelector('.nav');
      const headerHeight = header ? header.offsetHeight : 80;
      const navHeight = nav ? nav.offsetHeight : 60;
      // Total offset: header + nav bar + 20px padding
      const totalOffset = headerHeight + navHeight + 20;
      const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - totalOffset;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    }
  };

  // Desktop nav links
  const navLinks = document.querySelectorAll('.nav__link[href^="#"]');
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      if (targetId && targetId !== '#') {
        e.preventDefault();
        scrollToTarget(targetId);
      }
    });
  });

  // Mobile nav links
  const mobileNavLinksScroll = document.querySelectorAll('.header__mobile-nav-link[href^="#"]');
  mobileNavLinksScroll.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetId = link.getAttribute('href');
      if (targetId && targetId !== '#') {
        e.preventDefault();
        // Small delay to allow menu to close first
        setTimeout(() => {
          scrollToTarget(targetId);
        }, 100);
      }
    });
  });

  // Auto-check form checkboxes based on CTA button clicked
  const checkFormCheckbox = (value) => {
    const checkbox = document.querySelector(`input[name="ご用件[]"][value="${value}"]`);
    if (checkbox) {
      checkbox.checked = true;
    }
  };

  // Handle CTA buttons for 資料ダウンロード
  const downloadButtons = document.querySelectorAll('.btn--primary[href="#contact"], .cta-card--download .btn');
  downloadButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      scrollToTarget('#contact');
      setTimeout(() => {
        checkFormCheckbox('資料ダウンロード');
      }, 500);
    });
  });

  // Handle CTA buttons for 無料相談
  const consultationButtons = document.querySelectorAll('.btn--secondary[href="#contact"], .cta-card--consultation .btn');
  consultationButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      scrollToTarget('#contact');
      setTimeout(() => {
        checkFormCheckbox('無料相談');
      }, 500);
    });
  });
});

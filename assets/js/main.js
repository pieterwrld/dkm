(function () {
  'use strict';

  // === HEADER SCROLL ===
  function initHeaderScroll() {
    var header = document.querySelector('.site-header');
    var announcement = document.querySelector('.announcement-bar');
    if (!header) return;

    var announcementHeight = announcement ? announcement.offsetHeight : 0;
    var scrolled = false;

    function onScroll() {
      var shouldBeScrolled = window.scrollY > announcementHeight;
      if (shouldBeScrolled !== scrolled) {
        scrolled = shouldBeScrolled;
        header.classList.toggle('site-header--scrolled', scrolled);
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // === MOBILE MENU ===
  function initMobileMenu() {
    var menu = document.querySelector('.mobile-menu');
    var openBtn = document.querySelector('.header-hamburger');
    var closeBtn = document.querySelector('.mobile-menu__close');
    if (!menu || !openBtn) return;

    function open() {
      menu.classList.add('mobile-menu--open');
      document.body.classList.add('menu-open');
      openBtn.setAttribute('aria-expanded', 'true');
      menu.setAttribute('aria-hidden', 'false');
    }

    function close() {
      menu.classList.remove('mobile-menu--open');
      document.body.classList.remove('menu-open');
      openBtn.setAttribute('aria-expanded', 'false');
      menu.setAttribute('aria-hidden', 'true');
    }

    openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu.classList.contains('mobile-menu--open')) {
        close();
      }
    });
  }

  // === SEARCH OVERLAY ===
  function initSearchOverlay() {
    var overlay = document.querySelector('.search-overlay');
    var openBtns = document.querySelectorAll('[data-open-search]');
    var closeBtn = document.querySelector('.search-overlay__close');
    var input = document.querySelector('.search-overlay__input');
    if (!overlay) return;

    function open() {
      overlay.classList.add('search-overlay--open');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.classList.add('search-open');
      if (input) {
        setTimeout(function () { input.focus(); }, 100);
      }
    }

    function close() {
      overlay.classList.remove('search-overlay--open');
      overlay.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('search-open');
    }

    openBtns.forEach(function (btn) { btn.addEventListener('click', open); });
    if (closeBtn) closeBtn.addEventListener('click', close);

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) close();
    });

    if (input) {
      input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && input.value.trim()) {
          window.location.href = '/?s=' + encodeURIComponent(input.value.trim()) + '&post_type=product';
        }
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('search-overlay--open')) {
        close();
      }
    });
  }

  // === MINI CART ===
  function initMiniCart() {
    var cart = document.querySelector('.mini-cart');
    var overlay = document.querySelector('.mini-cart__overlay');
    var openBtns = document.querySelectorAll('[data-open-cart]');
    var closeBtn = document.querySelector('.mini-cart__close');
    if (!cart) return;

    function open() {
      cart.classList.add('mini-cart--open');
      if (overlay) overlay.classList.add('mini-cart__overlay--open');
      document.body.classList.add('cart-open');
    }

    function close() {
      cart.classList.remove('mini-cart--open');
      if (overlay) overlay.classList.remove('mini-cart__overlay--open');
      document.body.classList.remove('cart-open');
    }

    openBtns.forEach(function (btn) { btn.addEventListener('click', open); });
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (overlay) overlay.addEventListener('click', close);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && cart.classList.contains('mini-cart--open')) {
        close();
      }
    });

    document.body.addEventListener('added_to_cart', function () {
      open();
    });
  }

  // === SCROLL REVEAL ===
  function initScrollReveal() {
    var elements = document.querySelectorAll('.scroll-reveal');
    if (!elements.length || !('IntersectionObserver' in window)) {
      elements.forEach(function (el) { el.classList.add('scroll-reveal--visible'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          var delay = el.getAttribute('data-reveal-delay');
          if (delay) {
            el.style.transitionDelay = delay + 'ms';
          }
          el.classList.add('scroll-reveal--visible');
          observer.unobserve(el);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    elements.forEach(function (el) { observer.observe(el); });
  }

  // === PRODUCT IMAGE HOVER ===
  function initProductImageHover() {
    if (!window.matchMedia('(hover: hover)').matches) return;

    var cards = document.querySelectorAll('.product-card');
    cards.forEach(function (card) {
      var hoverImg = card.querySelector('.product-card__img--hover');
      if (!hoverImg) return;

      card.addEventListener('mouseenter', function () {
        hoverImg.style.opacity = '1';
      });

      card.addEventListener('mouseleave', function () {
        hoverImg.style.opacity = '0';
      });
    });
  }

  // === REVIEWS CAROUSEL ===
  function initReviewsCarousel() {
    var carousel = document.querySelector('.reviews-carousel');
    var prevBtn = document.querySelector('.reviews-nav button[data-dir="prev"]');
    var nextBtn = document.querySelector('.reviews-nav button[data-dir="next"]');
    if (!carousel) return;

    var scrollAmount = 320;

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      });
    }

    var isDragging = false;
    var startX = 0;
    var scrollLeft = 0;

    carousel.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse') {
        isDragging = true;
        startX = e.pageX - carousel.offsetLeft;
        scrollLeft = carousel.scrollLeft;
        carousel.style.cursor = 'grabbing';
      }
    });

    carousel.addEventListener('pointermove', function (e) {
      if (!isDragging) return;
      e.preventDefault();
      var x = e.pageX - carousel.offsetLeft;
      carousel.scrollLeft = scrollLeft - (x - startX) * 1.5;
    });

    carousel.addEventListener('pointerup', function () {
      isDragging = false;
      carousel.style.cursor = '';
    });

    carousel.addEventListener('pointerleave', function () {
      isDragging = false;
      carousel.style.cursor = '';
    });
  }

  // === SMOOTH SCROLL ===
  function initSmoothScroll() {
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a[href^="#"]');
      if (!link) return;
      var hash = link.getAttribute('href');
      if (hash === '#' || hash === '') return;
      var target = document.querySelector(hash);
      if (!target) return;
      e.preventDefault();
      window.scrollTo({
        top: target.getBoundingClientRect().top + window.scrollY - 100,
        behavior: 'smooth'
      });
    });
  }

  // === BACK TO TOP ===
  function initBackToTop() {
    var btn = document.querySelector('.back-to-top');
    if (!btn) return;

    var visible = false;

    function onScroll() {
      var shouldBeVisible = window.scrollY > 500;
      if (shouldBeVisible !== visible) {
        visible = shouldBeVisible;
        btn.classList.toggle('back-to-top--visible', visible);
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // === QUANTITY BUTTONS ===
  function initQuantityButtons() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.dkm-qty-btn');
      if (!btn) return;
      var wrapper = btn.closest('.quantity');
      if (!wrapper) return;
      var input = wrapper.querySelector('.qty');
      if (!input) return;

      var val = parseInt(input.value, 10) || 1;
      var min = parseInt(input.getAttribute('min'), 10) || 1;
      var max = parseInt(input.getAttribute('max'), 10) || 9999;
      var step = parseInt(input.getAttribute('step'), 10) || 1;

      if (btn.classList.contains('dkm-qty-minus')) {
        val = Math.max(min, val - step);
      } else if (btn.classList.contains('dkm-qty-plus')) {
        val = Math.min(max, val + step);
      }

      input.value = val;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  }

  // === WRAP QUANTITY INPUTS ===
  function wrapQuantityInputs() {
    var inputs = document.querySelectorAll('.woocommerce .quantity .qty');
    inputs.forEach(function (input) {
      if (input.parentNode.querySelector('.dkm-qty-btn')) return;

      var minus = document.createElement('button');
      minus.type = 'button';
      minus.className = 'dkm-qty-btn dkm-qty-minus';
      minus.setAttribute('aria-label', 'Verlaag aantal');
      minus.textContent = '−';

      var plus = document.createElement('button');
      plus.type = 'button';
      plus.className = 'dkm-qty-btn dkm-qty-plus';
      plus.setAttribute('aria-label', 'Verhoog aantal');
      plus.textContent = '+';

      input.parentNode.insertBefore(minus, input);
      input.parentNode.appendChild(plus);
    });
  }

  // === AJAX ADD TO CART ===
  function initAjaxAddToCart() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.ajax_add_to_cart, .add_to_cart_button.ajax_add_to_cart');
      if (!btn) return;
      if (btn.classList.contains('loading')) return;

      e.preventDefault();
      btn.classList.add('loading');
      var originalText = btn.textContent;
      btn.textContent = 'Toevoegen...';

      var productId = btn.getAttribute('data-product_id');
      var quantity = btn.getAttribute('data-quantity') || 1;

      var formData = new FormData();
      formData.append('product_id', productId);
      formData.append('quantity', quantity);

      var ajaxUrl = typeof wc_add_to_cart_params !== 'undefined'
        ? wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart')
        : '/?wc-ajax=add_to_cart';

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      })
        .then(function (response) { return response.json(); })
        .then(function (data) {
          if (data.error) {
            btn.textContent = 'Fout';
            setTimeout(function () { btn.textContent = originalText; }, 2000);
          } else {
            btn.textContent = 'Toegevoegd ✓';
            btn.classList.add('added');
            setTimeout(function () {
              btn.textContent = originalText;
              btn.classList.remove('added');
            }, 2000);

            if (data.fragments) {
              Object.keys(data.fragments).forEach(function (key) {
                var el = document.querySelector(key);
                if (el) el.outerHTML = data.fragments[key];
              });
            }

            var counts = document.querySelectorAll('.header-action-btn__count');
            counts.forEach(function (count) {
              var current = parseInt(count.textContent, 10) || 0;
              count.textContent = current + parseInt(quantity, 10);
              count.classList.remove('hidden');
            });

            document.body.dispatchEvent(new Event('added_to_cart'));
          }
        })
        .catch(function () {
          btn.textContent = 'Fout';
          setTimeout(function () { btn.textContent = originalText; }, 2000);
        })
        .finally(function () {
          btn.classList.remove('loading');
        });
    });
  }

  // === INIT ===
  function init() {
    initHeaderScroll();
    initMobileMenu();
    initSearchOverlay();
    initMiniCart();
    initScrollReveal();
    initProductImageHover();
    initReviewsCarousel();
    initSmoothScroll();
    initBackToTop();
    initQuantityButtons();
    wrapQuantityInputs();
    initAjaxAddToCart();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

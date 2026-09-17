// B2B partnerships page (/b2b-partnerships): page behaviour only.
// The enquiry form has no submission handling yet; that is being built separately.
(() => {
  'use strict';

  const page = document.querySelector('.b2b-page');
  if (!page) return;

  const form = page.querySelector('#b2b-quote-form');
  const enquire = page.querySelector('#b2b-enquire');
  const destinations = [...form.querySelectorAll('input[name="destinations"]')];
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Mobile navigation toggle.
  const menu = page.querySelector('.b2b-menu-toggle');
  const nav = page.querySelector('#b2b-main-nav');
  function setMenu(open) {
    menu.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    nav.classList.toggle('b2b-is-open', open);
  }
  menu.addEventListener('click', () => setMenu(menu.getAttribute('aria-expanded') !== 'true'));
  nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenu(false)));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && menu.getAttribute('aria-expanded') === 'true') {
      setMenu(false);
      menu.focus();
    }
  });
  document.addEventListener('click', event => {
    if (!event.target.closest('.b2b-header')) setMenu(false);
  });

  // The sticky mobile call-to-action hides while the enquiry form is on screen.
  const mobileCta = page.querySelector('.b2b-mobile-cta');
  if (mobileCta && 'IntersectionObserver' in window) {
    new IntersectionObserver(entries => {
      entries.forEach(entry => mobileCta.classList.toggle('b2b-is-hidden', entry.isIntersecting));
    }).observe(enquire);
  }

  // "Build a programme" buttons pre-select their destinations and jump to the form.
  page.querySelectorAll('[data-destination]').forEach(button => {
    button.addEventListener('click', () => {
      const selection = button.dataset.destination.split(',');
      destinations.forEach(input => { input.checked = selection.includes(input.value); });
      enquire.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth' });
      form.querySelector('input[name="name"]').focus({ preventScroll: true });
    });
  });

  // Submitting does nothing until the form's functionality is built. The button is
  // rendered disabled so the form cannot submit without JavaScript either.
  form.addEventListener('submit', event => event.preventDefault());
  form.querySelector('button[type="submit"]').disabled = false;
})();

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav-links');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
  }

  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (form.dataset.confirm === 'remove' && !window.confirm('Remove this item from your cart?')) {
        event.preventDefault();
      }
    });
  });
});

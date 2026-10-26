/* admin.js — utilities global */
document.addEventListener('DOMContentLoaded', () => {
  // Tandai menu aktif di sidebar
  const path = window.location.pathname;
  document.querySelectorAll('.sidebar a').forEach(a => {
    if (a.getAttribute('href') && path.includes(a.getAttribute('href'))) {
      a.classList.add('active');
    }
  });

  // Auto-dismiss alert
  document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 4000);
  });
});
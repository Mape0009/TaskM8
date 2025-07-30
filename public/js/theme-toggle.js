  const btn = document.getElementById('theme-toggle-btn');
  const sun = btn ? btn.querySelector('.sun') : null;
  const moon = btn ? btn.querySelector('.moon') : null;
  
  function setTheme(isDark) {
      document.body.classList.toggle('dark-mode', isDark);
      if (btn) {
          btn.classList.toggle('dark', isDark);
          if (sun && moon) {
              sun.style.display = isDark ? 'inline' : 'none';
              moon.style.display = isDark ? 'none' : 'inline';
          }
      }
  }
  
  function getPreferredTheme() {
      const stored = localStorage.getItem('theme');
      if (stored) return stored === 'dark';
      return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  
  const isDark = getPreferredTheme();
  setTheme(isDark);
  
  document.addEventListener('DOMContentLoaded', function () {
      if (btn) {
          btn.addEventListener('click', function () {
              const currentlyDark = document.body.classList.contains('dark-mode');
            setTheme(!currentlyDark);
            localStorage.setItem('theme', !currentlyDark ? 'dark' : 'light');
        });
    }

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                setTheme(e.matches);
            }
          });
      }
  });
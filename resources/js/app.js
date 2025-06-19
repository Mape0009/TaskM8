import './bootstrap';

// Theme toggle logic
function setTheme(isDark) {
    document.body.classList.toggle('dark-mode', isDark);
    const btn = document.getElementById('theme-toggle-btn');
    if (btn) {
        btn.classList.toggle('dark', isDark);
        btn.querySelector('.sun').style.display = isDark ? 'none' : 'inline';
        btn.querySelector('.moon').style.display = isDark ? 'inline' : 'none';
    }
}

function getPreferredTheme() {
    const stored = localStorage.getItem('theme');
    if (stored) return stored === 'dark';
    // Fallback to system preference
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

document.addEventListener('DOMContentLoaded', function () {
    const isDark = getPreferredTheme();
    setTheme(isDark);
    const btn = document.getElementById('theme-toggle-btn');
    if (btn) {
        btn.addEventListener('click', function () {
            const currentlyDark = document.body.classList.contains('dark-mode');
            setTheme(!currentlyDark);
            localStorage.setItem('theme', !currentlyDark ? 'dark' : 'light');
        });
    }
});

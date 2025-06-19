// Simple dark/light mode toggle for TaskM8
// Works with the button in the header (id="theme-toggle-btn")
// Remembers user choice in localStorage

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('theme-toggle-btn');
    const sun = btn ? btn.querySelector('.sun') : null;
    const moon = btn ? btn.querySelector('.moon') : null;

    function setTheme(isDark) {
        document.body.classList.toggle('dark-mode', isDark);
        if (btn) {
            btn.classList.toggle('dark', isDark);
            if (sun && moon) {
                sun.style.display = isDark ? 'none' : 'inline';
                moon.style.display = isDark ? 'inline' : 'none';
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

    if (btn) {
        btn.addEventListener('click', function () {
            const currentlyDark = document.body.classList.contains('dark-mode');
            setTheme(!currentlyDark);
            localStorage.setItem('theme', !currentlyDark ? 'dark' : 'light');
        });
    }

    // Optional: Listen for system theme changes if user hasn't chosen manually
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                setTheme(e.matches);
            }
        });
    }
}); 
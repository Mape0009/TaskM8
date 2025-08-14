// Theme toggle script - applies theme immediately and sets up event listeners when DOM is ready
(function() {
    'use strict';
    
    function setTheme(isDark) {
        document.documentElement.classList.toggle('dark-mode', isDark);
        document.body.classList.toggle('dark-mode', isDark);
        
        // Update all theme toggle buttons
        const buttons = document.querySelectorAll('#theme-toggle-btn, #mobile-theme-toggle-btn');
        buttons.forEach(btn => {
            if (btn) {
                btn.classList.toggle('dark', isDark);
                const sun = btn.querySelector('.sun');
                const moon = btn.querySelector('.moon');
                if (sun && moon) {
                    sun.style.display = isDark ? 'inline' : 'none';
                    moon.style.display = isDark ? 'none' : 'inline';
                }
            }
        });
    }
    
    function getPreferredTheme() {
        const stored = localStorage.getItem('theme');
        if (stored) return stored === 'dark';
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    
    // Apply theme immediately to prevent flash
    const isDark = getPreferredTheme();
    setTheme(isDark);
    
    // Add event listeners when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupThemeToggle);
    } else {
        setupThemeToggle();
    }
    
    function setupThemeToggle() {
        const buttons = document.querySelectorAll('#theme-toggle-btn, #mobile-theme-toggle-btn');
        buttons.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function() {
                    const currentlyDark = document.documentElement.classList.contains('dark-mode');
                    setTheme(!currentlyDark);
                    localStorage.setItem('theme', !currentlyDark ? 'dark' : 'light');
                });
            }
        });
        
        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('theme')) {
                    setTheme(e.matches);
                }
            });
        }
    }
})();
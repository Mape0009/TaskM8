(() => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    function staggerIn(elements, opts = {}) {
        const { baseDelay = 80, step = 60, from = 'up' } = opts;
        elements.forEach((el, i) => {
            el.style.opacity = '0';
            el.style.willChange = 'transform, opacity';
            let initial = 'translateY(16px)';
            if (from === 'down') initial = 'translateY(-16px)';
            if (from === 'left') initial = 'translateX(-16px)';
            if (from === 'right') initial = 'translateX(16px)';
            el.style.transform = initial;
            const delay = baseDelay + i * step;
            requestAnimationFrame(() => {
                setTimeout(() => {
                    el.style.transition = 'transform 560ms cubic-bezier(.2,.8,.2,1), opacity 560ms ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateX(0) translateY(0)';
                }, delay);
            });
        });
    }

    function onScrollReveal(selector, options = {}) {
        const targets = Array.from(document.querySelectorAll(selector));
        if (!targets.length || !('IntersectionObserver' in window)) return;
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    el.classList.add(options.addClass || 'animate-fade-up');
                    io.unobserve(el);
                }
            });
        }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
        targets.forEach(t => io.observe(t));
    }

    function pointerGlow(containerSelector, itemSelector) {
        const container = document.querySelector(containerSelector);
        if (!container) return;
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            container.querySelectorAll(itemSelector).forEach((el) => {
                el.style.setProperty('--mx', x + '%');
                el.style.setProperty('--my', y + '%');
            });
        });
        container.addEventListener('mouseleave', () => {
            container.querySelectorAll(itemSelector).forEach((el) => {
                el.style.removeProperty('--mx');
                el.style.removeProperty('--my');
            });
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        const title = document.querySelector('.guest-hero__title');
        const subtitle = document.querySelector('.guest-hero__subtitle');
        const cta = document.querySelector('.guest-hero__cta');
        if (title) staggerIn([title], { baseDelay: 60, step: 0, from: 'down' });
        if (subtitle) staggerIn([subtitle], { baseDelay: 180, step: 0, from: 'left' });
        if (cta) staggerIn([cta], { baseDelay: 280, step: 0, from: 'right' });

        const metrics = document.querySelectorAll('.guest-hero__metrics .metric');
        if (metrics.length) staggerIn(Array.from(metrics), { baseDelay: 220, step: 100, from: 'up' });

        onScrollReveal('.features .feature-card', { addClass: 'animate-fade-up' });
        pointerGlow('.features', '.feature-card');

        try {
            if (window.autoAnimate) {
                const heroContainer = document.querySelector('.guest-hero__content');
                const metricsContainer = document.querySelector('.guest-hero__metrics');
                const featuresContainer = document.querySelector('.features .features-grid');
                const ctaContainer = document.querySelector('.cta-banner');
                heroContainer && window.autoAnimate(heroContainer, { duration: 350, easing: 'ease-in-out' });
                metricsContainer && window.autoAnimate(metricsContainer, { duration: 350, easing: 'ease-out' });
                featuresContainer && window.autoAnimate(featuresContainer, { duration: 380, easing: 'ease-in-out' });
                ctaContainer && window.autoAnimate(ctaContainer, { duration: 360, easing: 'ease-in' });
            }
        } catch (e) {
        }
    });
})();



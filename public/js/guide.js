(function () {
	'use strict';

	const sections = document.querySelectorAll('.guide-section[data-guide-search]');
	const tocLinks = document.querySelectorAll('.guide-toc-links a');
	const backToTop = document.getElementById('guide-back-to-top');
	const header = document.querySelector('.main-header');
	const sectionById = new Map(Array.from(sections).map(function (section) {
		return [section.id, section];
	}));

	function getScrollOffset() {
		const headerHeight = header ? header.offsetHeight : 0;
		return Math.max(headerHeight + 16, 96);
	}

	function scrollToSection(section, behavior) {
		if (!section) return;

		const top = section.getBoundingClientRect().top + window.pageYOffset - getScrollOffset();
		window.scrollTo({ top: top, behavior: behavior || 'smooth' });
		section.setAttribute('tabindex', '-1');
		section.focus({ preventScroll: true });
		history.replaceState(null, '', '#' + section.id);
	}

	function setActiveTocLink(id) {
		tocLinks.forEach(function (link) {
			const href = link.getAttribute('href') || '';
			link.classList.toggle('is-active', href === '#' + id);
		});
	}

	function initScrollSpy() {
		if (!sections.length || !('IntersectionObserver' in window)) return;

		const observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						setActiveTocLink(entry.target.id);
					}
				});
			},
			{ rootMargin: '-20% 0px -60% 0px', threshold: 0 }
		);

		sections.forEach(function (section) {
			observer.observe(section);
		});
	}

	function initSmoothToc() {
		tocLinks.forEach(function (link) {
			link.addEventListener('click', function (event) {
				const href = link.getAttribute('href');
				if (!href || !href.startsWith('#')) return;

				const target = sectionById.get(href.slice(1));
				if (!target) return;

				event.preventDefault();
				scrollToSection(target, 'smooth');
			});
		});
	}

	function initHashScroll() {
		if (!window.location.hash) return;

		const target = sectionById.get(window.location.hash.slice(1));
		if (!target) return;

		window.requestAnimationFrame(function () {
			scrollToSection(target, 'auto');
		});
	}

	function initBackToTop() {
		if (!backToTop) return;

		function toggleButton() {
			const show = window.scrollY > 400;
			backToTop.classList.toggle('is-visible', show);
			backToTop.hidden = !show;
		}

		window.addEventListener('scroll', toggleButton, { passive: true });
		toggleButton();

		backToTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	initScrollSpy();
	initSmoothToc();
	initHashScroll();
	initBackToTop();
})();

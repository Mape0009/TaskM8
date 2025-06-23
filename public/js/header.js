// Modal logic
const modal = document.getElementById('new-event-modal');
const modalContent = document.getElementById('modal-content');
const openBtn = document.querySelector('.create-event-btn-header:not(.mobile)');
const openBtnMobile = document.querySelector('.create-event-btn-header.mobile');
const closeBtn = document.getElementById('close-modal-btn');
if (openBtn) {
    openBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'flex';
        // Set min for startInput to now
        const startInput = document.getElementById('event-start');
        if (startInput) {
            const now = new Date();
            const pad = n => n.toString().padStart(2, '0');
            const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
            startInput.min = local;
        }
        setTimeout(() => { startInput && startInput.focus(); }, 200);
    });
}
if (openBtnMobile) {
    openBtnMobile.addEventListener('click', function(e) {
        e.preventDefault();
        modal.style.display = 'flex';
        // Set min for startInput to now
        const startInput = document.getElementById('event-start');
        if (startInput) {
            const now = new Date();
            const pad = n => n.toString().padStart(2, '0');
            const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
            startInput.min = local;
        }
        setTimeout(() => { startInput && startInput.focus(); }, 200);
        // Also close the mobile dropdown
        const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
        if (mobileNavOverlay) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}
if (closeBtn) {
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        resetModal();
    });
}
window.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.style.display = 'none';
        resetModal();
    }
});
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'flex') {
        modal.style.display = 'none';
        resetModal();
    }
});
const form = document.getElementById('new-event-form');
const startInput = document.getElementById('event-start');
const endInput = document.getElementById('event-end');
if (startInput && endInput) {
    startInput.addEventListener('change', function() {
        endInput.min = startInput.value;
        if (endInput.value && endInput.value < startInput.value) {
            endInput.value = startInput.value;
        }
    });
}
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showSuccess();
    });
}
function showSuccess() {
    modalContent.innerHTML = `
        <div class="modal-success">
            <div class="checkmark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2.5 2.5L16 9"/></svg>
            </div>
            <h3>Event Lavet!</h3>
            <p>Dit event er nu lavet!</p>
        </div>
    `;
    setTimeout(() => {
        modal.style.display = 'none';
        resetModal();
    }, 1400);
}
function resetModal() {
    // Restore modal content to form
    modalContent.innerHTML = `
        <div class=\"modal-header\">\n            <span class=\"modal-icon\">\n                <svg width=\"28\" height=\"28\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" viewBox=\"0 0 24 24\"><rect x=\"3\" y=\"4\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"></rect><line x1=\"16\" y1=\"2\" x2=\"16\" y2=\"6\"></line><line x1=\"8\" y1=\"2\" x2=\"8\" y2=\"6\"></line><line x1=\"3\" y1=\"10\" x2=\"21\" y2=\"10\"></line></svg>\n            </span>\n            <h2>Create New Event</h2>\n            <button class=\"modal-close-btn\" id=\"close-modal-btn\" aria-label=\"Close\">\n                <svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"></line><line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"></line></svg>\n            </button>\n        </div>\n        <form id=\"new-event-form\" class=\"modal-form\" autocomplete=\"off\">\n            <div>\n                <label for=\"event-title\">Title</label>\n                <input type=\"text\" id=\"event-title\" name=\"title\" required placeholder=\"Event title\">\n            </div>\n            <div>\n                <label for=\"event-start\">Start Time</label>\n                <input type=\"datetime-local\" id=\"event-start\" name=\"start_time\" required>\n            </div>\n            <div>\n                <label for=\"event-end\">End Time</label>\n                <input type=\"datetime-local\" id=\"event-end\" name=\"end_time\" required>\n            </div>\n            <div class=\"repeat-container\">\n                <label class=\"repeat-checkbox-label\">\n                    <input type=\"checkbox\" id=\"event-repeat\" name=\"repeat\"> Repeat\n                </label>\n                <div id=\"repeat-options\" class=\"repeat-options\" style=\"display: none;\">\n                    <label for=\"repeat-interval\">Interval</label>\n                    <select id=\"repeat-interval\" name=\"repeat_interval\" class=\"repeat-select\">\n                        <option value=\"daily\">Daily</option>\n                        <option value=\"weekly\">Weekly</option>\n                        <option value=\"monthly\">Monthly</option>\n                        <option value=\"custom\">Custom</option>\n                    </select>\n                    <input type=\"text\" id=\"custom-interval\" name=\"custom_interval\" placeholder=\"Describe custom interval\" class=\"custom-interval-input\" style=\"display: none;\">\n                </div>\n            </div>\n            <div>\n                <label for=\"event-location\">Location</label>\n                <input type=\"text\" id=\"event-location\" name=\"location\" required placeholder=\"e.g. Main Office, Park...\">\n            </div>\n            <div>\n                <label for=\"event-description\">Description</label>\n                <textarea id=\"event-description\" name=\"description\" rows=\"3\" required placeholder=\"What is this event about?\"></textarea>\n            </div>\n            <button type=\"submit\" class=\"btn primary-btn\">Create Event</button>\n        </form>\n    `;
    // Set min for startInput to now after modal is reset
    const startInput = document.getElementById('event-start');
    if (startInput) {
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
        startInput.min = local;
    }
    // Re-bind close and submit events
    document.getElementById('close-modal-btn').addEventListener('click', function() {
        modal.style.display = 'none';
        resetModal();
    });
    document.getElementById('new-event-form').addEventListener('submit', function(e) {
        e.preventDefault();
        showSuccess();
    });
}
const repeatCheckbox = document.getElementById('event-repeat');
const repeatOptions = document.getElementById('repeat-options');
const repeatInterval = document.getElementById('repeat-interval');
const customInterval = document.getElementById('custom-interval');
if (repeatCheckbox && repeatOptions && repeatInterval && customInterval) {
    repeatCheckbox.addEventListener('change', function() {
        repeatOptions.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) customInterval.style.display = 'none';
    });
    repeatInterval.addEventListener('change', function() {
        customInterval.style.display = this.value === 'custom' ? 'block' : 'none';
    });
}
// Mobile dropdown menu logic
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mainNav = document.getElementById('main-nav');
if (mobileMenuBtn && mainNav) {
    mobileMenuBtn.addEventListener('click', function() {
        mainNav.classList.toggle('open');
        mobileMenuBtn.classList.toggle('open');
    });
    // Optional: close menu when clicking a link (for SPA feel)
    mainNav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mainNav.classList.remove('open');
            mobileMenuBtn.classList.remove('open');
        });
    });
}
// Mobile nav overlay logic (toggle on hamburger click, no close icon)
const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
const openMobileBtn = document.getElementById('mobile-menu-btn');
if (openMobileBtn && mobileNavOverlay) {
    openMobileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (mobileNavOverlay.classList.contains('open')) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        } else {
            mobileNavOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    });
    // Also close when clicking outside the dropdown
    mobileNavOverlay.addEventListener('click', function(e) {
        if (e.target === mobileNavOverlay) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
    // Close on nav link click
    mobileNavOverlay.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
}
// Sync dark mode toggle in mobile nav with main toggle
const themeToggleBtnMobile = document.getElementById('theme-toggle-btn-mobile');
const themeToggleBtn = document.getElementById('theme-toggle-btn');
if (themeToggleBtnMobile && themeToggleBtn) {
    themeToggleBtnMobile.addEventListener('click', function(e) {
        e.preventDefault();
        themeToggleBtn.click();
    });
}
// Micro-interaction: scale up nav links and button on tap/click
function addTapScale(selector) {
    document.querySelectorAll(selector).forEach(el => {
        el.addEventListener('touchstart', () => el.classList.add('tapped'));
        el.addEventListener('mousedown', () => el.classList.add('tapped'));
        el.addEventListener('touchend', () => el.classList.remove('tapped'));
        el.addEventListener('mouseup', () => el.classList.remove('tapped'));
        el.addEventListener('mouseleave', () => el.classList.remove('tapped'));
    });
}
addTapScale('.mobile-navigation.premium a');
addTapScale('.create-event-btn-header.premium'); 
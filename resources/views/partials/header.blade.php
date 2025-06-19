<header class="main-header">
    <div class="header-left">
        <div class="logo">
            <img src="{{ asset('TaskM8-Logo.png') }}" alt="TaskM8 Logo" class="logo-img logo-img-dark" />
            <img src="{{ asset('TaskM8-Logo-Dark.png') }}" alt="TaskM8 Logo Dark" class="logo-img logo-img-light" />
        </div>
        <nav class="navigation" id="main-nav">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Events</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Friends</a></li>
            </ul>
        </nav>
    </div>
    <div class="header-right">
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open menu">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <button class="theme-toggle-btn" id="theme-toggle-btn" aria-label="Toggle dark/light mode">
            <svg class="icon sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <svg class="icon moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
        </button>
        <div class="user-profile-header">
            <div class="user-avatar">U</div>
            <div class="user-info-header">
                <p class="user-greeting">Welcome, User!</p>
            </div>
        </div>
        <button class="create-event-btn-header"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> New Event</button>
    </div>
</header>
<script src="{{ asset('js/theme-toggle.js') }}"></script> <!-- Global dark/light mode toggle -->
<!-- New Event Modal -->
<div id="new-event-modal" class="modal">
    <div class="modal-content" id="modal-content">
        <div class="modal-header">
            <span class="modal-icon">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </span>
            <h2>Create New Event</h2>
            <button class="modal-close-btn" id="close-modal-btn" aria-label="Close">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="new-event-form" class="modal-form" autocomplete="off">
            <div>
                <label for="event-title">Title</label>
                <input type="text" id="event-title" name="title" required placeholder="Event title">
            </div>
            <div>
                <label for="event-start">Start Time</label>
                <input type="datetime-local" id="event-start" name="start_time" required>
            </div>
            <div>
                <label for="event-end">End Time</label>
                <input type="datetime-local" id="event-end" name="end_time" required>
            </div>
            <div class="repeat-container">
                <label class="repeat-checkbox-label">
                    <input type="checkbox" id="event-repeat" name="repeat"> Repeat
                </label>
                <div id="repeat-options" class="repeat-options" style="display: none;">
                    <label for="repeat-interval">Interval</label>
                    <select id="repeat-interval" name="repeat_interval" class="repeat-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="custom">Custom</option>
                    </select>
                    <input type="text" id="custom-interval" name="custom_interval" placeholder="Describe custom interval" class="custom-interval-input" style="display: none;">
                </div>
            </div>
            <div>
                <label for="event-location">Location</label>
                <input type="text" id="event-location" name="location" required placeholder="e.g. Main Office, Park...">
            </div>
            <div>
                <label for="event-description">Description</label>
                <textarea id="event-description" name="description" rows="3" required placeholder="What is this event about?"></textarea>
            </div>
            <button type="submit" class="btn primary-btn">Create Event</button>
        </form>
    </div>
</div>

<!-- Mobile Nav Overlay -->
<div id="mobile-nav-overlay" class="mobile-nav-overlay">
    <div class="mobile-nav-content minimal glassy integrated-dropdown">
        <nav class="mobile-navigation minimal premium integrated">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Events</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Friends</a></li>
            </ul>
        </nav>
        <div class="mobile-divider integrated"></div>
        <div class="mobile-user-profile minimal integrated">
            <div class="user-avatar integrated">U</div>
        </div>
        <button class="create-event-btn-header mobile minimal premium integrated">+ New Event</button>
    </div>
</div>

<script>
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
        mobileNavOverlay.classList.remove('open');
        document.body.style.overflow = '';
    });
}
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    resetModal();
});
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

form.addEventListener('submit', function(e) {
    e.preventDefault();
    showSuccess();
});
function showSuccess() {
    modalContent.innerHTML = `
        <div class="modal-success">
            <div class="checkmark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2.5 2.5L16 9"/></svg>
            </div>
            <h3>Event Created!</h3>
            <p>Your event has been added successfully.</p>
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
</script>

<style>
/* Premium Repeat Controls Styling */
.repeat-container {
    margin-bottom: 0.5em;
    padding: 1em 0 0 0;
    border-radius: 10px;
    background: var(--color-background-tertiary, #f8fafc);
    display: flex;
    flex-direction: column;
    gap: 0.7em;
}
body.dark-mode .repeat-container {
    background: #23232A;
}
.repeat-checkbox-label {
    font-weight: 600;
    color: var(--color-accent-primary, #2563eb);
    display: flex;
    align-items: center;
    gap: 0.5em;
    font-size: 1.08em;
    cursor: pointer;
}
.repeat-checkbox-label input[type="checkbox"] {
    accent-color: var(--color-accent-primary, #2563eb);
    width: 1.1em;
    height: 1.1em;
    border-radius: 4px;
    margin-right: 0.4em;
}
.repeat-options {
    margin-top: 0.2em;
    display: flex;
    flex-direction: column;
    gap: 0.5em;
}
.repeat-select {
    padding: 0.6em 1.2em;
    border-radius: 8px;
    border: 1.5px solid var(--color-border, #d1d5db);
    font-size: 1em;
    background: #fff;
    color: #23272f;
    font-weight: 500;
    transition: border 0.2s, box-shadow 0.2s;
    outline: none;
    margin-top: 0.2em;
}
.repeat-select:focus {
    border: 1.5px solid var(--color-accent-primary, #2563eb);
    box-shadow: 0 0 0 2px #2563eb33;
}
body.dark-mode .repeat-select {
    background: #2A2A30;
    color: #F0F0F0;
    border: 1.5px solid #333338;
}
body.dark-mode .repeat-select:focus {
    border: 1.5px solid #00C2FF;
    box-shadow: 0 0 0 2px #00C2FF33;
}
.custom-interval-input {
    margin-top: 0.3em;
    padding: 0.6em 1.2em;
    border-radius: 8px;
    border: 1.5px solid var(--color-border, #d1d5db);
    font-size: 1em;
    background: #fff;
    color: #23272f;
    font-weight: 500;
    transition: border 0.2s, box-shadow 0.2s;
    outline: none;
}
.custom-interval-input:focus {
    border: 1.5px solid var(--color-accent-primary, #2563eb);
    box-shadow: 0 0 0 2px #2563eb33;
}
body.dark-mode .custom-interval-input {
    background: #2A2A30;
    color: #F0F0F0;
    border: 1.5px solid #333338;
}
body.dark-mode .custom-interval-input:focus {
    border: 1.5px solid #00C2FF;
    box-shadow: 0 0 0 2px #00C2FF33;
}
/* Mobile Dropdown Header */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    outline: none;
    cursor: pointer;
    margin-left: 0.5em;
    padding: 0.2em 0.5em;
    border-radius: 8px;
    transition: background 0.2s;
}
.mobile-menu-btn:active, .mobile-menu-btn.open {
    background: var(--color-background-tertiary, #e0e7ef);
}
.mobile-menu-btn svg {
    display: block;
    stroke: var(--color-accent-primary, #2563eb);
}
@media (max-width: 900px) {
    .main-header {
        flex-wrap: wrap;
        position: relative;
    }
    .header-left {
        display: flex;
        align-items: center;
        width: auto;
    }
    .header-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100%;
    }
    .mobile-menu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        order: 10;
    }
}
@keyframes dropdownIn {
    0% { opacity: 0; transform: translateY(-16px); }
    100% { opacity: 1; transform: translateY(0); }
}
@media (max-width: 992px) {
    .main-header .navigation,
    .main-header .user-profile-header,
    .main-header .create-event-btn-header:not(.mobile) {
        display: none !important;
    }
    .mobile-menu-btn {
        display: inline-flex !important;
    }
    .mobile-nav-overlay {
        display: none;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .mobile-nav-overlay.open {
        display: flex;
        opacity: 1;
        pointer-events: auto;
    }
    .mobile-nav-content.minimal.glassy.integrated-dropdown {
        background: var(--color-background-secondary, #23232A);
        color: #F0F0F0;
        border-radius: 0 0 22px 22px;
        margin: 0 auto;
        width: 96vw;
        max-width: 420px;
        min-height: 0;
        box-shadow: 0 12px 32px 0 rgba(30,34,44,0.18);
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.2em 1.2em 2em 1.2em;
        position: relative;
        animation: slideDownDropdown 0.32s cubic-bezier(.77,0,.18,1);
        backdrop-filter: blur(12px) saturate(1.1);
        transition: box-shadow 0.18s, background 0.18s;
        z-index: 1001;
        top: 0;
    }
    body:not(.dark-mode) .mobile-nav-content.minimal.glassy.integrated-dropdown {
        background: #fff;
        color: #23272f;
    }
    .close-mobile-nav.integrated {
        position: absolute;
        top: 0.7em;
        right: 0.7em;
        background: rgba(99,102,241,0.10);
        border: none;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        border-radius: 50%;
        transition: background 0.18s, box-shadow 0.18s;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(30,34,44,0.10);
    }
    .close-mobile-nav.integrated:hover {
        background: #6366f1;
    }
    .close-mobile-nav.integrated svg {
        stroke: #23272f;
        color: #23272f;
    }
    body.dark-mode .close-mobile-nav.integrated svg {
        stroke: #F0F0F0;
        color: #F0F0F0;
    }
    .mobile-nav-header-row.integrated {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.3em;
        margin-top: 0.2em;
    }
    .mobile-nav-logo.integrated {
        font-size: 1.5em;
        font-weight: 900;
        color: #6366f1;
        letter-spacing: -1.2px;
        text-align: center;
        line-height: 1.1;
        margin: 0 auto;
        padding: 0.1em 0 0.1em 0;
        text-shadow: 0 2px 8px rgba(99,102,241,0.10);
    }
    .mobile-navigation.minimal.premium.integrated ul {
        list-style: none;
        padding: 0;
        margin: 0 0 1.2em 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1em;
    }
    .mobile-navigation.minimal.premium.integrated a {
        display: block;
        width: 100%;
        padding: 1em 0;
        font-size: 1.15em;
        font-weight: 800;
        color: #F0F0F0;
        background: none;
        border-radius: 10px;
        text-align: center;
        text-decoration: none;
        transition: background 0.18s, color 0.18s, transform 0.12s cubic-bezier(.77,0,.18,1);
        will-change: transform;
        box-shadow: none;
        letter-spacing: 0.01em;
    }
    .mobile-navigation.minimal.premium.integrated a.active, .mobile-navigation.minimal.premium.integrated a:hover {
        background: rgba(99,102,241,0.13);
        color: #00C2FF;
        box-shadow: 0 2px 8px rgba(99,102,241,0.10);
    }
    body:not(.dark-mode) .mobile-navigation.minimal.premium.integrated a {
        color: #23272f;
    }
    body:not(.dark-mode) .mobile-navigation.minimal.premium.integrated a.active, body:not(.dark-mode) .mobile-navigation.minimal.premium.integrated a:hover {
        background: #f1f5f9;
        color: #6366f1;
    }
    .mobile-divider.integrated {
        width: 100%;
        height: 1.5px;
        background: linear-gradient(90deg, rgba(99,102,241,0.10) 0%, rgba(0,0,0,0.03) 100%);
        margin: 1em 0 1em 0;
        border-radius: 1px;
    }
    .mobile-user-profile.minimal.integrated {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5em;
        margin-bottom: 1em;
    }
    .mobile-user-profile.minimal.integrated .user-avatar.integrated {
        width: 2.1em;
        height: 2.1em;
        font-size: 1.1em;
        background: #6366f1;
        color: #fff;
        box-shadow: 0 1px 4px rgba(99,102,241,0.10);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        border-radius: 50%;
    }
    .create-event-btn-header.mobile.minimal.premium.integrated {
        font-size: 1.05em;
        padding: 0.85em 2.2em;
        border-radius: 10px;
        width: max-content;
        min-width: 160px;
        min-height: 2.7em;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }
}
@keyframes slideDownDropdown {
    0% { transform: translateY(-32px) scaleY(0.98); opacity: 0; }
    100% { transform: translateY(0) scaleY(1); opacity: 1; }
}
@keyframes fadeInOverlay {
    0% { opacity: 0; }
    100% { opacity: 1; }
}
.mobile-nav-overlay {
    display: none;
}
@media (max-width: 992px) {
    .mobile-nav-overlay.open {
        display: flex;
        opacity: 1;
        pointer-events: auto;
    }
    .create-event-btn-header.mobile.minimal.premium.integrated {
        font-size: 1.05em;
        padding: 0.85em 2.2em;
        border-radius: 10px;
        width: max-content;
        min-width: 160px;
        min-height: 2.7em;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }
}
@media (min-width: 993px) {
    .mobile-nav-overlay {
        display: none !important;
    }
}
</style> 

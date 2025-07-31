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
// Fjern eller kommenter klik-udenfor og Escape logik
// window.addEventListener('click', function(e) {
//     if (e.target === modal) {
//         modal.style.display = 'none';
//         resetModal();
//     }
// });
// window.addEventListener('keydown', function(e) {
//     if (e.key === 'Escape' && modal.style.display === 'flex') {
//         modal.style.display = 'none';
//         resetModal();
//     }
// });

// Opdater closeBtn event: kun closeBtn kan lukke modalen
if (closeBtn) {
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        resetModal();
    });
}

// Form reset og reload efter submit (uden AJAX)
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
    form.addEventListener('submit', function() {
        // Efter submit vil siden automatisk reloade pga. form action (ingen AJAX)
        // Modal reset håndteres ved reload, men for ekstra sikkerhed kan vi resette felter her
        setTimeout(() => {
            form.reset();
        }, 100);
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
// Show success modal if event was created (from backend)
document.addEventListener('DOMContentLoaded', function() {
    const successMsg = document.getElementById('event-success-message');
    if (successMsg) {
        modal.style.display = 'flex';
        modalContent.innerHTML = `
            <div class="modal-success">
                <div class="checkmark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2.5 2.5L16 9"/></svg>
                </div>
                <h3>${successMsg.textContent}</h3>
            </div>
        `;
        setTimeout(() => {
            modal.style.display = 'none';
            // Fjern success-beskeden fra DOM
            successMsg.remove();
            // Genindlæs siden for at fjerne success-beskeden fra session
            window.location.reload();
        }, 1800);
    }
});
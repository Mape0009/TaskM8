<header class="main-header">
    <div class="header-left">
        <div class="logo">TaskM8</div>
        <nav class="navigation">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Events</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Friends</a></li>
            </ul>
        </nav>
    </div>
    <div class="header-right">
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
                <label for="event-start">Start Time</label>
                <input type="datetime-local" id="event-start" name="start_time" required>
            </div>
            <div>
                <label for="event-end">End Time</label>
                <input type="datetime-local" id="event-end" name="end_time" required>
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

<script>
const modal = document.getElementById('new-event-modal');
const modalContent = document.getElementById('modal-content');
const openBtn = document.querySelector('.create-event-btn-header');
const closeBtn = document.getElementById('close-modal-btn');
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
        <div class=\"modal-header\">\n            <span class=\"modal-icon\">\n                <svg width=\"28\" height=\"28\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" viewBox=\"0 0 24 24\"><rect x=\"3\" y=\"4\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"></rect><line x1=\"16\" y1=\"2\" x2=\"16\" y2=\"6\"></line><line x1=\"8\" y1=\"2\" x2=\"8\" y2=\"6\"></line><line x1=\"3\" y1=\"10\" x2=\"21\" y2=\"10\"></line></svg>\n            </span>\n            <h2>Create New Event</h2>\n            <button class=\"modal-close-btn\" id=\"close-modal-btn\" aria-label=\"Close\">\n                <svg width=\"22\" height=\"22\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"></line><line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"></line></svg>\n            </button>\n        </div>\n        <form id=\"new-event-form\" class=\"modal-form\" autocomplete=\"off\">\n            <div>\n                <label for=\"event-start\">Start Time</label>\n                <input type=\"datetime-local\" id=\"event-start\" name=\"start_time\" required>\n            </div>\n            <div>\n                <label for=\"event-end\">End Time</label>\n                <input type=\"datetime-local\" id=\"event-end\" name=\"end_time\" required>\n            </div>\n            <div>\n                <label for=\"event-location\">Location</label>\n                <input type=\"text\" id=\"event-location\" name=\"location\" required placeholder=\"e.g. Main Office, Park...\">\n            </div>\n            <div>\n                <label for=\"event-description\">Description</label>\n                <textarea id=\"event-description\" name=\"description\" rows=\"3\" required placeholder=\"What is this event about?\"></textarea>\n            </div>\n            <button type=\"submit\" class=\"btn primary-btn\">Create Event</button>\n        </form>\n    `;
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
</script> 

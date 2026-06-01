// Participants Modal JavaScript
let currentEventId = null;
let currentEventName = '';
let allParticipants = [];
let filteredParticipants = [];
let currentCategory = 'all';
let searchQuery = '';

function pmT(key, replacements = {}) {
    const i18n = window.participantsModalI18n || {};
    let text = i18n[key] || '';
    Object.entries(replacements).forEach(([name, value]) => {
        text = text.replace(new RegExp(':' + name, 'g'), value);
    });
    return text;
}

// Modal functions
function openParticipantsModal(eventId, eventName) {
    currentEventId = eventId;
    currentEventName = eventName;

    document.getElementById('participants-modal-subtitle').textContent =
        pmT('participantsSubtitleFor', { event: eventName });

    document.getElementById('participants-modal').style.display = 'flex';

    loadParticipants();
}

function closeParticipantsModal() {
    document.getElementById('participants-modal').style.display = 'none';

    currentEventId = null;
    currentEventName = '';
    allParticipants = [];
    filteredParticipants = [];
    currentCategory = 'all';
    searchQuery = '';

    document.getElementById('participants-search').value = '';
    document.querySelectorAll('.participants-category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('[data-category="all"]').classList.add('active');
}

function loadingSpinnerHtml() {
    return `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="6"></line>
            <line x1="12" y1="18" x2="12" y2="22"></line>
            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
            <line x1="2" y1="12" x2="6" y2="12"></line>
            <line x1="18" y1="12" x2="22" y2="12"></line>
            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
        </svg>
    `;
}

// Load participants from API
async function loadParticipants() {
    const participantsList = document.getElementById('participants-list');

    participantsList.innerHTML = `
        <div class="participants-loading">
            ${loadingSpinnerHtml()}
            ${pmT('loading')}
        </div>
    `;

    try {
        const response = await fetch(`/events/${currentEventId}/participants-list`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load participants');
        }

        allParticipants = await response.json();
        filteredParticipants = [...allParticipants];

        updateCategoryCounts();
        renderParticipants();

    } catch (error) {
        console.error('Error loading participants:', error);
        participantsList.innerHTML = `
            <div class="participants-empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <h3>${pmT('loadErrorTitle')}</h3>
                <p>${pmT('loadErrorText')}</p>
            </div>
        `;
    }
}

// Update category counts
function updateCategoryCounts() {
    const counts = {
        all: allParticipants.length,
        accepted: allParticipants.filter(p => p.status === 'accepted').length,
        declined: allParticipants.filter(p => p.status === 'declined').length,
        pending: allParticipants.filter(p => p.status === 'pending').length
    };

    Object.keys(counts).forEach(category => {
        const countElement = document.getElementById(`count-${category}`);
        if (countElement) {
            countElement.textContent = counts[category];
        }
    });
}

// Filter participants based on category and search
function filterParticipants() {
    filteredParticipants = allParticipants.filter(participant => {
        if (currentCategory !== 'all' && participant.status !== currentCategory) {
            return false;
        }

        if (searchQuery) {
            const query = searchQuery.toLowerCase();
            const name = (participant.name || '').toLowerCase();
            return name.includes(query);
        }

        return true;
    });

    renderParticipants();
}

function statusLabels() {
    return {
        accepted: pmT('statusAccepted'),
        declined: pmT('statusDeclined'),
        pending: pmT('statusPending'),
    };
}

// Render participants list
function renderParticipants() {
    const participantsList = document.getElementById('participants-list');

    if (filteredParticipants.length === 0) {
        let emptyMessage = '';
        if (searchQuery) {
            emptyMessage = `
                <div class="participants-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <h3>${pmT('emptySearchTitle')}</h3>
                    <p>${pmT('emptySearchText')}</p>
                </div>
            `;
        } else if (currentCategory !== 'all') {
            const labels = statusLabels();
            const categoryLabel = (labels[currentCategory] || currentCategory).toLowerCase();
            emptyMessage = `
                <div class="participants-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                    </svg>
                    <h3>${pmT('emptyCategoryTitle', { category: categoryLabel })}</h3>
                    <p>${pmT('emptyCategoryText')}</p>
                </div>
            `;
        } else {
            emptyMessage = `
                <div class="participants-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                    </svg>
                    <h3>${pmT('emptyTitle')}</h3>
                    <p>${pmT('emptyText')}</p>
                </div>
            `;
        }

        participantsList.innerHTML = emptyMessage;
        return;
    }

    participantsList.innerHTML = filteredParticipants.map(participant => {
        const initials = (participant.name || '?').charAt(0).toUpperCase();
        const statusText = getStatusText(participant.status, participant.eventRole);
        const statusClass = participant.status;

        return `
            <div class="participant-item" data-status="${participant.status}">
                <div class="participant-avatar">${initials}</div>
                <div class="participant-info">
                    <div class="participant-name">${participant.name || pmT('unknown')}</div>
                    <div class="participant-status">
                        <span class="participant-status-dot ${statusClass}"></span>
                        <span class="participant-status-text">${statusText}</span>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function getStatusText(status, eventRole = null) {
    const statusMap = statusLabels();
    const baseStatus = statusMap[status] || status;

    if (status === 'accepted' && eventRole === 'volunteer') {
        return `${baseStatus} - ${pmT('statusVolunteer')}`;
    }

    return baseStatus;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('participants-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.trim();
            filterParticipants();
        });
    }

    document.querySelectorAll('.participants-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.participants-category-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentCategory = this.getAttribute('data-category');

            filterParticipants();
        });
    });

    const modal = document.getElementById('participants-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeParticipantsModal();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeParticipantsModal();
        }
    });
});

// Participants Modal JavaScript
let currentEventId = null;
let currentEventName = '';
let allParticipants = [];
let filteredParticipants = [];
let currentCategory = 'all';
let searchQuery = '';

// Modal functions
function openParticipantsModal(eventId, eventName) {
    currentEventId = eventId;
    currentEventName = eventName;
    
    // Update modal title
    document.getElementById('participants-modal-subtitle').textContent = `Se alle deltagere for "${eventName}"`;
    
    // Show modal
    document.getElementById('participants-modal').style.display = 'flex';
    
    // Load participants
    loadParticipants();
}

function closeParticipantsModal() {
    document.getElementById('participants-modal').style.display = 'none';
    
    // Reset state
    currentEventId = null;
    currentEventName = '';
    allParticipants = [];
    filteredParticipants = [];
    currentCategory = 'all';
    searchQuery = '';
    
    // Reset UI
    document.getElementById('participants-search').value = '';
    document.querySelectorAll('.participants-category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('[data-category="all"]').classList.add('active');
}

// Load participants from API
async function loadParticipants() {
    const participantsList = document.getElementById('participants-list');
    
    // Show loading state
    participantsList.innerHTML = `
        <div class="participants-loading">
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
            Henter deltagere...
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
                <h3>Fejl ved indlæsning</h3>
                <p>Kunne ikke hente deltagere. Prøv igen senere.</p>
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
        // Category filter
        if (currentCategory !== 'all' && participant.status !== currentCategory) {
            return false;
        }
        
        // Search filter
        if (searchQuery) {
            const query = searchQuery.toLowerCase();
            const name = (participant.name || '').toLowerCase();
            return name.includes(query);
        }
        
        return true;
    });
    
    renderParticipants();
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
                    <h3>Ingen deltagere fundet</h3>
                    <p>Ingen deltagere matcher din søgning.</p>
                </div>
            `;
        } else if (currentCategory !== 'all') {
            const categoryNames = {
                accepted: 'Deltager',
                declined: 'Deltager ikke',
                pending: 'Afventer svar'
            };
            emptyMessage = `
                <div class="participants-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                    </svg>
                    <h3>Ingen ${categoryNames[currentCategory].toLowerCase()}</h3>
                    <p>Der er ingen deltagere i denne kategori.</p>
                </div>
            `;
        } else {
            emptyMessage = `
                <div class="participants-empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                    </svg>
                    <h3>Ingen deltagere</h3>
                    <p>Der er ingen deltagere på denne begivenhed endnu.</p>
                </div>
            `;
        }
        
        participantsList.innerHTML = emptyMessage;
        return;
    }
    
    participantsList.innerHTML = filteredParticipants.map(participant => {
        const initials = (participant.name || '?').charAt(0).toUpperCase();
        const statusText = getStatusText(participant.status);
        const statusClass = participant.status;
        
        return `
            <div class="participant-item" data-status="${participant.status}">
                <div class="participant-avatar">${initials}</div>
                <div class="participant-info">
                    <div class="participant-name">${participant.name || 'Ukendt'}</div>
                    <div class="participant-status">
                        <span class="participant-status-dot ${statusClass}"></span>
                        <span class="participant-status-text">${statusText}</span>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Get status text in Danish
function getStatusText(status) {
    const statusMap = {
        'accepted': 'Deltager',
        'declined': 'Deltager ikke',
        'pending': 'Afventer svar'
    };
    return statusMap[status] || status;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search input
    const searchInput = document.getElementById('participants-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.trim();
            filterParticipants();
        });
    }
    
    // Category buttons
    document.querySelectorAll('.participants-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.participants-category-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update current category
            currentCategory = this.getAttribute('data-category');
            
            // Filter participants
            filterParticipants();
        });
    });
    
    // Modal close on backdrop click
    const modal = document.getElementById('participants-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeParticipantsModal();
            }
        });
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeParticipantsModal();
        }
    });
});

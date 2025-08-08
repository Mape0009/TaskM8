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
        const startInput = document.getElementById('event-start');
        if (startInput) {
            const now = new Date();
            const pad = n => n.toString().padStart(2, '0');
            const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
            startInput.min = local;
        }
        setTimeout(() => { startInput && startInput.focus(); }, 200);
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

// Cancel button functionality
const cancelBtn = document.getElementById('cancel-btn');
if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        resetModal();
    });
}

// Form reset og reload efter submit 
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
        
        // Show loading state
        const submitBtn = form.querySelector('.primary-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Opretter...';
        submitBtn.disabled = true;
        
        // Simulate form submission (replace with actual form submission)
        setTimeout(() => {
            // Show success message
            showSuccess();
            
            // Reset form
            form.reset();
            
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1000);
    });
}

function showSuccess() {
    modalContent.innerHTML = `
        <div class="modal-success">
            <div class="checkmark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12l2.5 2.5L16 9"/>
                </svg>
            </div>
            <h3>Begivenhed oprettet!</h3>
            <p>Din begivenhed er nu blevet oprettet succesfuldt.</p>
        </div>
    `;
    
    setTimeout(() => {
        modal.style.display = 'none';
        resetModal();
    }, 2000);
}
function resetModal() {
    // Reset form
    if (form) {
        form.reset();
    }
    
    // Reset repeat options
    if (repeatCheckbox) {
        repeatCheckbox.checked = false;
    }
    if (repeatOptions) {
        repeatOptions.style.display = 'none';
        repeatOptions.classList.remove('show');
    }
    if (customIntervalField) {
        customIntervalField.style.display = 'none';
        customIntervalField.classList.remove('show');
    }
    if (customInterval) {
        customInterval.value = '';
    }
    
    // Reset datetime inputs
    const startInput = document.getElementById('event-start');
    const endInput = document.getElementById('event-end');
    if (startInput && endInput) {
        const now = new Date();
        const pad = n => n.toString().padStart(2, '0');
        const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
        startInput.min = local;
        startInput.value = '';
        endInput.value = '';
    }
    
    // Reset modal content to original form
    if (modalContent) {
        modalContent.innerHTML = `
            <div class="modal-header">
                <div class="modal-header-content">
                    <div class="modal-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="modal-title">
                        <h2>Opret ny begivenhed</h2>
                        <p class="modal-subtitle">Udfyld informationerne nedenfor</p>
                    </div>
                </div>
                <button class="modal-close-btn" id="close-modal-btn" aria-label="Luk">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <form id="new-event-form" class="modal-form" autocomplete="off" method="POST" action="{{ route('events.create') }}">
                @csrf
                
                <div class="form-section">
                    <h3 class="section-title">Grundlæggende information</h3>
                    <div class="form-row">
                        <label for="event-title">Titel</label>
                        <input type="text" id="event-title" name="eventName" required placeholder="Indtast begivenhedens titel">
                    </div>
                    <div class="form-row">
                        <label for="event-location">Lokation</label>
                        <input type="text" id="event-location" name="location" required placeholder="Indtast lokation">
                    </div>
                    <div class="form-row">
                        <label for="event-description">Beskrivelse</label>
                        <textarea id="event-description" name="description" rows="3" required placeholder="Beskriv begivenheden"></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="section-title">Tidspunkt</h3>
                    <div class="form-row">
                        <label for="event-start">Start tidspunkt</label>
                        <input type="datetime-local" id="event-start" name="startDate" required>
                    </div>
                    <div class="form-row">
                        <label for="event-end">Slut tidspunkt</label>
                        <input type="datetime-local" id="event-end" name="endDate" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="section-title">Gentagelse</h3>
                    <div class="repeat-section">
                        <div class="repeat-toggle">
                            <input type="checkbox" id="event-repeat" name="repeat">
                            <label for="event-repeat">Aktiver gentagelse</label>
                        </div>
                        <div id="repeat-options" class="repeat-options" style="display: none;">
                            <div class="repeat-field">
                                <label for="repeat-interval">Gentagelse interval</label>
                                <select id="repeat-interval" name="repeat_interval" class="repeat-select">
                                    <option value="daily">Dagligt</option>
                                    <option value="weekly">Ugentligt</option>
                                    <option value="monthly">Månedligt</option>
                                    <option value="yearly">Årligt</option>
                                    <option value="custom">Tilpasset</option>
                                </select>
                            </div>
                            <div class="repeat-field" id="custom-interval-field" style="display: none;">
                                <label for="custom-interval">Tilpasset interval</label>
                                <input type="text" id="custom-interval" name="custom_interval" placeholder="F.eks. hver 2. uge, hver 3. dag" class="custom-interval-input">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn secondary-btn" id="cancel-btn">Annuller</button>
                    <button type="submit" class="btn primary-btn">Opret begivenhed</button>
                </div>
            </form>
        `;
    }
    
    // Re-initialize event listeners
    initializeModalListeners();
}
const repeatCheckbox = document.getElementById('event-repeat');
const repeatOptions = document.getElementById('repeat-options');
const repeatInterval = document.getElementById('repeat-interval');
const customIntervalField = document.getElementById('custom-interval-field');
const customInterval = document.getElementById('custom-interval');

if (repeatCheckbox && repeatOptions && repeatInterval && customIntervalField && customInterval) {
    repeatCheckbox.addEventListener('change', function() {
        if (this.checked) {
            repeatOptions.style.display = 'block';
            repeatOptions.classList.add('show');
        } else {
            repeatOptions.style.display = 'none';
            repeatOptions.classList.remove('show');
            customIntervalField.style.display = 'none';
            customInterval.value = '';
        }
    });
    
    repeatInterval.addEventListener('change', function() {
        if (this.value === 'custom') {
            customIntervalField.style.display = 'block';
            customIntervalField.classList.add('show');
            customInterval.focus();
        } else {
            customIntervalField.style.display = 'none';
            customIntervalField.classList.remove('show');
            customInterval.value = '';
        }
    });
}
// Mobile nav
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

    mobileNavOverlay.addEventListener('click', function(e) {
        if (e.target === mobileNavOverlay) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
   
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

// User Profile Dropdown functionality
const userProfileTrigger = document.getElementById('user-profile-trigger');
const userDropdownMenu = document.getElementById('user-dropdown-menu');
const userProfileDropdown = document.querySelector('.user-profile-dropdown');

if (userProfileTrigger && userDropdownMenu) {
    userProfileTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        userProfileDropdown.classList.toggle('open');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!userProfileDropdown.contains(e.target)) {
            userProfileDropdown.classList.remove('open');
        }
    });

    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            userProfileDropdown.classList.remove('open');
        }
    });

    // Settings button functionality (placeholder for future implementation)
    const settingsBtn = document.getElementById('settings-btn');
    if (settingsBtn) {
        settingsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // TODO: Add settings functionality
            console.log('Settings clicked - functionality to be implemented');
            userProfileDropdown.classList.remove('open');
        });
    }
}

// Mobile settings button functionality
const mobileSettingsBtn = document.getElementById('mobile-settings-btn');
if (mobileSettingsBtn) {
    mobileSettingsBtn.addEventListener('click', function(e) {
        e.preventDefault();
        // TODO: Add settings functionality
        console.log('Mobile settings clicked - functionality to be implemented');
        // Close mobile nav overlay
        const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
        if (mobileNavOverlay) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}

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
            successMsg.remove();
            window.location.reload();
        }, 1800);
    }
});

function initializeModalListeners() {
    // Re-get elements after reset
    const newForm = document.getElementById('new-event-form');
    const newStartInput = document.getElementById('event-start');
    const newEndInput = document.getElementById('event-end');
    const newRepeatCheckbox = document.getElementById('event-repeat');
    const newRepeatOptions = document.getElementById('repeat-options');
    const newRepeatInterval = document.getElementById('repeat-interval');
    const newCustomIntervalField = document.getElementById('custom-interval-field');
    const newCustomInterval = document.getElementById('custom-interval');
    const newCancelBtn = document.getElementById('cancel-btn');
    const newCloseBtn = document.getElementById('close-modal-btn');
    
    // Set up datetime validation
    if (newStartInput && newEndInput) {
        newStartInput.addEventListener('change', function() {
            newEndInput.min = newStartInput.value;
            if (newEndInput.value && newEndInput.value < newStartInput.value) {
                newEndInput.value = newStartInput.value;
            }
        });
    }
    
    // Set up form submission
    if (newForm) {
        newForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = newForm.querySelector('.primary-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Opretter...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual form submission)
            setTimeout(() => {
                // Show success message
                showSuccess();
                
                // Reset form
                newForm.reset();
                
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 1000);
        });
    }
    
    // Set up repeat functionality
    if (newRepeatCheckbox && newRepeatOptions && newRepeatInterval && newCustomIntervalField && newCustomInterval) {
        newRepeatCheckbox.addEventListener('change', function() {
            if (this.checked) {
                newRepeatOptions.style.display = 'block';
                newRepeatOptions.classList.add('show');
            } else {
                newRepeatOptions.style.display = 'none';
                newRepeatOptions.classList.remove('show');
                newCustomIntervalField.style.display = 'none';
                newCustomInterval.value = '';
            }
        });
        
        newRepeatInterval.addEventListener('change', function() {
            if (this.value === 'custom') {
                newCustomIntervalField.style.display = 'block';
                newCustomIntervalField.classList.add('show');
                newCustomInterval.focus();
            } else {
                newCustomIntervalField.style.display = 'none';
                newCustomIntervalField.classList.remove('show');
                newCustomInterval.value = '';
            }
        });
    }
    
    // Set up cancel button
    if (newCancelBtn) {
        newCancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            resetModal();
        });
    }
    
    // Set up close button
    if (newCloseBtn) {
        newCloseBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            resetModal();
        });
    }
}
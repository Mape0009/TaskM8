function toggleTm8Loader(show) {
    const loader = document.getElementById('tm8-page-loader');
    if (!loader) {
        return;
    }

    if (show) {
        loader.classList.add('is-visible');
        loader.setAttribute('aria-hidden', 'false');
        document.body.classList.add('tm8-loader-lock');
    } else {
        loader.classList.remove('is-visible');
        loader.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('tm8-loader-lock');
    }
}

function wireTm8Loader(formId, delayMs, options) {
    const form = document.getElementById(formId);
    if (!form || form.dataset.tm8LoaderWired === 'true') {
        return;
    }

    const loaderOptions = options || {};
    form.dataset.tm8LoaderWired = 'true';
    form.addEventListener('submit', function(event) {
        if (form.dataset.tm8LoaderSubmitting === 'true') {
            return;
        }

        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            if (typeof form.reportValidity === 'function') {
                form.reportValidity();
            }
            return;
        }

        event.preventDefault();
        form.dataset.tm8LoaderSubmitting = 'true';

        const loaderTitle = document.querySelector('#tm8-page-loader .tm8-page-loader__title');
        const loaderText = document.querySelector('#tm8-page-loader .tm8-page-loader__text');
        if (loaderTitle && loaderOptions.title) {
            loaderTitle.textContent = loaderOptions.title;
        }
        if (loaderText && loaderOptions.text) {
            loaderText.textContent = loaderOptions.text;
        }

        toggleTm8Loader(true);

        window.setTimeout(function() {
            form.submit();
        }, delayMs || 1000);
    });
}

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
        wireEventDescriptionCounter();
        prefillFromTemplate();
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
        wireEventDescriptionCounter();
        prefillFromTemplate();
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
    wireTm8Loader('new-event-form', 1000, {
        title: 'Vi opretter begivenhed',
        text: 'Et øjeblik mens vi gemmer din nye begivenhed.'
    });
}

// Live counter for event description (create modal)
function wireEventDescriptionCounter() {
    const textarea = document.getElementById('event-description');
    const counter = document.getElementById('event-description-counter');
    const MAX = 800;
    if (!textarea || !counter) return;
    function updateCounter() {
        counter.textContent = `${textarea.value.length}/${MAX}`;
    }
    if (!textarea.dataset.counterWired) {
        textarea.addEventListener('input', function() {
            if (textarea.value.length > MAX) {
                textarea.value = textarea.value.slice(0, MAX);
            }
            updateCounter();
        });
        textarea.dataset.counterWired = 'true';
    }
    updateCounter();
}

// Prefill create modal fields from a template event id in query
function prefillFromTemplate() {
    try {
        const params = new URLSearchParams(window.location.search);
        const templateEventId = params.get('templateEventId');
        if (!templateEventId) return;
        fetch(`/events/${templateEventId}.json`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                const title = document.getElementById('event-title');
                const location = document.getElementById('event-location');
                const description = document.getElementById('event-description');
                const start = document.getElementById('event-start');
                const end = document.getElementById('event-end');
                const limit = document.getElementById('participant-limit');
                if (title) title.value = data.eventName || '';
                if (location) location.value = data.location || '';
                if (description) { description.value = data.description || ''; }
                if (limit) limit.value = data.participantLimit || '';
                if (start) start.value = '';
                if (end) end.value = '';
                wireEventDescriptionCounter();
            }).catch(() => {});
    } catch(_) {}
}

// Initialize on load as well (for pages where modal is already in DOM)
document.addEventListener('DOMContentLoaded', function() {
    wireEventDescriptionCounter();
    wireTm8Loader('taskWizard', 1000);
    wireTm8Loader('shiftWizard', 1000);
    try {
        const params = new URLSearchParams(window.location.search);
        if (params.get('open') === 'create') {
            const btn = document.querySelector('.create-event-btn-header');
            if (btn) btn.click();
        }
    } catch(_) {}
    // If template is selected, fetch event data and store for invite prefill
    try {
        const params = new URLSearchParams(window.location.search);
        const templateEventId = params.get('templateEventId');
        if (templateEventId) {
            const keepKey = localStorage.getItem('template_keep_members_event_id');
            if (keepKey && keepKey === templateEventId) {
                fetch(`/events/${templateEventId}/participants-list`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.ok ? r.json() : [])
                    .then(list => {
                        const emails = (list || []).map(p => p.email).filter(Boolean);
                        localStorage.setItem('template_keep_members_payload', JSON.stringify({ eventId: templateEventId, emails }));
                    }).catch(() => {});
            }
        }
    } catch(_) {}
});

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
            document.body.style.position = 'relative';
        }
    });

    const closeMobileBtn = document.getElementById('close-mobile-nav');
    if (closeMobileBtn) {
        closeMobileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
            document.body.style.position = '';
        });
    }

    mobileNavOverlay.addEventListener('click', function(e) {
        if (e.target === mobileNavOverlay) {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
            document.body.style.position = '';
        }
    });
   
    mobileNavOverlay.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileNavOverlay.classList.remove('open');
            document.body.style.overflow = '';
            document.body.style.position = '';
        });
    });
}
// Sync dark mode toggle in mobile nav with main toggle (id matches markup)
const themeToggleBtnMobile = document.getElementById('mobile-theme-toggle-btn');
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
            // Open settings modal
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) {
                settingsModal.style.display = 'flex';
                // Focus on first input
                const firstInput = settingsModal.querySelector('input');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 200);
                }
            }
            userProfileDropdown.classList.remove('open');
        });
    }
}

// Mobile settings button functionality
function wireMobileUserDropdown() {
    const mobileUserTrigger = document.getElementById('mobile-user-trigger');
    const mobileUserDropdown = document.getElementById('mobile-user-dropdown');
    const mobileSettingsBtn = document.getElementById('mobile-settings-btn');
    if (mobileUserTrigger && mobileUserDropdown) {
        mobileUserTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            mobileUserDropdown.classList.toggle('open');
            mobileUserTrigger.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (!mobileUserDropdown.contains(e.target) && e.target !== mobileUserTrigger) {
                mobileUserDropdown.classList.remove('open');
                mobileUserTrigger.classList.remove('active');
            }
        });
    }
    if (mobileSettingsBtn) {
        mobileSettingsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) {
                settingsModal.style.display = 'flex';
                const firstInput = settingsModal.querySelector('input');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 200);
                }
            }
            const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
            if (mobileNavOverlay) {
                mobileNavOverlay.classList.remove('open');
                document.body.style.overflow = '';
                document.body.style.position = '';
            }
        });
    }
    const mobileCreateEvent = document.getElementById('mobile-create-event');
    if (mobileCreateEvent) {
        mobileCreateEvent.addEventListener('click', function(e) {
            e.preventDefault();
            const openBtnMobile = document.querySelector('.create-event-btn-header.mobile');
            if (openBtnMobile) {
                openBtnMobile.click();
            } else {
                // Fallback: open modal directly
                const modal = document.getElementById('new-event-modal');
                if (modal) {
                    modal.style.display = 'flex';
                }
            }
        });
    }
}
wireMobileUserDropdown();

// Settings modal functionality
const settingsModal = document.getElementById('settings-modal');
const closeSettingsModalBtn = document.getElementById('close-settings-modal-btn');
const cancelSettingsBtn = document.getElementById('cancel-settings-btn');

function resetSettingsModal() {
    if (settingsModal) {
        const modalContent = settingsModal.querySelector('.modal-content');
        modalContent.innerHTML = `
            <div class="modal-header">
                <div class="modal-header-content">
                    <div class="modal-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                    <div class="modal-title">
                        <h2>Indstillinger</h2>
                        <p class="modal-subtitle">Administrer din konto</p>
                    </div>
                </div>
                <button class="modal-close-btn" id="close-settings-modal-btn" aria-label="Luk">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <form id="change-password-form" class="modal-form" method="POST" action="{{ route('user.change-password') }}">
                @csrf
                
                <div class="form-section">
                    <h3 class="section-title">Skift adgangskode</h3>
                    <div class="form-row">
                        <label for="current-password">Nuværende adgangskode</label>
                        <input type="password" id="current-password" name="current_password" required placeholder="Indtast din nuværende adgangskode">
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="new-password">Ny adgangskode</label>
                        <input type="password" id="new-password" name="new_password" required placeholder="Indtast din nye adgangskode">
                        @error('new_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-row">
                        <label for="new-password-confirm">Bekræft ny adgangskode</label>
                        <input type="password" id="new-password-confirm" name="new_password_confirmation" required placeholder="Gentag din nye adgangskode">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn secondary-btn" id="cancel-settings-btn">Annuller</button>
                    <button type="submit" class="btn primary-btn">Skift adgangskode</button>
                </div>
            </form>
        `;
        
        // Re-initialize event listeners
        initializeSettingsModalListeners();
    }
}

function initializeSettingsModalListeners() {
    // Re-get elements after reset
    const newCloseSettingsModalBtn = document.getElementById('close-settings-modal-btn');
    const newCancelSettingsBtn = document.getElementById('cancel-settings-btn');
    const newPasswordForm = document.getElementById('change-password-form');
    
    // Set up close button
    if (newCloseSettingsModalBtn) {
        newCloseSettingsModalBtn.addEventListener('click', function() {
            settingsModal.style.display = 'none';
        });
    }
    
    // Set up cancel button
    if (newCancelSettingsBtn) {
        newCancelSettingsBtn.addEventListener('click', function() {
            settingsModal.style.display = 'none';
        });
    }
    
    // Set up password form
    if (newPasswordForm) {
        newPasswordForm.addEventListener('submit', function(e) {
            const submitBtn = newPasswordForm.querySelector('.primary-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Skifter adgangskode...';
            submitBtn.disabled = true;
            
            // Add loading spinner
            const spinner = document.createElement('div');
            spinner.className = 'loading-spinner';
            spinner.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/>
                </svg>
            `;
            submitBtn.appendChild(spinner);
        });
    }
}

if (settingsModal) {
    // Close modal when clicking outside
    settingsModal.addEventListener('click', function(e) {
        if (e.target === settingsModal) {
            settingsModal.style.display = 'none';
        }
    });
    
    // Close modal with close button
    if (closeSettingsModalBtn) {
        closeSettingsModalBtn.addEventListener('click', function() {
            settingsModal.style.display = 'none';
        });
    }
    
    // Close modal with cancel button
    if (cancelSettingsBtn) {
        cancelSettingsBtn.addEventListener('click', function() {
            settingsModal.style.display = 'none';
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && settingsModal.style.display === 'flex') {
            settingsModal.style.display = 'none';
        }
    });
    
    // Handle password change form submission
    const passwordForm = document.getElementById('change-password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            // Show loading state
            const submitBtn = passwordForm.querySelector('.primary-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Skifter adgangskode...';
            submitBtn.disabled = true;
            
            // Add loading spinner
            const spinner = document.createElement('div');
            spinner.className = 'loading-spinner';
            spinner.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/>
                </svg>
            `;
            submitBtn.appendChild(spinner);
            
            // Allow form to submit - success will be handled by server redirect
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const successMsg = document.getElementById('event-success-message');
    if (successMsg) {
        // Check if it's a password change success message
        if (successMsg.querySelector('.modal-success')) {
            // Show in settings modal if it's open, otherwise show in main modal
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal && settingsModal.style.display === 'flex') {
                // Show success in settings modal
                const modalContent = settingsModal.querySelector('.modal-content');
                modalContent.innerHTML = successMsg.querySelector('.modal-success').outerHTML;
                
                // Add close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'modal-close-btn';
                closeBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                `;
                closeBtn.addEventListener('click', function() {
                    settingsModal.style.display = 'none';
                    // Reset modal content after closing
                    setTimeout(() => {
                        resetSettingsModal();
                        window.location.reload();
                    }, 300);
                });
                modalContent.appendChild(closeBtn);
                
                // Auto-close after 3 seconds
                setTimeout(() => {
                    if (settingsModal.style.display === 'flex') {
                        settingsModal.style.display = 'none';
                        setTimeout(() => {
                            resetSettingsModal();
                            window.location.reload();
                        }, 300);
                    }
                }, 3000);
            } else {
                // Show in main modal
                modal.style.display = 'flex';
                modalContent.innerHTML = successMsg.querySelector('.modal-success').outerHTML;
                
                // Add close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'modal-close-btn';
                closeBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                `;
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                });
                modalContent.appendChild(closeBtn);
                
                setTimeout(() => {
                    modal.style.display = 'none';
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                }, 3000);
            }
        } else {
            // Regular event success message
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
            // Show loading state
            const submitBtn = newForm.querySelector('.primary-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Opretter...';
            submitBtn.disabled = true;
            
            // Allow the form to actually submit to the server
            // The success message will be handled by the server redirect
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
// New Mobile Nav Controller (.mnav)
(function() {
    const toggleBtn = document.getElementById('mobile-menu-btn');
    const mnav = document.getElementById('mnav');
    if (!toggleBtn || !mnav) return;

    const backdrop = document.getElementById('mnav-backdrop');
    const panel = mnav.querySelector('.mnav__panel');
    const closeBtn = document.getElementById('mnav-close');
    const userBtn = document.getElementById('mnav-user');
    const submenu = document.getElementById('mnav-user-menu');
    const languageBtn = document.getElementById('mnav-language');
    const languageMenu = document.getElementById('mnav-language-menu');
    const createBtn = document.getElementById('mnav-create');
    const settingsBtnMobile = document.getElementById('mnav-settings');
    const localeForm = document.getElementById('locale-switcher-form');
    const localeInput = document.getElementById('locale-switcher-input');
    const localeTrigger = document.getElementById('locale-switcher-trigger');
    const localeMenu = document.getElementById('locale-switcher-menu');

    function openNav() {
        mnav.classList.add('is-open');
        mnav.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeNav() {
        mnav.classList.remove('is-open');
        mnav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (userBtn) userBtn.setAttribute('aria-expanded', 'false');
        if (submenu) submenu.hidden = true;
        if (languageBtn) languageBtn.setAttribute('aria-expanded', 'false');
        if (languageMenu) languageMenu.hidden = true;
    }

    toggleBtn.addEventListener('click', (e) => { e.preventDefault(); openNav(); });
    if (backdrop) backdrop.addEventListener('click', closeNav);
    if (closeBtn) closeBtn.addEventListener('click', closeNav);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeNav(); });

    // Theme button removed from mobile menu; uses header toggle only
    if (userBtn && submenu) {
        userBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const expanded = userBtn.getAttribute('aria-expanded') === 'true';
            userBtn.setAttribute('aria-expanded', String(!expanded));
            submenu.hidden = expanded;
        });
        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target)) {
                userBtn.setAttribute('aria-expanded', 'false');
                submenu.hidden = true;
            }
        });
    }
    if (createBtn) {
        createBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeNav();
            const openBtn = document.querySelector('.create-event-btn-header:not(.mobile)') || document.querySelector('.create-event-btn-header.mobile');
            openBtn && openBtn.click();
        });
    }
    if (settingsBtnMobile) {
        settingsBtnMobile.addEventListener('click', (e) => {
            e.preventDefault();
            closeNav();
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) settingsModal.style.display = 'flex';
        });
    }
    if (languageBtn && languageMenu) {
        languageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const expanded = languageBtn.getAttribute('aria-expanded') === 'true';
            languageBtn.setAttribute('aria-expanded', String(!expanded));
            languageMenu.hidden = expanded;
        });
    }
    document.querySelectorAll('.mnav__action--locale').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const locale = button.dataset.locale;
            if (!locale || !localeForm || !localeInput || localeInput.value === locale) {
                closeNav();
                return;
            }

            localeInput.value = locale;
            if (localeMenu) {
                localeMenu.hidden = true;
            }
            if (localeTrigger) {
                localeTrigger.setAttribute('aria-expanded', 'false');
            }
            closeNav();
            localeForm.submit();
        });
    });
})();

// Settings Tabs Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.settings-tab-btn');
    const tabContents = document.querySelectorAll('.settings-tab-content');
    const cancelNotificationsBtn = document.getElementById('cancel-notifications-btn');
    const notificationCenter = document.querySelector('.notification-center');
    const notificationBtnHeader = document.getElementById('notification-btn-header');
    const notificationPanel = document.getElementById('notification-panel');
    const notificationMarkReadBtn = document.getElementById('notification-mark-read-btn');
    const notificationCountPill = document.getElementById('notification-count-pill');

    const notificationList = document.getElementById('notification-list');
    const notificationLoadingState = document.getElementById('notification-loading-state');
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const notificationApiUrl = notificationCenter?.dataset.notificationsUrl || '/notifications';
    const notificationCountUrl = notificationCenter?.dataset.notificationsCountUrl || '/notifications/count';
    const notificationMarkReadBaseUrl = notificationCenter?.dataset.notificationMarkReadBase || '/notifications';

    let cachedNotifications = [];
    let notificationsLoadingPromise = null;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');
    }

    function getCsrfToken() {
        return csrfTokenMeta ? (csrfTokenMeta.getAttribute('content') || '') : '';
    }

    function formatRelativeTime(isoString) {
        if (!isoString) {
            return '';
        }

        const createdAt = new Date(isoString);
        if (Number.isNaN(createdAt.getTime())) {
            return '';
        }

        const diffSeconds = Math.max(0, Math.floor((Date.now() - createdAt.getTime()) / 1000));
        if (diffSeconds < 60) {
            return diffSeconds <= 1 ? 'Lige nu' : `${diffSeconds} sek`;
        }

        const diffMinutes = Math.floor(diffSeconds / 60);
        if (diffMinutes < 60) {
            return diffMinutes === 1 ? '1 min' : `${diffMinutes} min`;
        }

        const diffHours = Math.floor(diffMinutes / 60);
        if (diffHours < 24) {
            return diffHours === 1 ? '1 t' : `${diffHours} t`;
        }

        const diffDays = Math.floor(diffHours / 24);
        return diffDays === 1 ? '1 dag' : `${diffDays} dage`;
    }

    function setNotificationBadgeCount(unreadCount) {
        const normalizedUnreadCount = Number(unreadCount) || 0;

        if (!notificationBtnHeader || !notificationCountPill) {
            return;
        }

        const badge = notificationBtnHeader.querySelector('.notification-btn-header__badge');
        if (badge) {
            badge.hidden = normalizedUnreadCount <= 0;
            badge.style.display = normalizedUnreadCount > 0 ? '' : 'none';
            badge.textContent = normalizedUnreadCount > 0 ? String(normalizedUnreadCount) : '';
        }

        notificationCountPill.hidden = normalizedUnreadCount <= 0;
        notificationCountPill.textContent = normalizedUnreadCount === 1 ? '1 ny' : `${normalizedUnreadCount} nye`;

        if (notificationMarkReadBtn) {
            notificationMarkReadBtn.hidden = normalizedUnreadCount <= 0;
            notificationMarkReadBtn.disabled = normalizedUnreadCount <= 0;
        }
    }

    function renderNotifications(notifications) {
        if (!notificationList) {
            return;
        }

        cachedNotifications = Array.isArray(notifications) ? notifications : [];

        const itemsHtml = cachedNotifications.map((notification) => {
            const title = notification.event?.eventName || notification.message || 'Notifikation';
            const text = notification.event?.eventName && notification.message && notification.event.eventName !== notification.message
                ? notification.message
                : '';

            return `
                <li class="notification-item${notification.isRead ? '' : ' notification-item--unread'}" data-notification-id="${notification.id}">
                    <span class="notification-item__dot" aria-hidden="true"></span>
                    <div class="notification-item__body">
                        <p class="notification-item__title">${escapeHtml(title)}</p>
                        ${text ? `<p class="notification-item__text">${escapeHtml(text)}</p>` : ''}
                    </div>
                    <span class="notification-item__time">${escapeHtml(formatRelativeTime(notification.created_at))}</span>
                </li>
            `;
        }).join('');

        notificationList.innerHTML = `${itemsHtml || ''}`;

        if (notificationLoadingState) {
            notificationLoadingState.remove();
        }

        if (cachedNotifications.length === 0) {
            const emptyState = document.createElement('li');
            emptyState.className = 'notification-item';
            emptyState.id = 'notification-empty-state';

            const body = document.createElement('div');
            body.className = 'notification-item__body';

            const title = document.createElement('p');
            title.className = 'notification-item__title';
            title.textContent = 'Ingen notifikationer endnu.';

            body.appendChild(title);
            emptyState.appendChild(body);
            notificationList.appendChild(emptyState);
        }
    }

    async function fetchJson(url, options = {}) {
        const method = (options.method || 'GET').toUpperCase();
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                ...(method !== 'GET' ? { 'X-CSRF-TOKEN': getCsrfToken() } : {}),
                ...(options.headers || {}),
            },
            ...options,
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        return response.json();
    }

    async function loadNotifications() {
        if (notificationsLoadingPromise) {
            return notificationsLoadingPromise;
        }

        notificationsLoadingPromise = (async () => {
            try {
                const payload = await fetchJson(notificationApiUrl);
                const notifications = Array.isArray(payload.notifications) ? payload.notifications : [];
                renderNotifications(notifications);
                setNotificationBadgeCount(payload.unreadCount ?? notifications.filter(notification => !notification.isRead).length);
            } catch (error) {
                if (notificationList) {
                    notificationList.innerHTML = `
                        <li class="notification-item">
                            <div class="notification-item__body">
                                <p class="notification-item__title">Kunne ikke indlæse notifikationer.</p>
                            </div>
                        </li>
                    `;
                }
                setNotificationBadgeCount(0);
            } finally {
                notificationsLoadingPromise = null;
            }
        })();

        return notificationsLoadingPromise;
    }

    async function refreshNotificationCount() {
        try {
            const payload = await fetchJson(notificationCountUrl);
            setNotificationBadgeCount(payload.unreadCount ?? 0);
        } catch (error) {
            setNotificationBadgeCount(cachedNotifications.filter(notification => !notification.isRead).length);
        }
    }

    function getUnreadNotifications() {
        return cachedNotifications.filter(notification => !notification.isRead);
    }

    function closeNotificationPanel() {
        if (!notificationBtnHeader || !notificationPanel) {
            return;
        }

        notificationBtnHeader.setAttribute('aria-expanded', 'false');
        notificationPanel.hidden = true;
    }

    function openNotificationPanel() {
        if (!notificationBtnHeader || !notificationPanel) {
            return;
        }

        notificationBtnHeader.setAttribute('aria-expanded', 'true');
        notificationPanel.hidden = false;
    }

    function toggleNotificationPanel() {
        if (!notificationBtnHeader || !notificationPanel) {
            return;
        }

        if (notificationPanel.hidden) {
            openNotificationPanel();
        } else {
            closeNotificationPanel();
        }
    }

    if (notificationBtnHeader && notificationPanel) {
        notificationBtnHeader.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleNotificationPanel();
            const userProfileDropdown = document.querySelector('.user-profile-dropdown');
            if (userProfileDropdown) {
                userProfileDropdown.classList.remove('open');
            }
        });

        loadNotifications();

        document.addEventListener('click', function(e) {
            if (notificationCenter && !notificationCenter.contains(e.target)) {
                closeNotificationPanel();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNotificationPanel();
            }
        });
    }

    if (notificationMarkReadBtn) {
        notificationMarkReadBtn.addEventListener('click', async function(e) {
            e.preventDefault();

            const unreadNotifications = getUnreadNotifications();
            if (unreadNotifications.length === 0) {
                return;
            }

            notificationMarkReadBtn.disabled = true;
            notificationMarkReadBtn.textContent = 'Gemmer...';

            try {
                await Promise.all(unreadNotifications.map(async (notification) => {
                    await fetchJson(`${notificationMarkReadBaseUrl}/${notification.id}/mark-as-read`, {
                        method: 'POST',
                    });
                    notification.isRead = true;
                }));

                renderNotifications(cachedNotifications);
                await refreshNotificationCount();
            } catch (error) {
                notificationMarkReadBtn.textContent = 'Prøv igen';
            } finally {
                notificationMarkReadBtn.disabled = false;
            }
        });
    }

    refreshNotificationCount();

    // Tab switching
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            const activeContent = document.querySelector(`[data-tab-content="${tabName}"]`);
            if (activeContent) {
                activeContent.classList.add('active');
            }
        });
    });

    // Cancel notifications button
    if (cancelNotificationsBtn) {
        cancelNotificationsBtn.addEventListener('click', function() {
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) {
                settingsModal.style.display = 'none';
            }
        });
    }

    // Settings modal close button
    const closeSettingsBtn = document.getElementById('close-settings-modal-btn');
    if (closeSettingsBtn) {
        closeSettingsBtn.addEventListener('click', function() {
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) {
                settingsModal.style.display = 'none';
            }
        });
    }

    // Cancel password settings button
    const cancelSettingsBtn = document.getElementById('cancel-settings-btn');
    if (cancelSettingsBtn) {
        cancelSettingsBtn.addEventListener('click', function() {
            const settingsModal = document.getElementById('settings-modal');
            if (settingsModal) {
                settingsModal.style.display = 'none';
            }
        });
    }

});
<header class="main-header{{ (!Auth::check() && ($currentPage ?? null) === 'dashboard') ? ' guest-dashboard' : '' }}">
    <div class="header-left">
        <div class="logo">
            <a href="/dashboard" class="logo-link" aria-label="Gå til forside">
                <img src="{{ asset('TaskM8-Logo.png') }}" alt="TaskM8 Logo" class="logo-img logo-img-dark" />
                <img src="{{ asset('TaskM8-Logo-Dark.png') }}" alt="TaskM8 Logo Dark" class="logo-img logo-img-light" />
            </a>
        </div>
        <nav class="navigation" id="main-nav">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Forside</a></li>
                @if (Auth::check())
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Begivenheder</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Grupper</a></li>
                @endif
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
        @if (Auth::check())
        <div class="user-profile-dropdown">
            <button class="user-profile-trigger" id="user-profile-trigger" aria-label="Open user menu">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info-header">
                    <p class="user-greeting">Velkommen, {{ Auth::user()->name }}!</p>
                </div>
                <svg class="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6,9 12,15 18,9"></polyline>
                </svg>
            </button>
            <div class="user-dropdown-menu" id="user-dropdown-menu">
                <div class="dropdown-header">
                    <div class="dropdown-user-info">
                        <div class="dropdown-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="dropdown-user-details">
                            <p class="dropdown-user-name">{{ Auth::user()->name }}</p>
                            <p class="dropdown-user-email">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-items">
                    <button class="dropdown-item" id="settings-btn">
                        <svg class="dropdown-item-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        Skift adgangskode
                    </button>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" class="dropdown-logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-logout">
                            <svg class="dropdown-item-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Log ud
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="login-header">
            <a href="{{ route('login') }}" class="btn primary-btn">Log ind</a>
        </div>
        @endif
        @if (Auth::check())
        <button class="create-event-btn-header"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Ny Begivenhed</button>
        @endif
    </div>
 </header>
<!-- New Event Modal -->
<div id="new-event-modal" class="header-modal">
    <div class="modal-content" id="modal-content">
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon">
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
                    <div style="position: relative;">
                        <textarea id="event-description" name="description" rows="3" required placeholder="Beskriv begivenheden" maxlength="800" style="padding-bottom: 22px;"></textarea>
                        <span id="event-description-counter" style="position: absolute; bottom: 6px; right: 8px; font-size: 12px; color: var(--text-muted, #6b7280);">0/800</span>
                    </div>
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
                <h3 class="section-title">Deltagerbegrænsning (valgfri)</h3>
                <div class="form-row participant-limit">
                <label for="participant-limit">Maks antal deltagere</label>
                <input type="number" id="participant-limit" name="participantLimit" placeholder="Indtast maks antal deltagere" />
            </div>
            </div>

            
            <div class="form-actions">
                <button type="button" class="btn secondary-btn" id="cancel-btn">Annuller</button>
                <button type="submit" class="btn primary-btn">Opret begivenhed</button>
            </div>
        </form>
    </div>
</div>

<!-- Settings Modal -->
<div id="settings-modal" class="header-modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon">
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
    </div>
</div>

<!-- Mobile Header/Nav Menu -->
<div id="mnav" class="mnav" aria-hidden="true">
    <div class="mnav__backdrop" id="mnav-backdrop"></div>
    <aside class="mnav__panel" role="dialog" aria-modal="true" aria-labelledby="mnav-title">
        <header class="mnav__header">
            <div class="mnav__brand" id="mnav-title" aria-label="TaskM8">
                <a href="/dashboard" class="logo-link" aria-label="Gå til forside">
                    <img src="{{ asset('TaskM8-Logo.png') }}" alt="TaskM8" class="logo-img logo-img-dark" />
                    <img src="{{ asset('TaskM8-Logo-Dark.png') }}" alt="TaskM8" class="logo-img logo-img-light" />
                </a>
            </div>
            <button class="mnav__close" id="mnav-close" aria-label="Luk menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </header>
        <nav class="mnav__nav" aria-label="Primær">
            <ul class="mnav__list">
                <li class="mnav__item"><a class="mnav__link {{ $currentPage == 'dashboard' ? 'is-active' : '' }}" href="/dashboard">Forside</a></li>
                @if (Auth::check())
                <li class="mnav__item"><a class="mnav__link {{ $currentPage == 'events' ? 'is-active' : '' }}" href="/events">Begivenheder</a></li>
                <li class="mnav__item"><a class="mnav__link {{ $currentPage == 'groups' ? 'is-active' : '' }}" href="/groups">Grupper</a></li>
                @endif
            </ul>
        </nav>
        
        @if (Auth::check())
        <div class="mnav__section">
            <button class="mnav__user" id="mnav-user" aria-expanded="false" aria-controls="mnav-user-menu">
                <span class="mnav__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <span class="mnav__username">Velkommen, {{ Auth::user()->name }}!</span>
                <svg class="mnav__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6,9 12,15 18,9"/></svg>
            </button>
            <ul class="mnav__submenu" id="mnav-user-menu" hidden>
                <li><button class="mnav__action mnav__action--primary" id="mnav-create">Ny begivenhed</button></li>
                <li><button class="mnav__action" id="mnav-settings">Skift adgangskode</button></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mnav__action mnav__action--danger">Log ud</button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <div class="mnav__section">
            <a href="{{ route('login') }}" class="mnav__action mnav__action--primary">Log ind</a>
        </div>
        @endif
    </aside>
</div>

@if(session('success'))
    <div id="event-success-message" style="display:none;">
        @if(str_contains(session('success'), 'adgangskode'))
            <div class="modal-success">
                <div class="checkmark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 12l2.5 2.5L16 9"/>
                    </svg>
                </div>
                <h3>Adgangskode ændret!</h3>
                <p>{{ session('success') }}</p>
            </div>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif

<script src="{{ asset('js/theme-toggle.js') }}"></script>
<script src="{{ asset('js/header.js') }}"></script>
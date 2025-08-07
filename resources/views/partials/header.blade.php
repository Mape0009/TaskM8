<header class="main-header">
    <div class="header-left">
        <div class="logo">
            <img src="{{ asset('TaskM8-Logo.png') }}" alt="TaskM8 Logo" class="logo-img logo-img-dark" />
            <img src="{{ asset('TaskM8-Logo-Dark.png') }}" alt="TaskM8 Logo Dark" class="logo-img logo-img-light" />
        </div>
        <nav class="navigation" id="main-nav">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Forside</a></li>
                @if (Auth::check())
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Begivenheder</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Medlemmer</a></li>
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
                        Indstillinger
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
            <a href="{{ route('login') }}" class="btn login-btn">Log ind</a>
        </div>
        @endif
        @if (Auth::check())
        <button class="create-event-btn-header"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Ny Begivenhed</button>
        @endif
    </div>
</header>
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<!-- New Event Modal -->
<div id="new-event-modal" class="modal">
    <div class="modal-content" id="modal-content">
        <div class="modal-header">
            <span class="modal-icon">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </span>
            <h2>Lav ny begivenhed</h2>
            <button class="modal-close-btn" id="close-modal-btn" aria-label="Close">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="new-event-form" class="modal-form" autocomplete="off" method="POST" action="{{ route('events.create') }}">
            @csrf
            <div>
                <label for="event-title">Title</label>
                <input type="text" id="event-title" name="eventName" required placeholder="Hvad er titlen?">
            </div>
            <div>
                <label for="event-start">Start Tid</label>
                <input type="datetime-local" id="event-start" name="startDate" required>
            </div>
            <div>
                <label for="event-end">Slut tid</label>
                <input type="datetime-local" id="event-end" name="endDate" required>
            </div>
            <div class="repeat-container">
                <label class="repeat-checkbox-label">
                    <input type="checkbox" id="event-repeat" name="repeat"> Gentagelse
                </label>
                <div id="repeat-options" class="repeat-options" style="display: none;">
                    <label for="repeat-interval">Interval</label>
                    <select id="repeat-interval" name="repeat_interval" class="repeat-select">
                        <option value="daily">Dagligt</option>
                        <option value="weekly">Ugenligt</option>
                        <option value="monthly">Månedligt</option>
                        <option value="monthly">Årligt</option>
                    </select>
                    <input type="text" id="custom-interval" name="custom_interval" placeholder="" class="custom-interval-input" style="display: none;">
                </div>
            </div>
            <div>
                <label for="event-location">Lokation</label>
                <input type="text" id="event-location" name="location" required placeholder="Hvor er lokationen?">
            </div>
            <div>
                <label for="event-description">Beskrivelse</label>
                <textarea id="event-description" name="description" rows="3" required placeholder="Hvad er begivenheden om?"></textarea>
            </div>
            <button type="submit" class="btn primary-btn">Lav Begivenhed</button>
        </form>
    </div>
</div>

<!-- Mobile Nav Overlay -->
<div id="mobile-nav-overlay" class="mobile-nav-overlay">
    <div class="mobile-nav-content minimal glassy integrated-dropdown"> 
        
        <nav class="mobile-navigation minimal premium integrated">
            <ul>
                <li><a href="/dashboard" class="{{ $currentPage == 'dashboard' ? 'active' : '' }}">Forside</a></li>
                @if (Auth::check())
                <li><a href="/events" class="{{ $currentPage == 'events' ? 'active' : '' }}">Begivenheder</a></li>
                <li><a href="/friends" class="{{ $currentPage == 'friends' ? 'active' : '' }}">Medlemmer</a></li>
                @endif
            </ul>
        </nav>
        
        <div class="mobile-divider integrated"></div>
        <div class="mobile-theme-toggle">
            <button class="theme-toggle-btn mobile" id="mobile-theme-toggle-btn" aria-label="Toggle dark/light mode">
                <svg class="icon sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg class="icon moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
                <span>Skift tema</span>
            </button>
        </div>
        
        @if (Auth::check())
        <div class="mobile-divider integrated"></div>
        <div class="mobile-user-section">
            <div class="mobile-user-profile minimal integrated">
                <div class="user-avatar integrated">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info-header">
                    <p class="user-greeting">Velkommen, {{ Auth::user()->name }}!</p>
                </div>
            </div>
            <div class="mobile-actions">
                <button class="create-event-btn-header mobile minimal premium integrated">+ Ny Begivenhed</button>
                <button class="mobile-settings-btn" id="mobile-settings-btn">
                    <svg class="mobile-settings-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    Indstillinger
                </button>
                <form action="{{ route('logout') }}" method="POST" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="btn logout-btn mobile">Log ud</button>
                </form>
            </div>
        </div>
        @else
        <div class="mobile-divider integrated"></div>
        <div class="mobile-user-section">
            <div class="mobile-user-profile minimal integrated">
                <div class="user-avatar integrated">?</div>
                <div class="user-info-header">
                    <p class="user-greeting">Ikke logget ind</p>
                </div>
            </div>
            <div class="mobile-actions">
                <a href="{{ route('login') }}" class="btn login-btn mobile">Log ind</a>
            </div>
        </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div id="event-success-message" style="display:none;">{{ session('success') }}</div>
@endif

<script src="{{ asset('js/theme-toggle.js') }}"></script>
<script src="{{ asset('js/header.js') }}"></script>
<?php
    $supportedLocales = [
        'da' => ['label' => 'Dansk', 'icon' => '🇩🇰', 'code' => 'DK'],
        'en' => ['label' => 'English', 'icon' => '🇬🇧', 'code' => 'EN'],
        'es' => ['label' => 'Español', 'icon' => '🇪🇸', 'code' => 'ES'],
        'fi' => ['label' => 'Suomi', 'icon' => '🇫🇮', 'code' => 'FI'],
        'it' => ['label' => 'Italiano', 'icon' => '🇮🇹', 'code' => 'IT'],
        'uk' => ['label' => 'Українська', 'icon' => '🇺🇦', 'code' => 'UK'],
        'ru' => ['label' => 'Русский', 'icon' => '🇷🇺', 'code' => 'RU'],
    ];
    $currentLocale = app()->getLocale();
    $currentLocaleData = $supportedLocales[$currentLocale] ?? ['label' => strtoupper($currentLocale), 'icon' => '🌐', 'code' => strtoupper($currentLocale)];
?>
<header class="main-header<?php echo e((!Auth::check() && ($currentPage ?? null) === 'dashboard') ? ' guest-dashboard' : ''); ?>">
    <div class="header-left">
        <div class="logo">
            <a href="/dashboard" class="logo-link" aria-label="<?php echo e(__('ui.dashboard')); ?>">
                <img src="<?php echo e(asset('TaskM8-Logo.png')); ?>" alt="TaskM8 Logo" class="logo-img logo-img-dark" />
                <img src="<?php echo e(asset('TaskM8-Logo-Dark.png')); ?>" alt="TaskM8 Logo Dark" class="logo-img logo-img-light" />
            </a>
        </div>
        <nav class="navigation" id="main-nav">
            <ul>
                <li><a href="/dashboard" class="<?php echo e($currentPage == 'dashboard' ? 'active' : ''); ?>"><?php echo e(__('ui.dashboard')); ?></a></li>
                <?php if(Auth::check()): ?>
                <li><a href="/events" class="<?php echo e($currentPage == 'events' ? 'active' : ''); ?>"><?php echo e(__('ui.events')); ?></a></li>
                <li><a href="/previousEvents" class="<?php echo e($currentPage == 'previousEvents' ? 'active' : ''); ?>"><?php echo e(__('ui.previous_events')); ?></a></li>
                <li><a href="/groups/overview" class="<?php echo e($currentPage == 'groups/overview' ? 'active' : ''); ?>"><?php echo e(__('ui.groups')); ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <div class="header-right">
        <form action="<?php echo e(route('locale.switch')); ?>" method="POST" class="locale-switcher locale-switcher--menu" id="locale-switcher-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="locale" id="locale-switcher-input" value="<?php echo e($currentLocale); ?>">
            <button type="button" class="locale-switcher__trigger" id="locale-switcher-trigger" aria-haspopup="menu" aria-expanded="false" aria-controls="locale-switcher-menu" aria-label="<?php echo e(__('ui.language')); ?>: <?php echo e($currentLocaleData['label']); ?>">
                <span class="locale-switcher__label"><?php echo e($currentLocaleData['code']); ?></span>
                <svg class="locale-switcher__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div class="locale-switcher__menu" id="locale-switcher-menu" role="menu" aria-label="<?php echo e(__('ui.language')); ?>" hidden>
                <div class="locale-switcher__menu-label"><?php echo e(__('ui.language')); ?></div>
                <?php $__currentLoopData = $supportedLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $localeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="locale-switcher__option <?php echo e($currentLocale === $locale ? 'is-active' : ''); ?>" data-locale="<?php echo e($locale); ?>" role="menuitemradio" aria-checked="<?php echo e($currentLocale === $locale ? 'true' : 'false'); ?>">
                        <span class="locale-switcher__option-icon" aria-hidden="true"><?php echo e($localeData['icon']); ?></span>
                        <span class="locale-switcher__option-label"><?php echo e($localeData['label']); ?></span>
                        <?php if($currentLocale === $locale): ?>
                            <svg class="locale-switcher__option-check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        <?php endif; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open menu">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <button class="theme-toggle-btn" id="theme-toggle-btn" aria-label="Toggle dark/light mode">
            <svg class="icon sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <svg class="icon moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
        </button>
        <?php if(Auth::check()): ?>
        <div class="notification-center">
            <button class="notification-btn-header" id="notification-btn-header" aria-label="Open notifications" aria-haspopup="true" aria-expanded="false" aria-controls="notification-panel">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-btn-header__badge" aria-hidden="true">5</span>
            </button>
            <div class="notification-panel" id="notification-panel" hidden>
                <div class="notification-panel__header">
                    <div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('locale-switcher-form');
                        const trigger = document.getElementById('locale-switcher-trigger');
                        const menu = document.getElementById('locale-switcher-menu');
                        const input = document.getElementById('locale-switcher-input');

                        if (!form || !trigger || !menu || !input) return;

                        const closeMenu = () => {
                            menu.hidden = true;
                            trigger.setAttribute('aria-expanded', 'false');
                        };

                        const openMenu = () => {
                            menu.hidden = false;
                            trigger.setAttribute('aria-expanded', 'true');
                        };

                        trigger.addEventListener('click', function () {
                            if (menu.hidden) openMenu();
                            else closeMenu();
                        });

                        menu.querySelectorAll('.locale-switcher__option').forEach(function (option) {
                            option.addEventListener('click', function () {
                                const locale = option.dataset.locale;
                                if (!locale || input.value === locale) {
                                    closeMenu();
                                    return;
                                }
                                input.value = locale;
                                form.submit();
                            });
                        });

                        document.addEventListener('click', function (event) {
                            if (!form.contains(event.target)) closeMenu();
                        });

                        document.addEventListener('keydown', function (event) {
                            if (event.key === 'Escape') closeMenu();
                        });
                    });
                    </script>
                        <p class="notification-panel__eyebrow"><?php echo e(__('ui.notifications')); ?></p>
                        <h3 class="notification-panel__title"><?php echo e(__('ui.latest_notifications')); ?></h3>
                    </div>
                    <div class="notification-panel__actions">
                        <span class="notification-panel__count" id="notification-count-pill">5 nye</span>
                        <button type="button" class="notification-panel__mark-read" id="notification-mark-read-btn"><?php echo e(__('ui.mark_as_read')); ?></button>
                    </div>
                </div>
                <ul class="notification-panel__list" aria-label="Seneste notifikationer">
                    <li class="notification-item notification-item--unread">
                        <span class="notification-item__dot" aria-hidden="true"></span>
                        <div class="notification-item__body">
                            <p class="notification-item__title">Ny begivenhed oprettet</p>
                            <p class="notification-item__text">Mads oprettede “Sommerfest 2026”.</p>
                        </div>
                        <span class="notification-item__time">2 min</span>
                    </li>
                    <li class="notification-item notification-item--unread">
                        <span class="notification-item__dot" aria-hidden="true"></span>
                        <div class="notification-item__body">
                            <p class="notification-item__title">Du er tildelt en vagt</p>
                            <p class="notification-item__text">Fredag 14:00 - 18:00 er nu din.</p>
                        </div>
                        <span class="notification-item__time">15 min</span>
                    </li>
                    <li class="notification-item notification-item--unread">
                        <span class="notification-item__dot" aria-hidden="true"></span>
                        <div class="notification-item__body">
                            <p class="notification-item__title">Ny gruppeinvitation</p>
                            <p class="notification-item__text">Maria inviterede dig til "Skoleoplæringen".</p>
                        </div>
                        <span class="notification-item__time">34 min</span>
                    </li>
                    <li class="notification-item">
                        <span class="notification-item__dot" aria-hidden="true"></span>
                        <div class="notification-item__body">
                            <p class="notification-item__title">Begivenhed opdateret</p>
                            <p class="notification-item__text">Tidsplanen for "Byfest" er ændret.</p>
                        </div>
                        <span class="notification-item__time">1 t</span>
                    </li>
                    <li class="notification-item">
                        <span class="notification-item__dot" aria-hidden="true"></span>
                        <div class="notification-item__body">
                            <p class="notification-item__title">Ny deltager</p>
                            <p class="notification-item__text">Jonas tilmeldte sig "Havnefest".</p>
                        </div>
                        <span class="notification-item__time">2 t</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="user-profile-dropdown">
            <button class="user-profile-trigger" id="user-profile-trigger" aria-label="Open user menu">
                <div class="user-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
                <div class="user-info-header">
                    <p class="user-greeting"><?php echo e(__('ui.welcome', ['name' => Auth::user()->name])); ?></p>
                </div>
                <svg class="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6,9 12,15 18,9"></polyline>
                </svg>
            </button>
            <div class="user-dropdown-menu" id="user-dropdown-menu">
                <div class="dropdown-header">
                    <div class="dropdown-user-info">
                        <div class="dropdown-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
                        <div class="dropdown-user-details">
                            <p class="dropdown-user-name"><?php echo e(Auth::user()->name); ?></p>
                            <p class="dropdown-user-email"><?php echo e(Auth::user()->email); ?></p>
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
                        <?php echo e(__('ui.settings')); ?>

                    </button>
                    <div class="dropdown-divider"></div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="dropdown-logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item dropdown-logout">
                            <svg class="dropdown-item-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <?php echo e(__('ui.logout')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="login-header">
            <a href="<?php echo e(route('login')); ?>" class="btn primary-btn"><?php echo e(__('ui.login')); ?></a>
        </div>
        <?php endif; ?>
        <?php if(Auth::check()): ?>
        <button class="create-event-btn-header"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> <?php echo e(__('ui.create_event')); ?></button>
        <?php endif; ?>
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
                    <h2><?php echo e(__('ui.create_new_event')); ?></h2>
                    <p class="modal-subtitle"><?php echo e(__('ui.fill_information')); ?></p>
                </div>
            </div>
            <button class="modal-close-btn" id="close-modal-btn" aria-label="Luk">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="new-event-form" class="modal-form" autocomplete="off" method="POST" action="<?php echo e(route('events.create')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('ui.basic_information')); ?></h3>
                <div class="form-row">
                    <label for="event-title"><?php echo e(__('ui.title')); ?></label>
                    <input type="text" id="event-title" name="eventName" required placeholder="<?php echo e(__('ui.title')); ?>">
                </div>
                <div class="form-row">
                    <label for="event-location"><?php echo e(__('ui.location')); ?></label>
                    <input type="text" id="event-location" name="location" required placeholder="<?php echo e(__('ui.location')); ?>">
                </div>
                <div class="form-row">
                    <label for="event-description"><?php echo e(__('ui.description')); ?></label>
                    <div style="position: relative;">
                        <textarea id="event-description" name="description" rows="3" required placeholder="<?php echo e(__('ui.describe_event')); ?>" maxlength="800" style="padding-bottom: 22px;"></textarea>
                        <span id="event-description-counter" style="position: absolute; bottom: 6px; right: 8px; font-size: 12px; color: var(--text-muted, #6b7280);">0/800</span>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('ui.time')); ?></h3>
                <div class="form-row">
                    <label for="event-start"><?php echo e(__('ui.start_time')); ?></label>
                    <input type="datetime-local" id="event-start" name="startDate" required>
                </div>
                <div class="form-row">
                    <label for="event-end"><?php echo e(__('ui.end_time')); ?></label>
                    <input type="datetime-local" id="event-end" name="endDate" required>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title"><?php echo e(__('ui.participant_limit_optional')); ?></h3>
                <div class="form-row participant-limit">
                <label for="participant-limit"><?php echo e(__('ui.max_participants')); ?></label>
                <input type="number" id="participant-limit" name="participantLimit" placeholder="<?php echo e(__('ui.max_participants')); ?>" />
            </div>
            </div>

            
            <div class="form-actions">
                <button type="button" class="btn secondary-btn" id="cancel-btn"><?php echo e(__('ui.cancel')); ?></button>
                <button type="submit" class="btn primary-btn"><?php echo e(__('ui.save_event')); ?></button>
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
                    <h2><?php echo e(__('ui.settings')); ?></h2>
                    <p class="modal-subtitle"><?php echo e(__('ui.admin')); ?></p>
                </div>
            </div>
            <button class="modal-close-btn" id="close-settings-modal-btn" aria-label="Luk">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <!-- Settings Tabs Navigation -->
        <div class="settings-tabs-nav">
            <button class="settings-tab-btn active" data-tab="password">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2.5 9.5L2 19a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2l-.5-9.5M6.5 6a4 4 0 0 1 4-4h3a4 4 0 0 1 4 4m-9 0v-.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v.5"></path>
                </svg>
                <?php echo e(__('ui.change_password')); ?>

            </button>
            <button class="settings-tab-btn" data-tab="notifications">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <?php echo e(__('ui.notifications')); ?>

            </button>
        </div>
        
        <!-- Tab Content: Password -->
        <div class="settings-tab-content active" data-tab-content="password">
            <form id="change-password-form" class="modal-form" method="POST" action="<?php echo e(route('user.change-password')); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="form-section">
                    <h3 class="section-title"><?php echo e(__('ui.change_password')); ?></h3>
                    <div class="form-row">
                        <label for="current-password"><?php echo e(__('ui.current_password')); ?></label>
                        <input type="password" id="current-password" name="current_password" required placeholder="<?php echo e(__('ui.current_password')); ?>">
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-row">
                        <label for="new-password"><?php echo e(__('ui.new_password')); ?></label>
                        <input type="password" id="new-password" name="new_password" required placeholder="<?php echo e(__('ui.new_password')); ?>">
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-row">
                        <label for="new-password-confirm"><?php echo e(__('ui.confirm_new_password')); ?></label>
                        <input type="password" id="new-password-confirm" name="new_password_confirmation" required placeholder="<?php echo e(__('ui.confirm_new_password')); ?>">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn secondary-btn" id="cancel-settings-btn"><?php echo e(__('ui.cancel')); ?></button>
                    <button type="submit" class="btn primary-btn"><?php echo e(__('ui.change_password')); ?></button>
                </div>
            </form>
        </div>
        
        <!-- Tab Content: Notifications -->
        <div class="settings-tab-content" data-tab-content="notifications">
            <div class="modal-form notifications-settings">
                <div class="notifications-intro">
                    <p><?php echo e(__('ui.notifications_intro')); ?></p>
                </div>
                
                <div class="notifications-table">
                    <div class="notifications-header">
                        <div class="notifications-col notifications-col-notification"><?php echo e(__('ui.notification_name')); ?></div>
                        <div class="notifications-col notifications-col-system"><?php echo e(__('ui.system')); ?></div>
                        <div class="notifications-col notifications-col-email"><?php echo e(__('ui.email')); ?></div>
                    </div>
                    
                    <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Ny begivenhed</p>
                            <p class="notifications-description">Få besked når en ny begivenhed er tilføjet</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-new" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-new" data-channel="email" checked>
                        </div>
                    </div>
                    
                    <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Nye vagter</p>
                            <p class="notifications-description">Få besked når du får nye vagter til en begivenhed</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-shifts" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-shifts" data-channel="email" checked>
                        </div>
                    </div>
                    
                    <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Deltager forlader begivenhed</p>
                            <p class="notifications-description">Få besked når en deltager forlader en begivenhed</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-leave-participant" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-leave-participant" data-channel="email" checked>
                        </div>
                    </div>
                    
                    <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Medarbejder forlader begivenhed</p>
                            <p class="notifications-description">Få besked når en medarbejder forlader en begivenhed</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-leave-employee" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-leave-employee" data-channel="email" checked>
                        </div>
                    </div>

                                        <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Begivenhed slettes</p>
                            <p class="notifications-description">Få besked når en begivenhed slettes</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-delete" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="event-delete" data-channel="email" checked>
                        </div>
                    </div>
                    
                    <div class="notifications-row">
                        <div class="notifications-col notifications-col-notification">
                            <p class="notifications-title">Gruppe invitation</p>
                            <p class="notifications-description">Få besked når du bliver inviteret til en gruppe</p>
                        </div>
                        <div class="notifications-col notifications-col-system">
                            <input type="checkbox" class="notification-checkbox" data-notification="group-invitation" data-channel="system" checked>
                        </div>
                        <div class="notifications-col notifications-col-email">
                            <input type="checkbox" class="notification-checkbox" data-notification="group-invitation" data-channel="email" checked>
                        </div>
                    </div>
                </div>
                
                <div class="notifications-footer">
                    <button type="button" class="btn secondary-btn" id="cancel-notifications-btn"><?php echo e(__('ui.cancel')); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Header/Nav Menu -->
<div id="mnav" class="mnav" aria-hidden="true">
    <div class="mnav__backdrop" id="mnav-backdrop"></div>
    <aside class="mnav__panel" role="dialog" aria-modal="true" aria-labelledby="mnav-title">
        <header class="mnav__header">
            <div class="mnav__brand" id="mnav-title" aria-label="TaskM8">
                <a href="/dashboard" class="logo-link" aria-label="Gå til forside">
                    <img src="<?php echo e(asset('TaskM8-Logo.png')); ?>" alt="TaskM8" class="logo-img logo-img-dark" />
                    <img src="<?php echo e(asset('TaskM8-Logo-Dark.png')); ?>" alt="TaskM8" class="logo-img logo-img-light" />
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
                <li class="mnav__item"><a class="mnav__link <?php echo e($currentPage == 'dashboard' ? 'is-active' : ''); ?>" href="/dashboard"><?php echo e(__('ui.dashboard')); ?></a></li>
                <?php if(Auth::check()): ?>
                <li class="mnav__item"><a class="mnav__link <?php echo e($currentPage == 'events' ? 'is-active' : ''); ?>" href="/events"><?php echo e(__('ui.events')); ?></a></li>
                <li class="mnav__item"><a class="mnav__link <?php echo e($currentPage == 'previousEvents' ? 'is-active' : ''); ?>" href="/previousEvents"><?php echo e(__('ui.previous_events')); ?></a></li>
                <li class="mnav__item"><a class="mnav__link <?php echo e($currentPage == 'groups/overview' ? 'is-active' : ''); ?>" href="/groups/overview"><?php echo e(__('ui.groups')); ?></a></li>

                <?php endif; ?>
            </ul>
        </nav>

        <div class="mnav__section mnav__section--locale">
            <button class="mnav__user mnav__language" id="mnav-language" aria-expanded="false" aria-controls="mnav-language-menu">
                <span class="mnav__avatar mnav__avatar--language" aria-hidden="true">🌐</span>
                <span class="mnav__username"><?php echo e(__('ui.language')); ?></span>
                <svg class="mnav__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6,9 12,15 18,9"/></svg>
            </button>
            <ul class="mnav__submenu mnav__submenu--locale" id="mnav-language-menu" hidden>
                <?php $__currentLoopData = $supportedLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $localeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <button type="button" class="mnav__action mnav__action--locale <?php echo e($currentLocale === $locale ? 'is-active' : ''); ?>" data-locale="<?php echo e($locale); ?>" aria-pressed="<?php echo e($currentLocale === $locale ? 'true' : 'false'); ?>">
                            <span class="mnav__locale-row-icon" aria-hidden="true"><?php echo e($localeData['icon']); ?></span>
                            <span class="mnav__locale-row-label"><?php echo e($localeData['label']); ?></span>
                            <span class="mnav__locale-row-code"><?php echo e($localeData['code']); ?></span>
                        </button>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        
        <?php if(Auth::check()): ?>
        <div class="mnav__section">
            <button class="mnav__user" id="mnav-user" aria-expanded="false" aria-controls="mnav-user-menu">
                <span class="mnav__avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                <span class="mnav__username"><?php echo e(__('ui.welcome', ['name' => Auth::user()->name])); ?></span>
                <svg class="mnav__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6,9 12,15 18,9"/></svg>
            </button>
            <ul class="mnav__submenu" id="mnav-user-menu" hidden>
                <li><button class="mnav__action mnav__action--primary" id="mnav-create">Ny begivenhed</button></li>
                <li><button class="mnav__action" id="mnav-settings"><?php echo e(__('ui.settings')); ?></button></li>
                <li>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="mnav__action mnav__action--danger"><?php echo e(__('ui.logout')); ?></button>
                    </form>
                </li>
            </ul>
        </div>
        <?php else: ?>
        <div class="mnav__section">
            <a href="<?php echo e(route('login')); ?>" class="mnav__action mnav__action--primary"><?php echo e(__('ui.login')); ?></a>
        </div>
        <?php endif; ?>
    </aside>
</div>

<?php if(session('success')): ?>
    <div id="event-success-message" style="display:none;">
        <?php if(str_contains(session('success'), 'adgangskode')): ?>
            <div class="modal-success">
                <div class="checkmark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 12l2.5 2.5L16 9"/>
                    </svg>
                </div>
                <h3>Adgangskode ændret!</h3>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php else: ?>
            <?php echo e(session('success')); ?>

        <?php endif; ?>
    </div>
<?php endif; ?>

<script src="<?php echo e(asset('js/theme-toggle.js')); ?>"></script>
<script src="<?php echo e(asset('js/header.js')); ?>"></script><?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/partials/header.blade.php ENDPATH**/ ?>
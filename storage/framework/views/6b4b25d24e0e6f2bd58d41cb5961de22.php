<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 Forside</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/event.css')); ?>">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content-full">
        
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value"><?php echo e($pendingEventsCount); ?></span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value"><?php echo e($participatedEventsCount); ?></span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Tidligere Inviterede: </span>
                    <span class="stat-value"><?php echo e($previousInviteesCount); ?></span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493 M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07 M15 19.128v.106A12.318 12.318 0 0 1 8.624 21 c-2.331 0-4.512-.645-6.374-1.766l-.001-.109 a6.375 6.375 0 0 1 11.964-3.07 M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25 a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
        </section>
        <section class="upcoming-events">
            <h2>Kommende Begivenheder</h2>
            <div class="event-list">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo e($event->eventName); ?></h3>
                        </div>
                        <p class="event-description"><?php echo e($event->description); ?></p>
                        <div class="event-actions">
                            <a href="/events/<?php echo e($event->id); ?>" class="btn primary-btn">Se detaljer</a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php
                                    $isOwner = isset($event->ownerId) && $event->ownerId === auth()->id();
                                    $myParticipation = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->first();
                                    $rsvpStatus = $myParticipation->status ?? null; // accepted | declined | null
                                    $isParticipant = $rsvpStatus === 'accepted';
                                    $hasResponded = in_array($rsvpStatus, ['accepted','declined']);
                                ?>
                                <?php if(!$isOwner): ?>
                                    <?php
                                        $isFull = !empty($event->participantLimit) && (\App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count() >= $event->participantLimit) && !$isParticipant;
                                    ?>
                                    <div class="rsvp-status <?php echo e($rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending')); ?>">
                                        <?php if($rsvpStatus === 'accepted'): ?>
                                            <span class="status-dot"></span> Deltager
                                        <?php elseif($rsvpStatus === 'declined'): ?>
                                            <span class="status-dot"></span> Deltager ikke
                                        <?php else: ?>
                                            <span class="status-dot"></span> Afventer svar
                                        <?php endif; ?>
                                    </div>
                                    <?php if($rsvpStatus === 'accepted'): ?>
                                    <button type="button" class="bin-button" aria-label="Forlad begivenhed" onclick="document.getElementById('leave-modal-<?php echo e($event->id); ?>').style.display='flex'">
                                        <svg class="bin-top" viewBox="0 0 39 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line>
                                            <line x1="12" y1="1.5" x2="26.0357" y2="1.5" stroke="white" stroke-width="3"></line>
                                        </svg>
                                        <svg class="bin-bottom" viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <mask id="path-1-inside-1_8_19" fill="white">
                                                <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                                            </mask>
                                            <path d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z" fill="white" mask="url(#path-1-inside-1_8_19)"></path>
                                            <path d="M12 6L12 29" stroke="white" stroke-width="4"></path>
                                            <path d="M21 6V29" stroke="white" stroke-width="4"></path>
                                        </svg>
                                    </button>
                                    <?php endif; ?>
                                    <div class="rsvp-menu" id="rsvp-menu-<?php echo e($event->id); ?>">
                                        <button type="button" class="rsvp-menu-trigger" onclick="toggleRsvpDropdown('rsvp-menu-<?php echo e($event->id); ?>')">
                                            <?php echo e($hasResponded ? 'Skift svar' : 'Svar'); ?>

                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="caret"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                        <div class="rsvp-menu-list" role="menu">
                                            <form action="<?php echo e(route('events.rsvp', ['eventId' => $event->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="accepted" />
                                                <button type="submit" class="rsvp-menu-item accepted" <?php echo e($isFull ? 'disabled' : ''); ?>>
                                                    <span class="dot"></span> Deltag
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('events.rsvp', ['eventId' => $event->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="declined" />
                                                <button type="submit" class="rsvp-menu-item declined">
                                                    <span class="dot"></span> Deltager ikke
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Leave Confirmation Modal per card -->
                                    <div id="leave-modal-<?php echo e($event->id); ?>" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="leave-confirm-title-<?php echo e($event->id); ?>" style="display:none;">
                                        <div class="confirm-modal-content">
                                            <div class="confirm-modal-body">
                                                <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                                                    <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                                                </svg>
                                                <h2 id="leave-confirm-title-<?php echo e($event->id); ?>" class="confirm-title">Er du sikker?</h2>
                                                <p class="confirm-text">Vil du forlade denne begivenhed? Dette kan ikke fortrydes.</p>
                                            </div>
                                            <div class="confirm-actions">
                                                <button type="button" class="confirm-btn cancel" onclick="document.getElementById('leave-modal-<?php echo e($event->id); ?>').style.display='none'">Annuller</button>
                                                <form action="<?php echo e(route('events.decline', ['eventId' => $event->id])); ?>" method="POST" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="confirm-btn danger">Slet</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>Ingen begivenheder fundet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="<?php echo e(asset('build/assets/app-DNxiirP_.js')); ?>" type="module"></script>
</body>
</html>
<script>
    function toggleRsvpDropdown(id) {
        var m = document.getElementById(id);
        if (!m) return;
        var isOpen = m.classList.contains('open');
        document.querySelectorAll('.rsvp-menu.open').forEach(function(el){ el.classList.remove('open'); });
        if (!isOpen) m.classList.add('open');
    }
    document.addEventListener('click', function(e){
        var openMenu = document.querySelector('.rsvp-menu.open');
        if (!openMenu) return;
        if (!openMenu.contains(e.target)) {
            openMenu.classList.remove('open');
        }
    });
</script><?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/dashboard.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 Forside</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content-full">
        <header class="content-header">
        </header>
        
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value">12</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value">4</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Tidligere Inviteret: </span>
                    <span class="stat-value">0</span>
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
                                    $isParticipant = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->where('status', 'accepted')->exists();
                                ?>
                                <?php if(!$isOwner): ?>
                                    <?php
                                        $isFull = !empty($event->participantLimit) && (\App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count() >= $event->participantLimit) && !$isParticipant;
                                    ?>
                                    <form action="<?php echo e(route('events.rsvp', ['eventId' => $event->id])); ?>" method="POST" class="rsvp-actions rsvp-actions-inline" aria-label="Deltagelsesvalg">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" name="status" value="accepted" class="btn-rsvp accept <?php echo e($isParticipant ? 'active' : ''); ?>" <?php echo e($isFull ? 'disabled' : ''); ?>>Deltag</button>
                                        <button type="submit" name="status" value="declined" class="btn-rsvp decline <?php echo e(!$isParticipant ? 'active' : ''); ?>">Deltager ikke</button>
                                    </form>
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
</html> <?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/dashboard.blade.php ENDPATH**/ ?>
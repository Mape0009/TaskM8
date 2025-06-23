<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(isset($event) ? $event->title : 'Event Details'); ?> | TaskM8</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/event.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/modal.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <main>
        <div class="event-details-container">
            <div class="event-details-header">
                <span class="event-details-icon">
                    <svg width="32" height="32" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </span>
                <h1 class="event-details-title"><?php echo e($event->title ?? 'Event Title'); ?></h1>
            </div>
            <ul class="event-details-list">
                <li><span class="event-details-label">Start:</span> <span class="event-details-value"><?php echo e($event->start_time ?? '-'); ?></span></li>
                <li><span class="event-details-label">Slut:</span> <span class="event-details-value"><?php echo e($event->end_time ?? '-'); ?></span></li>
                <li><span class="event-details-label">Lokation:</span> <span class="event-details-value"><?php echo e($event->location ?? '-'); ?></span></li>
            </ul>
            <div class="event-details-description">
                <?php echo e($event->description ?? 'No description provided.'); ?>

            </div>
            <a href="<?php echo e(url('/events')); ?>" class="back-btn">&larr; Tilbage til begivenheder</a>
        </div>
    </main>
</body>
</html> <?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/event.blade.php ENDPATH**/ ?>
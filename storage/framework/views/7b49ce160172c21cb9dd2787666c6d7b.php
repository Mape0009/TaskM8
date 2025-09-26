<!DOCTYPE html>
<html lang="da">
<head>
    <?php
        $pageTitle = 'Begivenheder | TaskM8';
        $metaDescription = 'Se dine begivenheder og få hurtigt overblik i TaskM8.';
    ?>
    <?php echo $__env->make('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content-full">

        <section class="event-listing">
            <h2>Mine begivenheder</h2>
            <div class="event-list">
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo e($event->eventName); ?></h3>
                            <div class="event-kebab rsvp-menu" id="event-menu-<?php echo e($event->id); ?>">
                                <button class="kebab-btn rsvp-menu-trigger" onclick="toggleRsvpDropdown('event-menu-<?php echo e($event->id); ?>')" aria-label="Åbn menu">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="17" r="1"></circle></svg>
                                </button>
                                <div class="rsvp-menu-list" role="menu" style="right:0; min-width: 180px;">
                                    <a class="rsvp-menu-item" href="<?php echo e(route('events.tasks.create.form', ['eventId' => $event->id])); ?>">Opret opgave</a>
                                    <a class="rsvp-menu-item" href="<?php echo e(route('events.tasks.index', ['eventId' => $event->id])); ?>">Opgaver</a>
                                </div>
                            </div>
                        </div>
                        <p class="event-description"><?php echo e(Str::limit($event->description, 25)); ?></p>
                        <div class="event-actions">
                            <a href="/events/<?php echo e($event->id); ?>" class="btn primary-btn">Se detaljer</a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(isset($event->ownerId) && $event->ownerId === auth()->id()): ?>
                                    <a href="/events/<?php echo e($event->id); ?>/edit" class="btn secondary-btn">Rediger</a>
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
</script>
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html> <?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/events.blade.php ENDPATH**/ ?>
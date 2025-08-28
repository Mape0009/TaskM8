<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($event->eventName ?? 'Event Details'); ?> | TaskM8</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/event.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/invitation.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'events'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <main class="event-hero-bg">
        <section class="event-hero-section">
            <div class="event-hero-icon">
                <svg width="54" height="54" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h1 class="event-hero-title"><?php echo e($event->eventName ?? 'Event Title'); ?></h1>
            <div class="event-hero-date">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                <span><?php echo e($event->startDate ? \Carbon\Carbon::parse($event->startDate)->format('d-m-Y H:i') : '-'); ?> - <?php echo e($event->endDate ? \Carbon\Carbon::parse($event->endDate)->format('d-m-Y H:i') : '-'); ?></span>
            </div>
        </section>
        <section class="event-details-card">
            <div class="event-card-actions-top">
                <a href="<?php echo e(url('/events')); ?>" class="back-btn" aria-label="Tilbage til begivenheder">Tilbage</a>
                <?php if(auth()->guard()->check()): ?>
                <?php if(isset($event->ownerId) && $event->ownerId === auth()->id()): ?>
                <button class="btn invite-btn" onclick="openInviteModal(<?php echo e($event->id); ?>, '<?php echo e($event->eventName); ?>')">
                    Inviter til begivenhed
                </button>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <ul class="event-details-list">
                <li><span class="event-details-label">Lokation:</span> <span class="event-details-value"><?php echo e($event->location ?? '-'); ?></span></li>
                <?php
                    $acceptedCount = \App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count();
                ?>
                <li>
                    <span class="event-details-label">Deltagere:</span>
                    <span class="event-details-value">
                        <?php echo e($acceptedCount); ?>

                        <?php if(!empty($event->participantLimit)): ?>
                            / <?php echo e($event->participantLimit); ?>

                        <?php endif; ?>
                    </span>
                </li>
            </ul>
            <div class="event-details-description">
                <?php echo e($event->description ?? 'Der er ingen beskrivelse af denne begivenhed.'); ?>

            </div>
            <?php if(auth()->guard()->check()): ?>
            <?php
                $isOwner = isset($event->ownerId) && $event->ownerId === auth()->id();
                $isAccepted = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->where('status', 'accepted')->exists();
            ?>
            <?php if(!$isOwner): ?>
            <style>
                .rsvp-inline { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
                .rsvp-inline label { display:flex; align-items:center; gap:0.45rem; cursor:pointer; }
                .rsvp-inline input[type='radio'] { accent-color:#6366f1; }
                @media (max-width:600px){ .rsvp-inline{ width:100%; justify-content:flex-start; } }
            </style>
            <form action="<?php echo e(route('events.rsvp', ['eventId' => $event->id])); ?>" method="POST" class="rsvp-inline" aria-label="Deltagelsesvalg">
                <?php echo csrf_field(); ?>
                <label>
                    <input type="radio" name="status" value="accepted" <?php echo e($isAccepted ? 'checked' : ''); ?> onchange="this.form.submit()">
                    <span>Deltager</span>
                </label>
                <label>
                    <input type="radio" name="status" value="declined" <?php echo e($isAccepted ? '' : 'checked'); ?> onchange="this.form.submit()">
                    <span>Deltager ikke</span>
                </label>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>

    <!-- Invitation Modal -->
    <div id="invite-modal" class="invite-modal">
        <div class="invite-modal-content">
            <div class="invite-modal-header">
                <span class="modal-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </span>
                <h2>Inviter til begivenhed</h2>
                <button class="modal-close-btn" onclick="closeInviteModal()" aria-label="Close">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="invite-modal-form">
                <form action="<?php echo e(route('events.invite', $event->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="eventIdInvite" value="<?php echo e($event->id); ?>">
                    <div class="invite-section">
                        <h3>Inviter via email</h3>
                        <div class="email-input-container">
                            <div class="email-input-group">
                                <input type="email" id="email-input" placeholder="Indtast email adresse" class="email-input">
                                <button type="button" onclick="addEmail()" class="add-email-btn">Tilføj</button>
                            </div>
                        </div>
                        <div id="email-list" class="email-list"></div>
                    </div>
                    
                    <div class="invite-section">
                        <h3>Tidligere inviterede</h3>
                        <div class="search-container">
                            <input type="text" id="search-invitees" placeholder="Søg efter tidligere inviterede..." class="search-input">
                        </div>
                        <div id="invitees-list" class="invitees-list"></div>
                    </div>
                    
                    <button type="button" onclick="sendInvitations()" class="btn primary-btn">Send invitationer</button>
                </form>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('js/invitation.js')); ?>"></script>

    <script>
        let currentEventId = null;
        let addedEmails = [];

        function openInviteModal(eventId, eventName) {
            currentEventId = eventId;
            document.getElementById('invite-modal').style.display = 'flex';
            loadPreviousInvitees();
        }

        function closeInviteModal() {
            document.getElementById('invite-modal').style.display = 'none';
            document.getElementById('email-input').value = '';
            document.getElementById('search-invitees').value = '';
            addedEmails = [];
            updateEmailList();
        }

        function addEmail() {
            const emailInput = document.getElementById('email-input');
            const email = emailInput.value.trim();
            if (email && isValidEmail(email) && !addedEmails.includes(email)) {
                addedEmails.push(email);
                emailInput.value = '';
                updateEmailList();
            }
        }

        function removeEmail(email) {
            addedEmails = addedEmails.filter(e => e !== email);
            updateEmailList();
        }

        function updateEmailList() {
            const emailList = document.getElementById('email-list');
            emailList.innerHTML = '';
            addedEmails.forEach(email => {
                const emailTag = document.createElement('div');
                emailTag.className = 'email-tag';
                emailTag.innerHTML = `
                    <span>${email}</span>
                    <button type="button" onclick="removeEmail('${email}')" class="remove-email-btn">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;
                emailList.appendChild(emailTag);
            });
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        async function loadPreviousInvitees() {
            const inviteesList = document.getElementById('invitees-list');
            inviteesList.innerHTML = '<div class="invitee-item">Henter inviterede...</div>';
            try {
                const res = await fetch('<?php echo e(route('events.invitees', $event->id)); ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed');
                const all = await res.json();
                const items = (all || []).slice(0, 3);
                if (!items.length) {
                    inviteesList.innerHTML = '<div class="invitee-item">Ingen tidligere inviterede</div>';
                    return;
                }
                inviteesList.innerHTML = '';
                items.forEach(i => {
                    const initials = (i.name || i.email || '?').trim().charAt(0).toUpperCase();
                    const item = document.createElement('div');
                    item.className = 'invitee-item';
                    item.innerHTML = `
                        <div class="invitee-info">
                            <div class="invitee-avatar">${initials}</div>
                            <div class="invitee-details">
                                <span class="invitee-name">${i.name || 'Ukendt'}</span>
                                <span class="invitee-email">${i.email}</span>
                            </div>
                        </div>
                        <button type="button" class="invitee-select-btn">Vælg</button>
                    `;
                    item.querySelector('.invitee-select-btn').addEventListener('click', () => selectInvitee(i.email));
                    inviteesList.appendChild(item);
                });
            } catch (e) {
                inviteesList.innerHTML = '<div class="invitee-item">Kunne ikke hente inviterede.</div>';
            }
        }

        function selectInvitee(email) {
            if (!addedEmails.includes(email)) {
                addedEmails.push(email);
                updateEmailList();
            }
        }

        function sendInvitations() {
            const form = document.querySelector('#invite-modal form');

            form.querySelectorAll('input[name="emailsInvite[]"]').forEach(el => el.remove());

            // Tilføj emails som hidden inputs
            addedEmails.forEach(email => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'emailsInvite[]';
                hiddenInput.value = email;
                form.appendChild(hiddenInput);
            });

            form.submit();
        }

        document.getElementById('email-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEmail();
            }
        });

        document.getElementById('invite-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInviteModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/event.blade.php ENDPATH**/ ?>
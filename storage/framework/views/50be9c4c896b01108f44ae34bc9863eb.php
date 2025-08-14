<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($event->eventName ?? 'Event Details'); ?> | TaskM8</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/event.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/modal.css')); ?>">
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
            <ul class="event-details-list">
                <li><span class="event-details-label">Lokation:</span> <span class="event-details-value"><?php echo e($event->location ?? '-'); ?></span></li>
            </ul>
            <div class="event-details-description">
                <?php echo e($event->description ?? 'Der er ingen beskrivelse af denne begivenhed.'); ?>

            </div>
            <div class="event-actions-details">
                <button class="btn invite-btn" onclick="openInviteModal(<?php echo e($event->id); ?>, '<?php echo e($event->eventName); ?>')">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Inviter til begivenhed
                </button>
                <a href="<?php echo e(url('/events')); ?>" class="back-btn">&larr; Tilbage til begivenheder</a>
            </div>
        </section>
    </main>

    <!-- Invitation Modal -->
    <div id="invite-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
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
            <div class="modal-form">
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
                    <div id="invitees-list" class="invitees-list">
                        <!-- Tidligere inviterede vil blive indlæst her -->
                    </div>
                </div>
                
                <button type="button" onclick="sendInvitations()" class="btn primary-btn">Send invitationer</button>
            </div>
        </div>
    </div>

    <script>
        let currentEventId = null;
        let currentEventName = '';
        let addedEmails = [];

        function openInviteModal(eventId, eventName) {
            currentEventId = eventId;
            currentEventName = eventName;
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
                    <button onclick="removeEmail('${email}')" class="remove-email-btn">
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

        function loadPreviousInvitees() {
            // Her ville man normalt hente tidligere inviterede 
            const inviteesList = document.getElementById('invitees-list');
            inviteesList.innerHTML = `
                <div class="invitee-item">
                    <div class="invitee-info">
                        <div class="invitee-avatar">J</div>
                        <div class="invitee-details">
                            <span class="invitee-name">John Doe</span>
                            <span class="invitee-email">john@example.com</span>
                        </div>
                    </div>
                    <button class="invitee-select-btn" onclick="selectInvitee('john@example.com', 'John Doe')">Vælg</button>
                </div>
                <div class="invitee-item">
                    <div class="invitee-info">
                        <div class="invitee-avatar">J</div>
                        <div class="invitee-details">
                            <span class="invitee-name">Jane Smith</span>
                            <span class="invitee-email">jane@example.com</span>
                        </div>
                    </div>
                    <button class="invitee-select-btn" onclick="selectInvitee('jane@example.com', 'Jane Smith')">Vælg</button>
                </div>
            `;
        }

        function selectInvitee(email, name) {
            if (!addedEmails.includes(email)) {
                addedEmails.push(email);
                updateEmailList();
            }
        }

        function sendInvitations() {
            // Her ville man normalt sende invitationerne til serveren
            console.log('Sending invitations for event:', currentEventId);
            console.log('Emails to invite:', addedEmails);
            
            // Vis success besked
            alert('Invitationer er blevet sendt!');
            closeInviteModal();
        }

        // Event listeners
        document.getElementById('email-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                addEmail();
            }
        });

        // Luk modal når man klikker udenfor
        document.getElementById('invite-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInviteModal();
            }
        });
    </script>
</body>
</html> <?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/event.blade.php ENDPATH**/ ?>
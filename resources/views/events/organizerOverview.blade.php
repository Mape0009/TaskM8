<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Uddel roller | TaskM8';
        $metaDescription = 'Administrer deltagere og tildel roller for begivenheden.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uddel roller</title>
    <link rel="stylesheet" href="{{ asset('css/organizerOverview.css') }}">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])
    <main class="roles-wrapper">
        <div class="roles-header">
            <h1 class="roles-title">Uddel roller</h1>
            <div class="search">
                <input id="participant-search" type="text" placeholder="Søg efter deltager (navn eller email)">
            </div>
            <div class="page-actions">
                <a href="/events/{{ $eventId }}" class="btn ghost">Tilbage til begivenhed</a>
            </div>
        </div>

        @php
            $currentParticipant = $participants->firstWhere('userId', $currentUser->id);
            $currentRole = $currentParticipant?->eventRole ?? 'participant';
            $canDelete = \App\Http\RolePermissions\Permissions::hasPermission($currentRole, 'delete-participant');
            $canManage = function($targetRole) use ($currentRole) {
                $map = [
                    'coOwner' => 'manage-coOwners',
                    'taskManager' => 'manage-taskManagers',
                    'taskWorker' => 'manage-taskWorkers',
                    'participant' => 'manage-participants',
                ];
                return isset($map[$targetRole]) && \App\Http\RolePermissions\Permissions::hasPermission($currentRole, $map[$targetRole]);
            };
        @endphp

        @php
            $visibleParticipants = $participants->filter(function($p){ return in_array($p->status, ['accepted','pending']); });
        @endphp
        <section class="participants-list" id="participants">
            @forelse ($visibleParticipants as $participant)
                @php
                    $initials = strtoupper(substr($participant->user->name ?? $participant->user->email, 0, 1));
                    $isOwnerTarget = ($participant->eventRole === $eventRole::owner->name);
                    $isTargetCoOwner = ($participant->eventRole === $eventRole::coOwner->name);
                    $isCurrentCoOwner = ($currentRole === $eventRole::coOwner->name);
                    $isSelf = ($participant->userId === $currentUser->id);
                @endphp
                <div class="participant-card" data-name="{{ strtolower($participant->user->name ?? '') }}" data-email="{{ strtolower($participant->user->email ?? '') }}">
                    <div class="participant-info">
                        <div class="avatar">{{ $initials }}</div>
                        <div class="name-email">
                            <div class="name">{{ $participant->user->name ?? 'Ukendt' }}</div>
                            <div class="email">{{ $participant->user->email }}</div>
                        </div>
                    </div>
                    <div class="actions">
                        @php $roleLabelMap = [
                            $eventRole::owner->name => 'Ejer',
                            $eventRole::coOwner->name => 'Medejer',
                            $eventRole::taskManager->name => 'Opgaveansvarlig',
                            $eventRole::taskWorker->name => 'Opgavemedlem',
                            $eventRole::participant->name => 'Deltager',
                        ]; @endphp
                        <form action="{{ route('events.roleUpdate') }}" method="POST" class="role-form">
                            @csrf
                            <input type="hidden" name="participantId" value="{{ $participant->id }}">
                            <select class="role-select" name="eventRole" @disabled($isOwnerTarget || $isSelf)>
                                @if($isOwnerTarget)
                                    <option value="owner" selected>Ejer</option>
                                @else
                                    @php
                                        $canOrSelf = function($key) use ($canManage, $participant, $eventRole, $isSelf) {
                                            $currentIsKey = match($key){
                                                'coOwner' => $participant->eventRole === $eventRole::coOwner->name,
                                                'taskManager' => $participant->eventRole === $eventRole::taskManager->name,
                                                'taskWorker' => $participant->eventRole === $eventRole::taskWorker->name,
                                                'participant' => $participant->eventRole === $eventRole::participant->name,
                                                default => false,
                                            };
                                            return ($isSelf && $currentIsKey) || $canManage($key);
                                        };
                                    @endphp
                                    @if($canOrSelf('coOwner'))
                                        <option value="coOwner" {{ $participant->eventRole === $eventRole::coOwner->name ? 'selected' : '' }}>Med-ejer</option>
                                    @endif
                                    @if($canOrSelf('taskManager'))
                                        <option value="taskManager" {{ $participant->eventRole === $eventRole::taskManager->name ? 'selected' : '' }}>Opgaveansvarlig</option>
                                    @endif
                                    @if($canOrSelf('taskWorker'))
                                        <option value="taskWorker" {{ $participant->eventRole === $eventRole::taskWorker->name ? 'selected' : '' }}>Opgavemedlem</option>
                                    @endif
                                    @if($canOrSelf('participant'))
                                        <option value="participant" {{ $participant->eventRole === $eventRole::participant->name ? 'selected' : '' }}>Deltager</option>
                                    @endif
                                @endif
                            </select>
                            @if(!($isOwnerTarget || $isSelf))
                            <button type="submit" class="btn primary">Gem</button>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <p>Ingen deltagere fundet.</p>
            @endforelse
        </section>
    </main>
    <div id="coowner-confirm-modal" class="confirm-modal coowner-confirm" role="dialog" aria-modal="true" aria-labelledby="coowner-confirm-title" style="display:none;">
        <div class="confirm-modal-content">
            <div class="confirm-modal-body">
                <svg fill="currentColor" viewBox="0 0 24 24" class="confirm-icon" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 1.75c-.41 0-.82.1-1.19.3L4.5 5.1a2.25 2.25 0 0 0-1.19 1.98v4.92c0 5.16 3.55 9.94 8.46 11.25.16.04.33.04.47 0 4.91-1.31 8.46-6.09 8.46-11.25V7.08c0-.82-.45-1.58-1.19-1.98l-6.31-3.05c-.37-.2-.78-.3-1.2-.3Zm0 6.25a.75.75 0 0 1 .75.75v3.44l2.3 2.3a.75.75 0 0 1-1.06 1.06l-2.53-2.53a.75.75 0 0 1-.22-.53V8.75c0-.41.34-.75.76-.75Z"/>
                </svg>
                <h2 id="coowner-confirm-title" class="confirm-title">Bekræft Medejer</h2>
                <p class="confirm-text">Du er ved at give Medejer-rollen. Medejere har udvidede rettigheder på begivenheden. Vil du fortsætte?</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn cancel" id="coowner-cancel-btn">Annuller</button>
                <button type="button" class="confirm-btn success" id="coowner-confirm-btn">Bekræft</button>
            </div>
        </div>
    </div>
    @include('partials.footer')
    <script src="{{ asset('js/organizerOverview.js') }}"></script>
</body>
</html>


<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Opret opgave | TaskM8';
        $metaDescription = 'Opret og tildel opgaver til begivenheder i TaskM8.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@include('partials.header', ['currentPage' => 'tasks'])
<div class="edit-container">
    <div class="edit-header" style="display: flex; align-items: center; justify-content: space-between;">
        <h1 class="edit-title">Lav ny opgave</h1>
        @if(isset($event))
        <p class="muted" style="margin-left: 0.5rem;">Til begivenhed: <strong>{{ $event->eventName }}</strong></p>
        @endif
        <a href="{{ url()->previous() }}" class="btn white-btn">Tilbage</a>
    </div>

    <div class="task-form-wrapper">
        <form action="{{ isset($event) ? route('events.tasks.create', ['eventId' => $event->id]) : route('task.create') }}" method="POST" class="edit-form task-form" novalidate>
            @csrf

            <div class="form-row">
                <label for="taskName">Opgave Navn:</label>
                <input type="text" id="taskName" name="taskName" placeholder="Opgave Navn">
            </div>

            <div class="form-row">
                <label for="description">Beskrivelse:</label>
                <div style="position: relative;">
                    <textarea id="description" name="description" placeholder="Opgave Beskrivelse" maxlength="500" rows="4"></textarea>
                    <span id="desc-counter" style="position: absolute; bottom: 6px; right: 8px; font-size: 12px; color: var(--text-muted, #6b7280);">0/500</span>
                </div>
            </div>

            

            <div class="form-row">
                <label for="startDate">Start Tidspunkt:</label>
                <input type="datetime-local" id="startDate" name="startDate" required
                    @if(isset($event) && $event->startDate)
                        min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\TH:i') }}"
                    @endif
                    @if(isset($event) && $event->endDate)
                        max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\TH:i') }}"
                    @endif
                >
            </div>

            <div class="form-row">
                <label for="endDate">Slut Tidspunkt:</label>
                <input type="datetime-local" id="endDate" name="endDate" required
                    @if(isset($event) && $event->startDate)
                        min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\TH:i') }}"
                    @endif
                    @if(isset($event) && $event->endDate)
                        max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\TH:i') }}"
                    @endif
                >
            </div>

            <div class="form-row">
                <label for="user_search">Tildel Brugere:</label>
                <div class="assignee-picker" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <input id="user_search" type="text" placeholder="Søg efter brugere..." style="width: 100%;">
                    <div id="user_list" class="checkbox-group" style="max-height: 220px; overflow-y: auto; border: 1.5px solid var(--color-border); border-radius: 8px; padding: 0.5rem; background: var(--color-bg-elevated); display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.5rem;">
                        @forelse ($users as $user)
                            <label class="assignee-item" data-name="{{ Str::lower($user->name) }}" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 500;">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }} />
                                <span class="assignee-avatar" style="width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#e5e7eb;">{{ strtoupper(Str::substr($user->name,0,1)) }}</span>
                                <span>{{ $user->name }}</span>
                            </label>
                        @empty
                            <p style="font-size: 0.9rem; opacity: .8;">Ingen brugere fundet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary-btn">Lav Opgave</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/task-create.js') }}"></script>
</body>
</html>

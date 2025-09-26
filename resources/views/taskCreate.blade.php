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
        <h2 class="edit-title">Lav ny opgave</h2>
        <a href="{{ url()->previous() }}" class="btn white-btn">← Back</a>
    </div>

    <div class="task-form-wrapper">
        <form action="{{ isset($event) ? route('events.tasks.create', ['eventId' => $event->id]) : route('task.create') }}" method="POST" class="edit-form">
            @csrf

            <div class="form-row">
                <label for="taskName">Opgave Navn:</label>
                <input type="text" id="taskName" name="taskName" placeholder="Opgave Navn">
            </div>

            <div class="form-row">
                <label for="description">Beskrivelse:</label>
                <textarea id="description" name="description" placeholder="Opgave Beskrivelse"></textarea>
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
                    <input id="user_search" type="text" placeholder="Søg efter brugere..." style="width: 100%;" oninput="filterUsers()">
                    <div id="user_list" class="checkbox-group" style="max-height: 220px; overflow-y: auto; border: 1.5px solid var(--color-border); border-radius: 8px; padding: 0.5rem; background: var(--color-bg-elevated); display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.5rem;">
                        @forelse ($users as $user)
                            <label class="assignee-item" data-name="{{ Str::lower($user->name) }}" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 500;">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }} />
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
<script>
function filterUsers(){
    var q = (document.getElementById('user_search').value || '').toLowerCase();
    document.querySelectorAll('#user_list .assignee-item').forEach(function(el){
        var n = el.getAttribute('data-name');
        el.style.display = (!q || (n && n.indexOf(q) !== -1)) ? 'flex' : 'none';
    });
}
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger: {{ $event->eventName }} | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editevent.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])
    <main class="edit-container">
        <div class="edit-card">
            <div class="edit-header">
                <h1 class="edit-title">Rediger begivenhed</h1>
            </div>

            <form class="edit-form" method="POST" action="{{ route('events.update', ['id' => $event->id]) }}">
             @csrf
             @method('PUT')

                <div class="form-row">
                    <label for="eventName">Titel</label>
                    <input type="text" id="eventName" name="eventName" value="{{ old('eventName', $event->eventName) }}" required placeholder="Indtast begivenhedens titel" />
                </div>

                <div class="form-row">
                    <label for="location">Lokation</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" placeholder="Indtast lokation" />
                </div>

                <div class="edit-grid">
                    <div class="form-row">
                        <label for="startDate">Start tidspunkt</label>
                        <input type="datetime-local" id="startDate" name="startDate" value="{{ old('startDate', \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\TH:i')) }}" required />
                    </div>
                    <div class="form-row">
                        <label for="endDate">Slut tidspunkt</label>
                        <input type="datetime-local" id="endDate" name="endDate" value="{{ old('endDate', \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\TH:i')) }}" required />
                    </div>
                </div>

                <div class="form-row">
                    <label for="description">Beskrivelse</label>
                    <div style="position: relative;">
                        <textarea id="description" name="description" rows="4" placeholder="Beskriv begivenheden" maxlength="800" style="padding-bottom: 22px;">{{ old('description', $event->description) }}</textarea>
                        <span id="description-counter" style="position: absolute; bottom: 6px; right: 8px; font-size: 12px; color: var(--text-muted, #6b7280);"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary-btn">Gem ændringer</button>
                    <a href="{{ url('/events/'.$event->id) }}" class="btn secondary-btn">Annuller</a>
                </div>
            </form>
        </div>
    </main>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ta = document.getElementById('description');
    var counter = document.getElementById('description-counter');
    if (!ta || !counter) return;
    var MAX = 800;
    function update() { counter.textContent = (ta.value.length || 0) + '/' + MAX; }
    ta.addEventListener('input', function() {
        if (ta.value.length > MAX) { ta.value = ta.value.slice(0, MAX); }
        update();
    });
    update();
});
</script>
</html>



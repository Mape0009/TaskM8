<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger: {{ $event->eventName }} | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .edit-container {max-width: 880px; margin: 2rem auto; padding: 0 1rem;}
        .edit-card {background: var(--color-background-secondary); border:1px solid var(--color-border); border-radius: 16px; box-shadow: 0 10px 30px var(--color-shadow-light); padding: 2rem;}
        .edit-header {display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem}
        .edit-title {font-size: 1.5rem; font-weight: 700; letter-spacing: -.2px; color: var(--color-text-primary)}
        .edit-form .form-row {display:flex; flex-direction:column; gap:.5rem; margin-bottom:1rem}
        .edit-form label {font-weight: 600; color: var(--color-text-primary)}
        .edit-form input[type="text"],
        .edit-form input[type="datetime-local"],
        .edit-form textarea {border:1px solid var(--color-border); background: var(--color-background-primary); color: var(--color-text-primary); border-radius: 10px; padding:.85rem 1rem; font: inherit}
        .edit-form textarea {resize: vertical; min-height: 110px}
        .edit-grid {display:grid; grid-template-columns:1fr 1fr; gap:1rem}
        .form-actions {display:flex; gap:.75rem; justify-content:flex-end; margin-top:1.5rem}
        .helper {color: var(--color-text-secondary); font-size:.9rem}

        @media (max-width: 768px) {
            .edit-grid {grid-template-columns: 1fr}
        }
    </style>
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])
    <main class="edit-container">
        <div class="edit-card">
            <div class="edit-header">
                <h1 class="edit-title">Rediger begivenhed</h1>
                <a href="{{ url('/events/'.$event->id) }}" class="back-btn">&larr; Tilbage</a>
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
                        <span class="helper">Format: ÅÅÅÅ-MM-DD TT:MM</span>
                    </div>
                    <div class="form-row">
                        <label for="endDate">Slut tidspunkt</label>
                        <input type="datetime-local" id="endDate" name="endDate" value="{{ old('endDate', \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\TH:i')) }}" required />
                    </div>
                </div>

                <div class="form-row">
                    <label for="description">Beskrivelse</label>
                    <textarea id="description" name="description" rows="4" placeholder="Beskriv begivenheden">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="form-actions">
                    <a href="{{ url('/events/'.$event->id) }}" class="btn secondary-btn">Annuller</a>
                    <button type="submit" class="btn primary-btn">Gem ændringer</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>



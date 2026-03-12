<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Rediger opgave | TaskM8';
        $metaDescription = 'Opdater opgaveinformation og tilknytning i TaskM8.';
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
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="task-edit-page">
@include('partials.header', ['currentPage' => 'tasks'])
<main class="main-content-full">
    @php
        $taskStart = $tasks->start_time ? \Carbon\Carbon::parse($tasks->start_time) : null;
        $taskEnd = $tasks->end_time ? \Carbon\Carbon::parse($tasks->end_time) : null;
        $taskDurationLabel = 'Ikke sat';
        if ($taskStart && $taskEnd && $taskEnd->greaterThan($taskStart)) {
            $minutes = $taskStart->diffInMinutes($taskEnd);
            $hours = intdiv($minutes, 60);
            $rest = $minutes % 60;
            $taskDurationLabel = $hours > 0
                ? ($hours . ' t' . ($rest > 0 ? ' ' . $rest . ' min' : ''))
                : ($rest . ' min');
        }
    @endphp
    <div class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">Rediger opgave</p>
                <h1>{{ $tasks->taskName }}</h1>
                <p class="lede">Opdater titel, beskrivelse og tidspunkt, så opgaven passer til den aktuelle plan.</p>
            </div>
            <div class="hero-actions">
                <a href="{{ url()->previous() }}" class="btn secondary-ghost">Tilbage</a>
            </div>
        </section>

        <section class="task-edit-layout">
            <article class="task-edit-main edit-card">
                @if($errors->any())
                    <div class="alert alert-error" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST" class="edit-form task-edit-form" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <label for="taskName">Opgavenavn</label>
                        <input type="text" id="taskName" name="taskName" value="{{ old('taskName', $tasks->taskName) }}" maxlength="255" required placeholder="Fx Klargoering af lokale">
                        @error('taskName')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label for="description">Beskrivelse</label>
                        <div class="textarea-wrap">
                            <textarea id="description" name="description" rows="5" maxlength="800" placeholder="Skriv hvad opgaven indeholder, og hvad der forventes af personen.">{{ old('description', $tasks->description) }}</textarea>
                            <span class="counter" id="task-description-counter">0 / 800</span>
                        </div>
                        @error('description')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="task-datetime-grid">
                        <div class="form-row">
                            <label for="startDate">Starttidspunkt</label>
                            <input type="datetime-local" id="startDate" name="startDate"
                                value="{{ old('startDate', $tasks->start_time ? \Carbon\Carbon::parse($tasks->start_time)->format('Y-m-d\\TH:i') : '') }}"
                                @if(isset($event) && $event->startDate)
                                    min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\\TH:i') }}"
                                @endif
                                @if(isset($event) && $event->endDate)
                                    max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\\TH:i') }}"
                                @endif
                            >
                            @error('startDate')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-row">
                            <label for="endDate">Sluttidspunkt</label>
                            <input type="datetime-local" id="endDate" name="endDate"
                                value="{{ old('endDate', $tasks->end_time ? \Carbon\Carbon::parse($tasks->end_time)->format('Y-m-d\\TH:i') : '') }}"
                                @if(isset($event) && $event->startDate)
                                    min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\\TH:i') }}"
                                @endif
                                @if(isset($event) && $event->endDate)
                                    max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\\TH:i') }}"
                                @endif
                            >
                            @error('endDate')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions task-edit-actions">
                        <a href="{{ url()->previous() }}" class="btn secondary-btn">Annuller</a>
                        <button type="submit" class="btn primary-btn">Gem ændringer</button>
                    </div>
                </form>
            </article>

            <aside class="task-edit-aside edit-card">
                <h3>Overblik</h3>
                <p>Hold opgaven kort og tydelig, så deltagerne ved præcis hvad de skal.</p>

                @if(isset($event))
                    <div class="task-edit-meta-item">
                        <span>Begivenhed</span>
                        <strong>{{ $event->eventName }}</strong>
                    </div>
                    <div class="task-edit-meta-item">
                        <span>Tilladt tidsrum</span>
                        <strong>
                            @if($event->startDate && $event->endDate)
                                {{ \Carbon\Carbon::parse($event->startDate)->format('d.m.Y H:i') }} - {{ \Carbon\Carbon::parse($event->endDate)->format('d.m.Y H:i') }}
                            @else
                                Ikke sat
                            @endif
                        </strong>
                    </div>
                @endif

                <div class="task-edit-meta-item">
                    <span>Nuværende varighed</span>
                    <strong>{{ $taskDurationLabel }}</strong>
                </div>
            </aside>
        </section>
    </div>
</main>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const description = document.getElementById('description');
    const counter = document.getElementById('task-description-counter');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    const updateCounter = () => {
        if (!description || !counter) return;
        counter.textContent = `${description.value.length} / 800`;
    };

    const normalizeEndDate = () => {
        if (!startDate || !endDate || !startDate.value) return;
        const start = new Date(startDate.value);
        const end = endDate.value ? new Date(endDate.value) : null;
        if (!end || end <= start) {
            const plusHour = new Date(start.getTime() + 60 * 60 * 1000);
            const pad = (n) => String(n).padStart(2, '0');
            endDate.value = `${plusHour.getFullYear()}-${pad(plusHour.getMonth() + 1)}-${pad(plusHour.getDate())}T${pad(plusHour.getHours())}:${pad(plusHour.getMinutes())}`;
        }
    };

    if (description) {
        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    if (startDate) {
        startDate.addEventListener('change', normalizeEndDate);
    }
});
</script>

</body>
</html>



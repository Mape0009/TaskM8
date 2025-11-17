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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@include('partials.header', ['currentPage' => 'tasks'])
<div class="edit-container">
    <div class="edit-header" style="display: flex; align-items: center; justify-content: space-between;">
        <h1 class="edit-title">Rediger Opgave</h1>
        <a href="{{ url()->previous() }}" class="btn white-btn">← Tilbage</a>
    </div>

    <div class="edit-card">
        <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST" class="edit-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <label for="taskName">Opgave Navn:</label>
                <input type="text" name="taskName" value="{{ $tasks->taskName }}" placeholder="Opgave Navn">
            </div>

            <div class="form-row">
                <label for="description">Beskrivelse:</label>
                <textarea name="description" placeholder="Opgave Beskrivelse">{{ $tasks->description }}</textarea>
            </div>

            <div class="form-row">
                <label for="startDate">Start Tidspunkt:</label>
                <input type="datetime-local" id="startDate" name="startDate" required 
                    value="{{ old('startDate', \Carbon\Carbon::parse($tasks->start_time)->format('Y-m-d\\TH:i')) }}"
                    @if(isset($event) && $event->startDate)
                        min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\\TH:i') }}"
                    @endif
                    @if(isset($event) && $event->endDate)
                        max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\\TH:i') }}"
                    @endif
                >
            </div>

            <div class="form-row">
                <label for="endDate">Slut Tidspunkt:</label>
                <input type="datetime-local" id="endDate" name="endDate" required 
                    value="{{ old('endDate', \Carbon\Carbon::parse($tasks->end_time)->format('Y-m-d\\TH:i')) }}"
                    @if(isset($event) && $event->startDate)
                        min="{{ \Carbon\Carbon::parse($event->startDate)->format('Y-m-d\\TH:i') }}"
                    @endif
                    @if(isset($event) && $event->endDate)
                        max="{{ \Carbon\Carbon::parse($event->endDate)->format('Y-m-d\\TH:i') }}"
                    @endif
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary-btn">Opdater Opgave</button>
            </div>
        </form>
    </div>
</div>

<style>
    @media (max-width: 2400px) {
    .form-row {
        margin-bottom: 16px;
    }

    .edit-container {
        display: block;
        padding: 4rem 8rem;
    }
    .btn.primary-btn {
        padding: 1rem 1.5rem;
    }
}
    @media (max-width: 430px) {
    .edit-container {
        padding: 2rem 1rem;
    }
    .form-row {
        margin-bottom: 2rem;
    }

}
</style>

</body>
</html>



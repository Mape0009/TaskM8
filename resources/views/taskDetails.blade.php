<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = ($task->taskName ?? 'Opgave') . ' | TaskM8';
        $metaDescription = Str::limit($task->description ?? 'Se detaljer for opgaven i TaskM8.', 150);
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
<main class="event-hero-bg">
    <div class="event-details-card">
        <div class="event-details-header">
            <div class="event-details-icon">
                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path fill="#000000" fill-rule="evenodd" d="M4,4 L9,4 C9.55228,4 10,3.55228 10,3 C10,2.44772 9.55228,2 9,2 L4,2 C2.89543,2 2,2.89543 2,4 L2,12 C2,13.1046 2.89543,14 4,14 L12,14 C13.1046,14 14,13.1046 14,12 L14,10 C14,9.44771 13.5523,9 13,9 C12.4477,9 12,9.44771 12,10 L12,12 L4,12 L4,4 Z M15.2071,2.29289 C14.8166,1.90237 14.1834,1.90237 13.7929,2.29289 L8.5,7.58579 L7.70711,6.79289 C7.31658,6.40237 6.68342,6.40237 6.29289,6.79289 C5.90237,7.18342 5.90237,7.81658 6.29289,8.20711 L7.79289,9.70711 C7.98043,9.89464 8.23478,10 8.5,10 C8.76522,10 9.01957,9.89464 9.20711,9.70711 L15.2071,3.70711 C15.5976,3.31658 15.5976,2.68342 15.2071,2.29289 Z"/>
                </svg>
            </div>
            <div class="event-header-info">
                <h1 class="event-details-title">{{ $task->taskName }}</h1>
                <div class="event-details-dates">
                    <strong>{{ \Carbon\Carbon::parse($task->start_time)->format('d-m-Y H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('d-m-Y H:i') }}</strong>
                </div>
            </div>
        </div>

        @if($task->description)
            <div class="event-details-description">
                {{ $task->description }}
            </div>
        @endif

   

        <div class="event-actions-details" style="display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;">
            <a href="{{ url()->previous() }}" class="btn white-btn">Tilbage</a>
            <a href="/tasks/{{ $task->id }}/edit" class="btn primary-btn">Rediger</a>
            <button type="button" class="bin-button" aria-label="Slet opgave" onclick="document.getElementById('delete-task-modal').style.display='flex'">
                <svg class="bin-top" viewBox="0 0 39 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line>
                    <line x1="12" y1="1.5" x2="26.0357" y2="1.5" stroke="white" stroke-width="3"></line>
                </svg>
                <svg class="bin-bottom" viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="path-1-inside-1_8_19" fill="white">
                        <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                    </mask>
                    <path d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z" fill="white" mask="url(#path-1-inside-1_8_19)"></path>
                    <path d="M12 6L12 29" stroke="white" stroke-width="4"></path>
                    <path d="M21 6V29" stroke="white" stroke-width="4"></path>
                </svg>
            </button>
        </div>

        <div id="delete-task-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="delete-task-title" style="display:none;">
            <div class="confirm-modal-content">
                <div class="confirm-modal-body">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                    </svg>
                    <h2 id="delete-task-title" class="confirm-title">Er du sikker?</h2>
                    <p class="confirm-text">Vil du slette denne opgave? Dette kan ikke fortrydes.</p>
                </div>
                <div class="confirm-actions">
                    <button type="button" class="confirm-btn cancel" onclick="document.getElementById('delete-task-modal').style.display='none'">Annuller</button>
                    <form action="{{ route('task.delete', ['id' => $task->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="confirm-btn danger">Slet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
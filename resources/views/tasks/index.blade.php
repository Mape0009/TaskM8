<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = isset($event) ? ($event->eventName . ' – Opgaver | TaskM8') : 'Opgaver | TaskM8';
        $metaDescription = isset($event)
            ? ('Se og administrer opgaver for ' . $event->eventName)
            : 'Se og administrer dine opgaver relateret til begivenheder i TaskM8.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@include('partials.header', ['currentPage' => 'tasks'])

<main class="main-content-full">
    <section class="task-listing">
        <div class="task-listing-header" style="margin-bottom: 1rem;">
            <div class="header-content">
                <h2>{{ isset($event) ? 'Opgaver for ' . $event->eventName : 'Mine opgaver' }}</h2>
                @if(isset($event))
                    <p class="header-description">Administrer opgaver og vagter for denne begivenhed</p>
                @else
                    <p class="header-description">Se og administrer alle dine opgaver</p>
                @endif
            </div>
            <div class="header-actions" style="gap: 0.75rem;">
                @if(isset($event))
                    <a href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}" class="btn primary-btn">
                        <i class="fas fa-plus"></i>
                        Opret Opgave
                    </a>
                @else
                    <a href="/tasks/create" class="btn primary-btn">
                        <i class="fas fa-plus"></i>
                        Opret Opgave
                    </a>
                @endif
            </div>
        </div>

        <div class="task-list" style="margin-top: 1rem; gap: 1.25rem;">
            @foreach($tasks as $task)
                <div class="task-card">
                    <div class="task-header">
                        <div class="task-title-section">
                            <h3>{{ $task->taskName }}</h3>
                            @if($task->description)
                                <p class="task-description">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="task-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $task->shifts->count() }} vagt{{ $task->shifts->count() !== 1 ? 'er' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="task-actions">
                        <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn primary-btn">
                            <i class="fas fa-list"></i>
                            Vagter
                        </a>
                        <a href="/tasks/{{ $task->id }}/edit" class="btn secondary-btn">
                            <i class="fas fa-edit"></i>
                            Rediger Opgave
                        </a>
                        <button type="button" class="bin-button" aria-label="Slet Opgave" onclick="openDeleteModal({{ $task->id }})">
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
                </div>
            @endforeach
        </div>
    </section>
</main>

@auth
<div id="delete-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" style="display:none;">
    <div class="confirm-modal-content">
        <div class="confirm-modal-body">
            <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
            </svg>
            <h2 id="confirm-title" class="confirm-title">Er du sikker?</h2>
            <p class="confirm-text">Vil du slette denne opgave? Dette kan ikke fortrydes.</p>
        </div>
        <div class="confirm-actions">
            <button type="button" class="confirm-btn cancel" onclick="closeDeleteModal()">Annuller</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-btn danger">Slet</button>
            </form>
        </div>
    </div>
</div>
@endauth

<script src="{{ asset('js/task.js') }}"></script>
@include('partials.footer')
</body>
</html>


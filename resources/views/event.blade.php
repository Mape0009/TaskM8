<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($event) ? $event->title : 'Event Details' }} | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])
    <main>
        <div class="event-details-container">
            <div class="event-details-header">
                <span class="event-details-icon">
                    <svg width="32" height="32" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </span>
                <h1 class="event-details-title">{{ $event->title ?? 'Event Title' }}</h1>
            </div>
            <ul class="event-details-list">
                <li><span class="event-details-label">Start:</span> <span class="event-details-value">{{ $event->start_time ?? '-' }}</span></li>
                <li><span class="event-details-label">End:</span> <span class="event-details-value">{{ $event->end_time ?? '-' }}</span></li>
                <li><span class="event-details-label">Location:</span> <span class="event-details-value">{{ $event->location ?? '-' }}</span></li>
            </ul>
            <div class="event-details-description">
                {{ $event->description ?? 'No description provided.' }}
            </div>
            <a href="{{ url('/events') }}" class="back-btn">&larr; Back to Events</a>
        </div>
    </main>
</body>
</html> 
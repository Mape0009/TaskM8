<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Begivenheder</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])

    <main class="main-content-full">
        <header class="content-header">
            <h1></h1>
        </header>
        {{-- <div class="search-bar">
            <input type="text" placeholder="Søg efter begivenheder...">
        </div> --}}

        <section class="event-listing">
            <h2>Mine begivenheder</h2>
            <div class="event-list">
                @forelse($events as $event)
                    <div class="event-card">
                        <div class="event-header">
                            <h3>{{ $event->eventName }}</h3>
                        </div>
                        <p class="event-description">{{ $event->description }}</p>
                        <div class="event-actions">
                            <a href="/events/{{ $event->id }}" class="btn primary-btn">Se detaljer</a>
                            <button class="btn secondary-btn">Rediger <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
                        </div>
                    </div>
                @empty
                    <p>Ingen begivenheder fundet.</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html> 
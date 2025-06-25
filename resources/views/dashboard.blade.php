<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 Forside</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

    <main class="main-content-full">
        <header class="content-header">
        </header>
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value">12</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value">4</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Medlemmer: </span>
                    <span class="stat-value">13</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><circle cx="17" cy="7" r="4"></circle></svg>
                </div>
            </div>
        </section>
        <section class="upcoming-events">
            <h2>Kommende Begivenheder</h2>
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
    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}" type="module"></script>
</body>
</html> 
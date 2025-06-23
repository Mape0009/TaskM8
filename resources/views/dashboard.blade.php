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
        <div class="search-bar">
            <input type="text" placeholder="Søg efter begivenheder...">
        </div>
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value">12</span>
                </div>
                <div class="stat-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value">4</span>
                </div>
                <div class="stat-icon"><svg class="icon" fill="non  e" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path><circle cx="10" cy="7" r="4"></circle></svg></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Medlemmer: </span>
                    <span class="stat-value">13</span>
                </div>
            <div class="stat-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path><circle cx="10" cy="7" r="4"></circle></svg></div>
            </div>
        </section>
        <section class="upcoming-events">
            <h2>Kommende Begivenheder</h2>
            <div class="event-list">
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></span>
                        <h3>Havnefest</h3>
                    </div>
                    <p class="event-description">Fest på havnen! Husk det gode humør</p>
                    <div class="event-actions">
                        <a href="#" class="btn primary-btn">Svar</a>
                        <a href="{{ url('/events') }}" class="btn secondary-btn">Se detaljer <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></a>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.836 0H20v-5m0 11v5h-.581m-15.838 0H4v-5m2.597-8.597a2 2 0 112.828 2.828L7 15l-2 3v4h4l3-2 3.597-3.597m-2.828-2.828l1.414 1.414L15 15l2-3v-4h-4l-3-2-3.597 3.597z"></path></svg></span>
                        <h3>Byfest</h3>
                    </div>
                    <p class="event-description">Fest for byens beboere. Kom glad!</p>
                    <div class="event-actions">
                        <a href="#" class="btn primary-btn">Svar</a>
                        <a href="{{ url('/events') }}" class="btn secondary-btn">Se detaljer <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></a>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                        <h3>Grillaften</h3>
                    </div>
                    <p class="event-description">Roklubben holder grillaften!</p>
                    <div class="event-actions">
                        <a href="#" class="btn primary-btn">Svar</a>
                        <a href="{{ url('/events') }}" class="btn secondary-btn">Se detaljer <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}" type="module"></script>
</body>
</html> 
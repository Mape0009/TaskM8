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
                <!-- Event Card 1 -->
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></span>
                        <h3>Havnefest</h3>
                    </div>
                    <p class="event-description">Fest på havnen! Husk det gode humør.</p>
                    <div class="event-actions">
                        <a href="/events/1" class="btn primary-btn">Se detaljer</a>
                        <button class="btn secondary-btn">Rediger <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
                    </div>
                </div>

                <!-- Event Card 2 -->
                <div class="event-card">
                    <div class="event-header">
                        <span class="event-icon"><svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                        <h3>Byfest</h3>
                    </div>
                    <p class="event-description">Fest for byens beboere! Kom glad og drik bajer.</p>
                    
                    <div class="event-actions">
                        <a href="/events/2" class="btn primary-btn">Se detaljer</a>
                        <button class="btn secondary-btn">Rediger <svg class="icon arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html> 
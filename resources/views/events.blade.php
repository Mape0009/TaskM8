<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Begivenheder | TaskM8';
        $metaDescription = 'Se dine begivenheder og få hurtigt overblik i TaskM8.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])

    <main class="main-content-full">

        <section class="event-listing">
            <h2>Mine begivenheder</h2>
            <div class="event-list">
                @forelse($events as $event)
                    <div class="event-card">
                        <div class="event-header">
                            <h3>{{ $event->eventName }}</h3>
                        </div>
                        <p class="event-description">{{ Str::limit($event->description, 25) }}</p>
                        <div class="event-actions">
                            <a href="/events/{{ $event->id }}" class="btn primary-btn">Se detaljer</a>
                            @auth
                                @if(isset($event->ownerId) && $event->ownerId === auth()->id())
                                    <a href="/events/{{ $event->id }}/edit" class="btn secondary-btn">Rediger</a>
                                @endif
                            @endauth
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
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gruppeoversigt | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupoverview.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

    <main class="overview-container">
        <div class="overview-header">
            <h1 class="overview-title">Gruppeoversigt</h1>
            <a href="{{ url('groups/create') }}" class="btn create-btn">Opret gruppe</a>
        </div>

        @if($groups->isEmpty())
            <p class="no-groups">Ingen grupper oprettet endnu.</p>
        @else
            <div class="groups-grid">
                @foreach ($groups as $group)
                    <div class="group-card">
                        <div class="group-card-header">
                            <h2>{{ $group->groupName }}</h2>
                            <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                {{ $group->private ? 'Privat' : 'Offentlig' }}
                            </span>
                        </div>
                        <p class="group-description">{{ $group->description }}</p>
                        <form action="{{ route('groups.delete', ['id' => $group->id]) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete-btn">Slet</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Creation | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupCreation.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

        <main class="group-container">
        <div class="group-card">
            <div class="group-header">
                <h1 class="group-title">Opret gruppe</h1>
            </div>

    <form class ="group-form" action="{{ route('groups.create') }}" method="POST">
        @csrf
        <div class="group-row">
        <label for="groupName">Gruppenavn:</label>
        <input type="text" id="groupName" name="groupName" required><br><br>
        </div>
        <div class="group-row">
        <label for="description">Beskrivelse:</label>
        <textarea id="description" name="description"></textarea><br><br>
        </div>
<div class="group-row">
    <label for="private">Privat gruppe:</label>
    <label class="switch">
        <input type="checkbox" id="private" name="private">
        <span class="slider"></span>
    </label>
</div>

        <div class="form-actions">
        <button type="submit" class="btn primary-btn">Opret gruppe</button>
        <a href="{{ url('/groups/overview') }}" class="btn secondary-btn">Annuller</a>

        </div>
        
                   </div>
            </form>
        </div>
    </main>
</body>
</html>
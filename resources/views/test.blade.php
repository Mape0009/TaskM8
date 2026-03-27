<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <h1>Test</h1>
    <p>This is a test page.</p>
    @forelse ($volunteers as $volunteer)
    <div class="volunteer">
        <h3>{{ $volunteer['name'] }}</h3>
    </div>

    <form action="{{ route('events.removeVolunteer', ['participantId' => $volunteer['id']]) }}" method="POST">
        @csrf
        <button type="submit">Remove Volunteer</button>
    </form>

    <form action="{{ route('events.promoteFromVolunteer', ['participantId' => $volunteer['id']]) }}" method="POST">
        @csrf
        <button type="submit">Promote from Volunteer</button>
    </form>
    @empty
        <p>No volunteers found.</p>
    @endforelse
</body>
</html>
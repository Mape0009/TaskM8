<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Email</title>
</head>
<body>
    <h1>This is an overview of participants</h1>
    <form action="{{ route('events.participants', ['eventId' => $eventId]) }}" method="GET">
        @csrf
        @foreach ($participants as $participant)
            <div>
                <p>Name: {{ $participant->user->name }}</p>
                <p>Email: {{ $participant->user->email }}</p>
                <br>
            </div>
        @endforeach
    </form>
</body>
</html>
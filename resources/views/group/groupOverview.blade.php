<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Overview | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
     @include('partials.header', ['currentPage' => 'dashboard'])

    <h1>Group Overview</h1>
    @foreach ($groups as $group)
        <div>
            <h2>{{ $group->groupName }}</h2>
            <p>{{ $group->description }}</p>
            <p>Private: {{ $group->private ? 'Yes' : 'No' }}</p>
            <form action="{{ route('groups.delete', ['id' => $group->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>   
    @endforeach
</body>
</html>
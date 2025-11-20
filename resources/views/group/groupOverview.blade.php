<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Overview</title>
</head>
<body>
    <h1>Group Overview</h1>
    @foreach ($groups as $group)
        <div>
            <h2>{{ $group->groupName }}</h2>
            <p>{{ $group->description }}</p>
            <p>Private: {{ $group->private ? 'Yes' : 'No' }}</p>
            <form action="{{ route('groups.delete', ['id' => $group->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">delete</button>
            </form>
        </div>   
    @endforeach
</body>
</html>
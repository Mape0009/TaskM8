<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Overview</title>
</head>
<body>
    <h1>Group Member Overview</h1>
    @foreach ($groupMembers as $groupMember)
        <div>
            <h2>{{ $groupMember->userId }}</h2>
            <form action="{{ route('groupMember.delete', ['id' => $groupMember->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">delete</button>
            </form>
        </div>   
    @endforeach
</body>
</html>
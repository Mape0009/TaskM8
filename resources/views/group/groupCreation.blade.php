<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
</head>

<body>
    <h1>Create a New Group</h1>
    <form action="{{ route('groups.create') }}" method="POST">
        @csrf
        <label for="groupName">Group Name:</label>
        <input type="text" id="groupName" name="groupName" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea><br><br>

        <label for="private">Private Group:</label>
        <input type="checkbox" id="private" name="private"><br><br>

        <button type="submit">Create Group</button>
    </form>
</body>
</html>
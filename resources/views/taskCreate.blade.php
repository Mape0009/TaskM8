<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('task.create') }}" method="POST">
        @csrf
        <input type="text" name="taskName" placeholder="task Name">
        <button type="submit">Create task</button>
    </form>   
</body>
</html>

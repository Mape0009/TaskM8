<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Email</title>
</head>
<body>
    <p>Email</p>
    <form action="{{ route('events.invite') }}" method="POST">
        @csrf
    <input type="hidden" name="eventIdInvite" value="5">
        <input type="email" name="emailsInvite[]" required>
        <button type="submit">Send Invite</button>
    </form>
</body>
</html>
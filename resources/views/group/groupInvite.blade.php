<!DOCTYPE html>
<html lang="da">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TaskM8 | Invitation</title>
    </head>

    <body>
        <form action="{{ route('groupMember.invite.post', ['groupId' => $groupId]) }}" method="POST">
            @csrf
            <input type="hidden" name="groupId" value="{{ $groupId }}">

            @foreach ($users as $user)
                <div>
                    <label>
                        <input type="checkbox" name="userId[]" value="{{ $user->id }}">
                        {{ $user->name ?? $user->email }} (ID: {{ $user->id }})
                    </label>
                </div>
            @endforeach

            <button type="submit">Invite selected users</button>
        </form>
    </body>
</html>
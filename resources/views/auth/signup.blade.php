<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opret konto - TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</head>
<body>
    <div class="auth-container">
        <h2>Opret Konto</h2>
        <form action="{{ route('user.create') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="name">Navn</label>
                <input type="text" id="name" name="name" placeholder="Skriv dit navn" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Skriv din email" value="{{ request('email') }}" required>
            </div>
            <div class="input-group">
                <label for="pin">Invitationskode (PIN)</label>
                <input type="text" id="pin" name="pin" placeholder="4-cifret kode" value="{{ request('pin') }}" maxlength="4">
            </div>
            <input type="hidden" name="event_id" value="{{ request('event') }}">
            <div class="input-group">
                <label for="password">Adgangskode</label>
                <input type="password" id="password" name="password" placeholder="Lav adgangskode" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">Bekræft adgangskode</label>
                <input type="password" id="confirm-password" name="password_confirmation" placeholder="Bekræft adgangskode" required>
            </div>
            <div class="input-group">
                <label for="phone">Telefon-Nummer (Valgfrit)</label>
                <input type="tel" id="phone" name="phonenumber" placeholder="Skriv dit telefon-nummer">
            </div>
            <button type="submit" class="btn primary-btn">Opret Konto</button>
        </form>
        <p>Har du allerede en konto? <a href="/signin">Login</a></p>
    </div>
</body>
</html>
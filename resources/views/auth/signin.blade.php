<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Log ind | TaskM8';
        $metaDescription = 'Log ind på TaskM8 for at planlægge og styre dine begivenheder.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
        'robots' => 'noindex, nofollow',
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</head>
<body class="signin-page">
    <div class="auth-container">
        <h2>Login</h2>
        <form action="{{ route('loginPost') }}" method="POST">
            @csrf
            @if (session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="error-message">
        {{ $errors->first() }}
    </div>
@endif

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Skriv email" required>
            </div>
            <div class="input-group">
                <label for="password">Adgangskode</label>
                <input type="password" id="password" name="password" placeholder="Skriv adgangskode" required>
            </div>
            <button type="submit" class="btn primary-btn">Login</button>
        </form>
        <p>Har du ingen konto? <a href="/signup">Opret Konto</a></p>
    </div>
</body>
</html> 
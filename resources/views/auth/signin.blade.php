<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Login';
        $metaDescription = 'Log ind på din TaskM8-konto for at få adgang til dine begivenheder, opgaver og grupper. Indtast din e-mail og adgangskode for at fortsætte.';
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
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</head>
<body class="signin-page">
    <div id="tm8-page-loader" class="tm8-page-loader" aria-hidden="true">
        <div class="tm8-page-loader__card" role="status" aria-live="polite" aria-label="Logger ind">
            <div class="loading-wave" aria-hidden="true">
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
            </div>
            <h2 class="tm8-page-loader__title">Logger ind</h2>
            <p class="tm8-page-loader__text">Vi gør dit dashboard klar.</p>
        </div>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.auth-container form');
            const loader = document.getElementById('tm8-page-loader');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;

            if (!form || !loader) {
                return;
            }

            let isSubmitting = false;

            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    return;
                }

                event.preventDefault();
                isSubmitting = true;

                loader.classList.add('is-visible');
                loader.setAttribute('aria-hidden', 'false');
                document.body.classList.add('tm8-loader-lock');

                if (submitButton) {
                    submitButton.disabled = true;
                }

                window.setTimeout(function () {
                    form.submit();
                }, 1000);
            });
        });
    </script>
</body>
</html> 
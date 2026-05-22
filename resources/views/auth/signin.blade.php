<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = 'TaskM8 | ' . __('ui.login');
        $metaDescription = __('ui.login_to_view_events');
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
        <div class="tm8-page-loader__card" role="status" aria-live="polite" aria-label="{{ __('ui.login') }}">
            <div class="loading-wave" aria-hidden="true">
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
            </div>
            <h2 class="tm8-page-loader__title">{{ __('ui.login') }}</h2>
            <p class="tm8-page-loader__text">{{ __('ui.guest_subtitle') }}</p>
        </div>
    </div>

    <div class="auth-container">
        <h2>{{ __('ui.login') }}</h2>
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
                <label for="email">{{ __('ui.email') }}</label>
                <input type="email" id="email" name="email" placeholder="{{ __('ui.email') }}" required>
            </div>
            <div class="input-group">
                <label for="password">{{ __('ui.current_password') }}</label>
                <input type="password" id="password" name="password" placeholder="{{ __('ui.current_password') }}" required>
            </div>
            <button type="submit" class="btn primary-btn">{{ __('ui.login') }}</button>
        </form>
        <p>{{ __('ui.no_account') }} <a href="/signup">{{ __('ui.sign_up') }}</a></p>
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = 'TaskM8 | ' . __('ui.sign_up');
        $metaDescription = __('ui.get_started');
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
<body class="signup-page">
    <div id="tm8-page-loader" class="tm8-page-loader" aria-hidden="true">
        <div class="tm8-page-loader__card" role="status" aria-live="polite" aria-label="{{ __('ui.sign_up') }}">
            <div class="loading-wave" aria-hidden="true">
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
                <div class="loading-bar"></div>
            </div>
            <h2 class="tm8-page-loader__title">{{ __('ui.sign_up') }}</h2>
            <p class="tm8-page-loader__text">{{ __('ui.get_started') }}</p>
        </div>
    </div>

    <div class="auth-container">
        <h2>{{ __('ui.sign_up') }}</h2>
        <form action="{{ route('user.create') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="name">{{ __('ui.name') }}</label>
                <input type="text" id="name" name="name" placeholder="{{ __('ui.name') }}" required>
            </div>
            <div class="input-group">
                <label for="email">{{ __('ui.email') }}</label>
                <input type="email" id="email" name="email" placeholder="{{ __('ui.email') }}" value="{{ request('email') }}" required>
            </div>
            <div class="input-group">
                <label for="password">{{ __('ui.new_password') }}</label>
                <input type="password" id="password" name="password" placeholder="{{ __('ui.new_password') }}" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">{{ __('ui.confirm_new_password') }}</label>
                <input type="password" id="confirm-password" name="password_confirmation" placeholder="{{ __('ui.confirm_new_password') }}" required>
            </div>
            <div class="input-group">
                <label for="pin">PIN</label>
                <input type="text" id="pin" name="pin" placeholder="PIN" value="{{ request('pin') }}" maxlength="4">
            </div>
            <div class="input-group">
                <label for="phone">{{ __('ui.phone_optional') }}</label>
                <input type="tel" id="phone" name="phonenumber" placeholder="{{ __('ui.phone') }}">
            </div>
            <input type="hidden" name="event_id" value="{{ request('event') }}">
            <button type="submit" class="btn primary-btn form-submit">{{ __('ui.sign_up') }}</button>
        </form>
        <p>{{ __('ui.already_account') }} <a href="/signin">{{ __('ui.login') }}</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.auth-container form');
            const loader = document.getElementById('tm8-page-loader');

            if (!form || !loader) {
                return;
            }

            let isSubmitting = false;

            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    return;
                }

                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    if (typeof form.reportValidity === 'function') {
                        form.reportValidity();
                    }
                    return;
                }

                event.preventDefault();
                isSubmitting = true;

                loader.classList.add('is-visible');
                loader.setAttribute('aria-hidden', 'false');
                document.body.classList.add('tm8-loader-lock');

                window.setTimeout(function () {
                    form.submit();
                }, 1000);
            });
        });
    </script>
</body>
</html>
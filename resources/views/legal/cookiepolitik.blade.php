<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.legal_cookie_page_title');
        $metaDescription = __('ui.legal_cookie_meta');
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
        'robots' => 'noindex, follow'
    ])
    <link rel="stylesheet" href="{{ asset('css/legal.css') }}">
</head>
<body>
    @include('partials.header', ['currentPage' => null])
    <main class="legal-shell">

        <header class="legal-hero" aria-labelledby="legal-title">
            <div class="legal-hero-head">
                <div class="legal-eyebrow">{{ __('ui.legal_cookie_eyebrow') }}</div>
                <h1 id="legal-title" class="legal-title">{{ __('ui.legal_cookie_title') }}</h1>
                <p class="legal-sub">{{ __('ui.legal_cookie_sub') }}</p>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>{{ __('ui.legal_cookie_toc') }}</h2>
                <div class="legal-toc-links">
                    <a href="#hvad-er">{{ __('ui.legal_cookie_what_link') }}</a>
                    <a href="#hvilke">{{ __('ui.legal_cookie_which_link') }}</a>
                    <a href="#varighed">{{ __('ui.legal_cookie_duration_link') }}</a>
                    <a href="#kontrol">{{ __('ui.legal_cookie_control_link') }}</a>
                    <a href="#tredjeparter">{{ __('ui.legal_cookie_third_party_link') }}</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="hvad-er">
                    <h2>{{ __('ui.legal_cookie_what') }}</h2>
                    <p>{{ __('ui.legal_cookie_what_text') }}</p>
                </section>

                <section class="legal-section" id="hvilke">
                    <h2>{{ __('ui.legal_cookie_which') }}</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>{{ __('ui.legal_cookie_session_title') }}</strong>
                            <span>{{ __('ui.legal_cookie_session_text') }}</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>{{ __('ui.legal_cookie_security_title') }}</strong>
                            <span>{{ __('ui.legal_cookie_security_text') }}</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>{{ __('ui.legal_cookie_consent_title') }}</strong>
                            <span>{{ __('ui.legal_cookie_consent_text') }}</span>
                        </div>
                    </div>
                    <div class="legal-note">{{ __('ui.legal_cookie_note') }}</div>
                </section>

                <section class="legal-section" id="varighed">
                    <h2>{{ __('ui.legal_cookie_duration') }}</h2>
                    <ul class="legal-list">
                        <li>{{ __('ui.legal_cookie_session_li') }}</li>
                        <li>{{ __('ui.legal_cookie_security_li') }}</li>
                        <li>{{ __('ui.legal_cookie_consent_li') }}</li>
                    </ul>
                </section>

                <section class="legal-section" id="kontrol">
                    <h2>{{ __('ui.legal_cookie_control') }}</h2>
                    <p>{{ __('ui.legal_cookie_control_text') }}</p>
                </section>

                <section class="legal-section" id="tredjeparter">
                    <h2>{{ __('ui.legal_cookie_third_party') }}</h2>
                    <p>{{ __('ui.legal_cookie_third_party_text') }}</p>
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

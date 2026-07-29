<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.legal_privacy_page_title');
        $metaDescription = __('ui.legal_privacy_meta');
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
    <main class="legal-shell legal-shell--compact">

        <header class="legal-hero" aria-labelledby="legal-title">
            <div class="legal-hero-head">
                <div class="legal-eyebrow">{{ __('ui.legal_privacy_eyebrow') }}</div>
                <h1 id="legal-title" class="legal-title">{{ __('ui.legal_privacy_title') }}</h1>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>{{ __('ui.legal_privacy_toc') }}</h2>
                <div class="legal-toc-links">
                    <a href="#hvad-indsamler-vi">{{ __('ui.legal_privacy_what_link') }}</a>
                    <a href="#hvorfor-bruger-vi-det">{{ __('ui.legal_privacy_why_link') }}</a>
                    <a href="#hvor-længe">{{ __('ui.legal_privacy_how_long_link') }}</a>
                    <a href="#dine-rettigheder">{{ __('ui.legal_privacy_rights_link') }}</a>
                    <a href="#sikkerhed">{{ __('ui.legal_privacy_security_link') }}</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="hvad-indsamler-vi">
                    <h2>{{ __('ui.legal_privacy_what') }}</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>{{ __('ui.legal_privacy_account_title') }}</strong>
                            <span>{{ __('ui.legal_privacy_account_text') }}</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>{{ __('ui.legal_privacy_content_title') }}</strong>
                            <span>{{ __('ui.legal_privacy_content_text') }}</span>
                        </div>
                    </div>
                </section>

                <section class="legal-section" id="hvorfor-bruger-vi-det">
                    <h2>{{ __('ui.legal_privacy_why') }}</h2>
                    <ul class="legal-list">
                        <li>{{ __('ui.legal_privacy_why_li1') }}</li>
                        <li>{{ __('ui.legal_privacy_why_li2') }}</li>
                        <li>{{ __('ui.legal_privacy_why_li3') }}</li>
                    </ul>
                    <div class="legal-note">{{ __('ui.legal_privacy_note') }}</div>
                </section>

                <section class="legal-section" id="hvor-længe">
                    <h2>{{ __('ui.legal_privacy_how_long') }}</h2>
                    <p>{{ __('ui.legal_privacy_how_long_text') }}</p>
                </section>

                <section class="legal-section" id="dine-rettigheder">
                    <h2>{{ __('ui.legal_privacy_rights') }}</h2>
                    <ul class="legal-list">
                        <li>{{ __('ui.legal_privacy_rights_li1') }}</li>
                        <li>{{ __('ui.legal_privacy_rights_li2') }}</li>
                        <li>{{ __('ui.legal_privacy_rights_li3') }}</li>
                        <li>{{ __('ui.legal_privacy_rights_li4') }}</li>
                    </ul>
                    <p>{{ __('ui.legal_privacy_rights_text') }}</p>
                </section>

                <section class="legal-section" id="sikkerhed">
                    <h2>{{ __('ui.legal_privacy_security') }}</h2>
                    <p>{{ __('ui.legal_privacy_security_text') }}</p>
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


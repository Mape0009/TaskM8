<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.legal_terms_page_title');
        $metaDescription = __('ui.legal_terms_meta');
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
                <div class="legal-eyebrow">{{ __('ui.legal_terms_eyebrow') }}</div>
                <h1 id="legal-title" class="legal-title">{{ __('ui.legal_terms_title') }}</h1>
                <p class="legal-sub">{{ __('ui.legal_terms_sub') }}</p>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>{{ __('ui.legal_terms_toc') }}</h2>
                <div class="legal-toc-links">
                    <a href="#accept">{{ __('ui.legal_terms_accept_link') }}</a>
                    <a href="#konto">{{ __('ui.legal_terms_account_link') }}</a>
                    <a href="#brug">{{ __('ui.legal_terms_use_link') }}</a>
                    <a href="#indhold">{{ __('ui.legal_terms_content_link') }}</a>
                    <a href="#ansvar">{{ __('ui.legal_terms_responsibility_link') }}</a>
                    <a href="#sluk-konto">{{ __('ui.legal_terms_termination_link') }}</a>
                    <a href="#lovvalg">{{ __('ui.legal_terms_law_link') }}</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="accept">
                    <h2>{{ __('ui.legal_terms_accept') }}</h2>
                    <p>{{ __('ui.legal_terms_accept_text') }}</p>
                </section>

                <section class="legal-section" id="konto">
                    <h2>{{ __('ui.legal_terms_account') }}</h2>
                    <ul class="legal-list">
                        <li>{{ __('ui.legal_terms_account_li1') }}</li>
                        <li>{{ __('ui.legal_terms_account_li2') }}</li>
                        <li>{{ __('ui.legal_terms_account_li3') }}</li>
                    </ul>
                </section>

                <section class="legal-section" id="brug">
                    <h2>{{ __('ui.legal_terms_use') }}</h2>
                    <p>{{ __('ui.legal_terms_use_text') }}</p>
                    <div class="legal-note">{{ __('ui.legal_terms_use_note') }}</div>
                </section>

                <section class="legal-section" id="indhold">
                    <h2>{{ __('ui.legal_terms_content') }}</h2>
                    <p>{{ __('ui.legal_terms_content_text') }}</p>
                </section>

                <section class="legal-section" id="ansvar">
                    <h2>{{ __('ui.legal_terms_responsibility') }}</h2>
                    <p>{{ __('ui.legal_terms_responsibility_text') }}</p>
                </section>

                <section class="legal-section" id="sluk-konto">
                    <h2>{{ __('ui.legal_terms_termination') }}</h2>
                    <p>{{ __('ui.legal_terms_termination_text') }}</p>
                </section>

                <section class="legal-section" id="lovvalg">
                    <h2>{{ __('ui.legal_terms_law') }}</h2>
                    <p>{{ __('ui.legal_terms_law_text') }}</p>
                </section>

            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


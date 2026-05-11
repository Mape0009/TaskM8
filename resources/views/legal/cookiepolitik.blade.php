<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Cookiepolitik';
        $metaDescription = 'Hvad er cookies, og hvordan bruger TaskM8 dem?';
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
                <div class="legal-eyebrow">Cookies</div>
                <h1 id="legal-title" class="legal-title">Cookiepolitik</h1>
                <p class="legal-sub">Kort: Cookies hjælper TaskM8 med at fungere. Vi bruger kun nødvendige cookies og ingen tracking.</p>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>Indhold</h2>
                <div class="legal-toc-links">
                    <a href="#hvad-er">Hvad er cookies?</a>
                    <a href="#hvilke">Hvilke vi bruger</a>
                    <a href="#varighed">Hvor længe</a>
                    <a href="#kontrol">Styring</a>
                    <a href="#tredjeparter">Tredjepart</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="hvad-er">
                    <h2>Hvad er cookies?</h2>
                    <p>Cookies er små filer, der gør hjemmesider bedre til dig. De husker fx login og indstillinger, så TaskM8 fungerer som den skal.</p>
                </section>

                <section class="legal-section" id="hvilke">
                    <h2>Hvilke cookies bruger vi?</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>Sessioncookies</strong>
                            <span>Holder dig logget ind, mens du bruger tjenesten.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Sikkerhedscookie</strong>
                            <span>Beskytter din konto og forhindrer misbrug.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Samtykkecookie</strong>
                            <span>Husker at du har godkendt cookies, så du ikke bliver spurgt igen.</span>
                        </div>
                    </div>
                    <div class="legal-note">Vi bruger ingen tracking- eller reklamecookies.</div>
                </section>

                <section class="legal-section" id="varighed">
                    <h2>Hvor længe gemmes cookies?</h2>
                    <ul class="legal-list">
                        <li>Sessioncookies slettes, når du lukker din browser</li>
                        <li>Sikkerhedscookies slettes når du logger ud eller efter en kort periode</li>
                        <li>Samtykkecookie kan gemmes i op til 12 måneder</li>
                    </ul>
                </section>

                <section class="legal-section" id="kontrol">
                    <h2>Sådan styrer du cookies</h2>
                    <p>Du kan altid slette eller blokere cookies i din browser. Bemærk at nogle funktioner kan blive påvirket, hvis du blokerer nødvendige cookies.</p>
                </section>

                <section class="legal-section" id="tredjeparter">
                    <h2>Tredjeparts cookies</h2>
                    <p>TaskM8 bruger primært egne cookies til drift. Vi deler ikke cookie-data til reklameleverandører.</p>
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

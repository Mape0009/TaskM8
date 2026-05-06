<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Privatlivspolitik';
        $metaDescription = 'Sådan behandler TaskM8 dine personoplysninger.';
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
                <div class="legal-eyebrow">Privatliv</div>
                <h1 id="legal-title" class="legal-title">Privatlivspolitik</h1>
                <p class="legal-sub">Kort: Vi behandler dine data kun for at levere tjenesten. Her er hvad vi indsamler og hvorfor.</p>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>Indhold</h2>
                <div class="legal-toc-links">
                    <a href="#hvad-indsamler-vi">Hvad vi indsamler</a>
                    <a href="#hvorfor-bruger-vi-det">Hvorfor</a>
                    <a href="#hvor-længe">Opbevaring</a>
                    <a href="#dine-rettigheder">Dine rettigheder</a>
                    <a href="#sikkerhed">Sikkerhed</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="hvad-indsamler-vi">
                    <h2>Hvad indsamler vi?</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>Kontodata</strong>
                            <span>Navn, e-mail og loginoplysninger – nødvendig for din konto.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Dit indhold</strong>
                            <span>Begivenheder, opgaver, vagter og deltagerinfo du opretter.</span>
                        </div>
                    </div>
                </section>

                <section class="legal-section" id="hvorfor-bruger-vi-det">
                    <h2>Hvorfor vi bruger data</h2>
                    <ul class="legal-list">
                        <li>For at levere og forbedre TaskM8</li>
                        <li>For at sikre platformen og kontakte dig ved behov</li>
                        <li>Kun nødvendige driftsleverandører får adgang (ingen salg af data)</li>
                    </ul>
                    <div class="legal-note">Vi sælger ikke dine personoplysninger.</div>
                </section>

                <section class="legal-section" id="hvor-længe">
                    <h2>Hvor længe opbevarer vi data?</h2>
                    <p>Vi beholder data kun så længe det er nødvendigt for tjenesten eller efter lovkrav. Sletter du din konto, fjerner vi personoplysninger i overensstemmelse med vores politik.</p>
                </section>

                <section class="legal-section" id="dine-rettigheder">
                    <h2>Dine rettigheder</h2>
                    <ul class="legal-list">
                        <li><strong>Indsigt</strong> – Se hvad vi gemmer om dig</li>
                        <li><strong>Rettelse</strong> – Få forkerte oplysninger rettet</li>
                        <li><strong>Sletning</strong> – Anmod om sletning af dine personoplysninger</li>
                        <li><strong>Dataportabilitet</strong> – Få dine data i et standardformat</li>
                    </ul>
                    <p>Kontakt os for at få hjælp til at bruge dine rettigheder.</p>
                </section>

                <section class="legal-section" id="sikkerhed">
                    <h2>Sikkerhed</h2>
                    <p>Vi beskytter data med moderne sikkerhedsforanstaltninger (kryptering, adgangskontrol og overvågning). Kun autoriserede medarbejdere har adgang ved behov.</p>
                </section>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


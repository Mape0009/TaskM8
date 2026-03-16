<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Privatlivspolitik | TaskM8';
        $metaDescription = 'Læs hvordan TaskM8 behandler personoplysninger i overensstemmelse med dansk og EU-lovgivning (GDPR).';
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
<body class="legal-page">
    @include('partials.header', ['currentPage' => null])
    <main class="legal-shell">
        <section class="legal-hero">
            <span class="legal-eyebrow">Juridisk</span>
            <div class="legal-hero-head">
                <h1 class="legal-title">Privatlivspolitik</h1>
                <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
                <p class="legal-sub">Her kan du hurtigt få overblik over, hvilke oplysninger vi behandler, hvorfor vi gør det, og hvilke rettigheder du har. Vi har skrevet det kort, klart og uden unødigt juridisk støj.</p>
            </div>
            <div class="legal-facts">
                <div class="legal-fact-card">
                    <span>Dataansvarlig</span>
                    <strong>Mercantec er ansvarlig for behandlingen af personoplysninger i TaskM8.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Formål</span>
                    <strong>Vi bruger kun oplysninger for at drive, beskytte og forbedre tjenesten.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Dine rettigheder</span>
                    <strong>Du kan bede om indsigt, rettelse, sletning og i nogle tilfælde begrænsning.</strong>
                </div>
            </div>
        </section>

        <div class="legal-layout">
            <aside class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>På denne side</h2>
                <nav class="legal-toc-links">
                    <a href="#ansvar">Hvem er ansvarlig?</a>
                    <a href="#oplysninger">Hvilke oplysninger bruger vi?</a>
                    <a href="#deling">Hvem deler vi med?</a>
                    <a href="#opbevaring">Hvor længe gemmer vi data?</a>
                    <a href="#rettigheder">Dine rettigheder</a>
                    <a href="#sikkerhed">Sådan beskytter vi data</a>
                    <a href="#kontakt">Kontakt</a>
                </nav>
            </aside>

            <div class="legal-content">
                <section class="legal-section" id="ansvar">
                    <h2>Hvem er ansvarlig?</h2>
                    <p>Mercantec er dataansvarlig for TaskM8. Det betyder, at vi bestemmer hvilke personoplysninger der behandles, til hvilke formål og med hvilke sikkerhedsforanstaltninger.</p>
                </section>

                <section class="legal-section" id="oplysninger">
                    <h2>Hvilke oplysninger bruger vi?</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>Kontooplysninger</strong>
                            <span>Navn, e-mail og loginoplysninger bruges til at oprette din konto, give adgang og sende nødvendige beskeder.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Brugs- og sikkerhedsdata</strong>
                            <span>Vi registrerer relevante hændelser som login, aktivitet og fejl for at beskytte tjenesten og forbedre stabiliteten.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Begivenheds- og planlægningsdata</strong>
                            <span>Oplysninger om begivenheder, deltagere, opgaver og vagter bruges for at få TaskM8 til at fungere som planlægningsværktøj.</span>
                        </div>
                    </div>
                    <p>Behandlingen sker typisk for at opfylde vores aftale med dig og ud fra en legitim interesse i at drive en sikker og brugbar tjeneste.</p>
                </section>

                <section class="legal-section" id="deling">
                    <h2>Hvem deler vi oplysninger med?</h2>
                    <p>Vi deler kun oplysninger med nødvendige samarbejdspartnere som hosting-, drifts- og e-mailleverandører. De fungerer som databehandlere og må kun behandle data efter vores instrukser.</p>
                    <div class="legal-note">Vi sælger ikke dine personoplysninger og bruger dem ikke til skjult profilering.</div>
                </section>

                <section class="legal-section" id="opbevaring">
                    <h2>Hvor længe gemmer vi oplysninger?</h2>
                    <p>Vi gemmer kun oplysninger så længe det er nødvendigt for drift, sikkerhed, support og lovmæssige forpligtelser. Når oplysninger ikke længere er nødvendige, slettes eller anonymiseres de.</p>
                </section>

                <section class="legal-section" id="rettigheder">
                    <h2>Dine rettigheder</h2>
                    <ul class="legal-list">
                        <li>Du kan bede om indsigt i hvilke oplysninger vi har om dig.</li>
                        <li>Du kan bede om at få rettet forkerte eller ufuldstændige oplysninger.</li>
                        <li>Du kan i visse tilfælde bede om sletning eller begrænsning af behandlingen.</li>
                        <li>Du kan gøre indsigelse mod behandling, når reglerne giver dig ret til det.</li>
                        <li>Du kan anmode om dataportabilitet for oplysninger, du selv har givet.</li>
                    </ul>
                    <p>Hvis du vil bruge en rettighed, kan du kontakte os. Du har også ret til at klage til Datatilsynet.</p>
                </section>

                <section class="legal-section" id="sikkerhed">
                    <h2>Sådan beskytter vi dine data</h2>
                    <p>Vi anvender passende tekniske og organisatoriske sikkerhedsforanstaltninger. Det omfatter blandt andet adgangsstyring, overvågning, begrænsning af adgang og relevante sikkerhedsprocedurer i driften.</p>
                </section>

                <section class="legal-section" id="kontakt">
                    <h2>Kontakt</h2>
                    <p>Hvis du har spørgsmål til privatliv eller behandling af personoplysninger i TaskM8, kan du kontakte Mercantec via kontaktoplysningerne på hjemmesiden.</p>
                </section>
            </div>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>


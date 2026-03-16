<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Cookiepolitik | TaskM8';
        $metaDescription = 'Læs hvordan TaskM8 bruger cookies og lignende teknologier.';
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
                <h1 class="legal-title">Cookiepolitik</h1>
                <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
                <p class="legal-sub">Denne side forklarer enkelt hvilke cookies vi bruger, hvorfor vi bruger dem, og hvad du selv kan styre. Ingen lange forklaringer, kun det du faktisk har brug for at vide.</p>
            </div>
            <div class="legal-facts">
                <div class="legal-fact-card">
                    <span>Type</span>
                    <strong>Vi bruger primært nødvendige cookies for at få TaskM8 til at fungere korrekt.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Formål</span>
                    <strong>Cookies hjælper blandt andet med login, sikkerhed og lagring af samtykke.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Kontrol</span>
                    <strong>Du kan altid slette eller blokere cookies i din browser.</strong>
                </div>
            </div>
        </section>

        <div class="legal-layout">
            <aside class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>På denne side</h2>
                <nav class="legal-toc-links">
                    <a href="#hvad-er-cookies">Hvad er cookies?</a>
                    <a href="#hvilke-cookies">Hvilke cookies bruger vi?</a>
                    <a href="#samtykke">Samtykke</a>
                    <a href="#opbevaring">Hvor længe gemmes de?</a>
                    <a href="#styring">Sådan styrer du cookies</a>
                    <a href="#kontakt">Kontakt</a>
                </nav>
            </aside>

            <div class="legal-content">
                <section class="legal-section" id="hvad-er-cookies">
                    <h2>Hvad er cookies?</h2>
                    <p>Cookies er små tekstfiler, der gemmes på din enhed, når du besøger en hjemmeside. De bruges typisk til at huske indstillinger, holde dig logget ind og sikre at siden fungerer som forventet.</p>
                </section>

                <section class="legal-section" id="hvilke-cookies">
                    <h2>Hvilke cookies bruger vi?</h2>
                    <div class="legal-table">
                        <div class="legal-table-row">
                            <strong>Nødvendige cookies</strong>
                            <span>Bruges til grundlæggende funktioner som login, sessionshåndtering, sikkerhed og lagring af cookievalg. Siden fungerer ikke ordentligt uden dem.</span>
                        </div>
                        <div class="legal-table-row">
                            <strong>Samtykke-cookie</strong>
                            <span>Gemmer om du har accepteret cookie-beskeden, så du ikke bliver spurgt igen ved hvert besøg.</span>
                        </div>
                    </div>
                </section>

                <section class="legal-section" id="samtykke">
                    <h2>Samtykke</h2>
                    <p>Når du klikker på accepter i vores cookie-besked, gemmer vi dit valg. Du kan altid fjerne cookies igen i din browser, hvis du vil nulstille dit samtykke.</p>
                    <div class="legal-note">Hvis du blokerer nødvendige cookies, kan dele af TaskM8 stoppe med at fungere korrekt.</div>
                </section>

                <section class="legal-section" id="opbevaring">
                    <h2>Hvor længe gemmes cookies?</h2>
                    <p>Det afhænger af formålet. Samtykke-cookien gemmes normalt i op til 24 måneder, medmindre du sletter den tidligere.</p>
                </section>

                <section class="legal-section" id="styring">
                    <h2>Sådan styrer du cookies</h2>
                    <ul class="legal-list">
                        <li>Du kan slette cookies manuelt i din browser.</li>
                        <li>Du kan blokere cookies helt eller delvist via browserindstillinger.</li>
                        <li>Du kan også vælge at rydde eksisterende cookies, hvis du vil starte forfra.</li>
                    </ul>
                </section>

                <section class="legal-section" id="kontakt">
                    <h2>Kontakt</h2>
                    <p>Hvis du har spørgsmål til vores brug af cookies, kan du kontakte Mercantec via oplysningerne på hjemmesiden.</p>
                </section>
            </div>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>


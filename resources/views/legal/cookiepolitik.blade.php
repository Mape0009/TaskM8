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


        <div class="legal-content">
            <section class="legal-section" id="hvad-er">
                <h2>Hvad er cookies?</h2>
                <p>
                    Cookies er små tekstfiler som gemmes på din computer når du besøger en website. De hjælper hjemmesiden med at huske ting som:
                </p>
                <ul class="legal-list">
                    <li>At du er logget ind</li>
                    <li>Dine indstillinger</li>
                    <li>At siden fungerer korrekt</li>
                </ul>
                <p>
                    Cookies er ikke farlige - de kan ikke installere virus eller stele data. De er bare små instrukser.
                </p>
            </section>

            <section class="legal-section" id="hvilke">
                <h2>Hvilke cookies bruger vi?</h2>
                <div class="legal-table">
                    <div class="legal-table-row">
                        <strong>Sessioncookies</strong>
                        <span>Slettes når du lukker browseren. Bruges til at holde dig logget ind mens du bruger TaskM8.</span>
                    </div>
                    <div class="legal-table-row">
                        <strong>Sikkerhedscookie</strong>
                        <span>Beskytter din konto mod uautoriseret adgang. Vigtig for sikkerheden.</span>
                    </div>
                    <div class="legal-table-row">
                        <strong>Consentcookie</strong>
                        <span>Gemmer at du har accepteret cookies, så du ikke bliver spurgt igen hver gang.</span>
                    </div>
                </div>
                <div class="legal-note">
                    Vi bruger <strong>INGEN tracking cookies</strong>. Vi sælger ikke data til tredjeparter, og vi følger ikke hvor du går på nettet.
                </div>
            </section>

            <section class="legal-section" id="varighed">
                <h2>Hvor længe gemmes cookies?</h2>
                <ul class="legal-list">
                    <li>Sessioncookies: Slettes når du lukker TaskM8</li>
                    <li>Sikkerhedscookies: Slettes når du logger ud</li>
                    <li>Consentcookie: Gemmes i op til 12 måneder (du kan slette det når du vil)</li>
                </ul>
            </section>

            <section class="legal-section" id="kontrol">
                <h2>Sådan styrer du cookies</h2>
                <p>
                    Du har fuldstændig kontrol over cookies. I din browser kan du:
                </p>
                <ul class="legal-list">
                    <li>Slette alle cookies</li>
                    <li>Blokere nye cookies</li>
                    <li>Få advarsel hver gang en website sætter en cookie</li>
                </ul>
                <p>
                    <strong>Husk:</strong> Hvis du blokerer nødvendige cookies, kan du måske ikke logge ind på TaskM8.
                </p>
            </section>

            <section class="legal-section" id="tredjeparter">
                <h2>Tredjeparts cookies</h2>
                <p>
                    TaskM8 bruger ikke cookies fra tredjeparter. Vi bruger kun vores egne cookies til at yde tjenesten.
                </p>
            </section>

            <section class="legal-section" id="kontakt">
                <h2>Spørgsmål?</h2>
                <p>
                    Har du spørgsmål til vores cookies, kontakt Mercantec. Vi vil gerne svare.
                </p>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

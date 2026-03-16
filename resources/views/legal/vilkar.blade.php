<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Vilkår og betingelser | TaskM8';
        $metaDescription = 'Læs de generelle vilkår og betingelser for brug af TaskM8.';
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
                <h1 class="legal-title">Vilkår og betingelser</h1>
                <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
                <p class="legal-sub">Her finder du de grundlæggende regler for brug af TaskM8. Vi har skåret det ned til det vigtigste, så siden er let at forstå og hurtig at finde rundt i.</p>
            </div>
            <div class="legal-facts">
                <div class="legal-fact-card">
                    <span>Udbyder</span>
                    <strong>TaskM8 leveres af Mercantec.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Brug af tjenesten</span>
                    <strong>Når du bruger TaskM8, accepterer du de vilkår der gælder på siden.</strong>
                </div>
                <div class="legal-fact-card">
                    <span>Lovvalg</span>
                    <strong>Dansk ret gælder for brugen af tjenesten.</strong>
                </div>
            </div>
        </section>

        <div class="legal-layout">
            <aside class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>På denne side</h2>
                <nav class="legal-toc-links">
                    <a href="#anvendelse">Anvendelse</a>
                    <a href="#konto">Konto og ansvar</a>
                    <a href="#brug">Acceptabel brug</a>
                    <a href="#ansvar">Ansvarsbegrænsning</a>
                    <a href="#aendringer">Ændringer</a>
                    <a href="#lovvalg">Lovvalg og værneting</a>
                </nav>
            </aside>

            <div class="legal-content">
                <section class="legal-section" id="anvendelse">
                    <h2>Anvendelse</h2>
                    <p>TaskM8 leveres af Mercantec. Når du bruger tjenesten, accepterer du disse vilkår og betingelser.</p>
                </section>

                <section class="legal-section" id="konto">
                    <h2>Konto og ansvar</h2>
                    <p>Du er ansvarlig for at passe på dine loginoplysninger og for aktivitet, der sker via din konto. Hvis du har mistanke om misbrug, skal du kontakte os hurtigst muligt.</p>
                </section>

                <section class="legal-section" id="brug">
                    <h2>Acceptabel brug</h2>
                    <ul class="legal-list">
                        <li>Brug TaskM8 lovligt og i overensstemmelse med formålet.</li>
                        <li>Forsøg ikke at få uautoriseret adgang til systemer, data eller andre brugeres konti.</li>
                        <li>Forstyr ikke driften med misbrug, automatiserede angreb eller skadelig adfærd.</li>
                    </ul>
                </section>

                <section class="legal-section" id="ansvar">
                    <h2>Ansvarsbegrænsning</h2>
                    <p>TaskM8 stilles til rådighed som tjeneste og vi arbejder for en stabil og sikker drift. I det omfang loven tillader det, hæfter vi ikke for indirekte tab eller følgeskader som følge af brugen af tjenesten.</p>
                </section>

                <section class="legal-section" id="aendringer">
                    <h2>Ændringer</h2>
                    <p>Vi kan løbende opdatere både funktioner og vilkår. Hvis ændringer er væsentlige, varsler vi dem på rimelig måde. Fortsat brug af tjenesten efter en opdatering betyder, at du accepterer de nye vilkår.</p>
                    <div class="legal-note">Vi forsøger altid at holde vilkår enkle, opdaterede og relevante for den faktiske brug af TaskM8.</div>
                </section>

                <section class="legal-section" id="lovvalg">
                    <h2>Lovvalg og værneting</h2>
                    <p>Disse vilkår er underlagt dansk ret. Eventuelle tvister kan indbringes for de danske domstole i det omfang reglerne tillader det.</p>
                </section>
            </div>
        </div>
    </main>
    @include('partials.footer')
</body>
</html>


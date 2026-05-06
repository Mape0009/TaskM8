<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Vilkår';
        $metaDescription = 'De grundlæggende regler for at bruge TaskM8.';
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
                <div class="legal-eyebrow">Vilkår</div>
                <h1 id="legal-title" class="legal-title">Vilkår for brug af TaskM8</h1>
                <p class="legal-sub">Kort og klart: brug TaskM8 ansvarligt. Her forklarer vi dine og vores vigtigste rettigheder og pligter.</p>
            </div>
        </header>

        <div class="legal-layout">
            <nav class="legal-toc" aria-label="Indholdsfortegnelse">
                <h2>Indhold</h2>
                <div class="legal-toc-links">
                    <a href="#accept">Accept</a>
                    <a href="#konto">Din konto</a>
                    <a href="#brug">Brug</a>
                    <a href="#indhold">Dit indhold</a>
                    <a href="#ansvar">Ansvar</a>
                    <a href="#sluk-konto">Opsigelse</a>
                    <a href="#lovvalg">Lovvalg</a>
                </div>
            </nav>

            <div class="legal-content">
                <section class="legal-section" id="accept">
                    <h2>Accept</h2>
                    <p>Ved at bruge TaskM8 accepterer du disse vilkår. Vi kan tilpasse vilkårene – ændringer annonceres, og fortsat brug betyder accept.</p>
                </section>

                <section class="legal-section" id="konto">
                    <h2>Din konto</h2>
                    <ul class="legal-list">
                        <li>Du er ansvarlig for login og sikkerhed på din konto.</li>
                        <li>Hold dit kodeord sikkert og del det ikke.</li>
                        <li>Mistænker du misbrug, skift straks din adgangskode og kontakt os.</li>
                    </ul>
                </section>

                <section class="legal-section" id="brug">
                    <h2>Acceptabel brug</h2>
                    <p>Brug platformen lovligt og hensigtsmæssigt. Du må ikke misbruge systemet, angribe sikkerheden eller forstyrre andre brugere.</p>
                    <div class="legal-note">Ved groft misbrug kan vi suspendere eller lukke konti uden forudgående varsel.</div>
                </section>

                <section class="legal-section" id="indhold">
                    <h2>Dit indhold</h2>
                    <p>Du beholder ejerskabet af det indhold, du opretter. Vi har en begrænset ret til at behandle det for at levere tjenesten (opbevaring, backup, visning).</p>
                </section>

                <section class="legal-section" id="ansvar">
                    <h2>Vores ansvar</h2>
                    <p>Vi bestræber os på driftssikkerhed, men kan ikke garantere fejlfrie tjenester. I det omfang loven tillader det er vores ansvar begrænset.</p>
                </section>

                <section class="legal-section" id="sluk-konto">
                    <h2>Opsigelse</h2>
                    <p>Du kan slette din konto når som helst. Data slettes i henhold til vores politik, medmindre lovkrav eller betalinger betyder noget andet.</p>
                </section>

                <section class="legal-section" id="lovvalg">
                    <h2>Lovvalg</h2>
                    <p>Disse vilkår er underlagt dansk ret. Vi forsøger altid at løse tvister i dialog før retlige skridt.</p>
                </section>

            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


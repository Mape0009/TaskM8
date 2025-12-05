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
<body>
    @include('partials.header', ['currentPage' => null])
    <main class="main-content-full" style="max-width:900px;margin:40px auto;padding:0 16px">
        <section class="legal-hero">
            <h1 class="legal-title">Privatlivspolitik</h1>
            <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
            <p class="legal-sub">Her forklarer vi, hvilke oplysninger vi indsamler, hvorfor vi gør det, og hvordan vi passer på dem – i et let forståeligt sprog.</p>
        </section>

        <section class="legal-section">
            <h2>Hvem er ansvarlig?</h2>
            <p>Mercantec er dataansvarlig for TaskM8. Det betyder, at vi bestemmer, hvilke oplysninger der indsamles, og hvordan de bruges.</p>
        </section>

        <section class="legal-section">
            <h2>Hvilke oplysninger indsamler vi – og hvorfor?</h2>
            <ul class="legal-list">
                <li><strong>Kontaktoplysninger</strong> (fx navn og e-mail) – for at oprette din konto, sende invitationer og kvitteringer.</li>
                <li><strong>Brugsdata</strong> (fx logins og interaktioner) – for at holde tjenesten sikker og forbedre funktioner.</li>
                <li><strong>Begivenhedsdata</strong> (fx beskrivelse, tidspunkter og deltagere) – for at planlægge og dele begivenheder.</li>
            </ul>
            <p>Behandlingen sker for at opfylde vores aftale med dig og ud fra vores <em>legitime interesse</em> i at drive en sikker og stabil tjeneste.</p>
        </section>

        <section class="legal-section">
            <h2>Hvem deler vi oplysninger med?</h2>
            <p>Vi bruger betroede underleverandører (databehandlere) til drift, hosting og e-mail. De må kun behandle data efter vores instrukser og med passende sikkerhed.</p>
        </section>

        <section class="legal-section">
            <h2>Hvor længe gemmer vi oplysninger?</h2>
            <p>Kun så længe det er nødvendigt for formålene – eller som loven kræver. Herefter slettes eller anonymiseres de.</p>
        </section>

        <section class="legal-section">
            <h2>Dine rettigheder</h2>
            <ul class="legal-list">
                <li>Få <strong>indsigt</strong> i, hvilke oplysninger vi har om dig</li>
                <li>Få <strong>rettet</strong> forkerte oplysninger</li>
                <li>Få <strong>slettes</strong> oplysninger i visse tilfælde</li>
                <li><strong>Begræns</strong> eller <strong>gør indsigelse</strong> mod behandling</li>
                <li>Få <strong>dataportabilitet</strong> for oplysninger, du selv har givet</li>
            </ul>
            <p>Kontakt os for at bruge dine rettigheder. Du kan også klage til Datatilsynet.</p>
        </section>

        <section class="legal-section">
            <h2>Sådan beskytter vi dine data</h2>
            <p>Vi bruger tekniske og organisatoriske sikkerhedsforanstaltninger, herunder adgangskontrol, kryptering hvor relevant og løbende overvågning.</p>
        </section>

        <section class="legal-section">
            <h2>Kontakt</h2>
            <p>Har du spørgsmål, så kontakt Mercantec via oplysningerne på vores hjemmeside.</p>
        </section>
    </main>
    <style>
        .legal-hero{margin-bottom:22px}
        .legal-title{margin:0 0 6px 0}
        .legal-updated{color:#9ca3af;margin:0 0 8px 0}
        .legal-sub{color:#cbd5e1;margin:0 0 10px 0}
        .legal-section{background: #1e232e;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:16px 18px;margin-bottom:14px}
        .legal-section h2{margin:0 0 8px 0}
        .legal-list{margin:0 0 8px 16px}
        @media (prefers-color-scheme: light){
            .legal-updated{color:#4b5563}
            .legal-sub{color:#4b5563}
            .legal-section{background:#ffffff;border:1px solid rgba(0,0,0,0.08);box-shadow:0 6px 18px rgba(0,0,0,0.06)}
        }
    </style>
    @include('partials.footer')
</body>
</html>


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
<body>
    @include('partials.header', ['currentPage' => null])
    <main class="main-content-full" style="max-width:900px;margin:40px auto;padding:0 16px">
        <section class="legal-hero">
            <h1 class="legal-title">Vilkår og betingelser</h1>
            <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
            <p class="legal-sub">Her er de vigtigste regler for brug af TaskM8 – i et let forståeligt sprog.</p>
        </section>

        <section class="legal-section">
            <h2>Anvendelse</h2>
            <p>TaskM8 leveres af Mercantec. Når du bruger tjenesten, accepterer du disse vilkår.</p>
        </section>

        <section class="legal-section">
            <h2>Konto og ansvar</h2>
            <p>Du er ansvarlig for at beskytte dine loginoplysninger og for aktivitet via din konto. Kontakt os, hvis du mistænker misbrug.</p>
        </section>

        <section class="legal-section">
            <h2>Acceptabel brug</h2>
            <p>Brug tjenesten lovligt og respektfuldt. Forsøg på uautoriseret adgang eller forstyrrelser er ikke tilladt.</p>
        </section>

        <section class="legal-section">
            <h2>Ansvarsbegrænsning</h2>
            <p>Tjenesten leveres "som den er". I det omfang loven tillader det, hæfter vi ikke for indirekte tab.</p>
        </section>

        <section class="legal-section">
            <h2>Ændringer</h2>
            <p>Vi kan opdatere vilkår og funktioner. Væsentlige ændringer varsles rimeligt. Fortsat brug betyder, at du accepterer ændringerne.</p>
        </section>

        <section class="legal-section">
            <h2>Lovvalg og værneting</h2>
            <p>Dansk ret gælder. Tvister kan indbringes for danske domstole.</p>
        </section>
    </main>
    <style>
        .legal-hero{margin-bottom:22px}
        .legal-title{margin:0 0 6px 0}
        .legal-updated{color:#9ca3af;margin:0 0 8px 0}
        .legal-sub{color:#cbd5e1;margin:0 0 10px 0}
        .legal-section{background:#1e232e;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:16px 18px;margin-bottom:14px}
        .legal-section h2{margin:0 0 8px 0}
        @media (prefers-color-scheme: light){
            .legal-updated{color:#4b5563}
            .legal-sub{color:#4b5563}
            .legal-section{background:#ffffff;border:1px solid rgba(0,0,0,0.08);box-shadow:0 6px 18px rgba(0,0,0,0.06)}
        }
    </style>
    @include('partials.footer')
</body>
</html>


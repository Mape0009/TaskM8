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
<body>
    @include('partials.header', ['currentPage' => null])
    <main class="main-content-full" style="max-width:900px;margin:40px auto;padding:0 16px">
        <section class="legal-hero">
            <h1 class="legal-title">Cookiepolitik</h1>
            <p class="legal-updated">Senest opdateret: {{ now()->format('d.m.Y') }}</p>
            <p class="legal-sub">Her beskriver vi, hvilke cookies vi bruger, og hvad de gør – kort og forståeligt.</p>
        </section>

        <section class="legal-section">
            <h2>Hvad er cookies?</h2>
            <p>Cookies er små filer, der gemmes på din enhed. De hjælper med at få siden til at fungere korrekt og gøre oplevelsen bedre.</p>
        </section>

        <section class="legal-section">
            <h2>Hvilke cookies bruger vi?</h2>
            <ul class="legal-list">
                <li><strong>Nødvendige cookies</strong> – gør siden mulig at bruge (fx login, sikkerhed og samtykke). Siden kan ikke fungere uden dem.</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>Samtykke</h2>
            <p>Når du klikker <strong>Accepter</strong> i vores cookie-popup, gemmer vi et samtykke. Du kan altid slette cookies i din browser.</p>
        </section>

        <section class="legal-section">
            <h2>Hvor længe gemmes cookies?</h2>
            <p>Samtykke-cookien gemmes typisk i op til 24 måneder, medmindre du sletter den tidligere.</p>
        </section>

        <section class="legal-section">
            <h2>Kontakt</h2>
            <p>Har du spørgsmål, så kontakt Mercantec via oplysningerne på hjemmesiden.</p>
        </section>
    </main>
    <style>
        .legal-hero{margin-bottom:22px}
        .legal-title{margin:0 0 6px 0}
        .legal-updated{color:#9ca3af;margin:0 0 8px 0}
        .legal-sub{color:#cbd5e1;margin:0 0 10px 0}
        .legal-section{background:rgba(17,24,39,0.4);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:16px 18px;margin-bottom:14px}
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


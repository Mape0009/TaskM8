<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Om os | TaskM8';
        $metaDescription = 'Læs om TaskM8 og Mercantec. En kort og overskuelig introduktion til platformen.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Om os | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <style>
        .about-shell {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px 16px;
        }

        .about-card {
            background: var(--color-background-secondary, #ffffff);
            border: 1px solid var(--color-border, #dbe1ea);
            border-radius: 12px;
            padding: 22px;
        }

        .about-card h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2.6vw, 2rem);
            color: var(--color-text-primary, #0f172a);
        }

        .about-card p {
            margin: 12px 0 0;
            line-height: 1.65;
            color: var(--color-text-secondary, #334155);
        }

        .about-grid {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .about-item {
            border: 1px solid var(--color-border, #dbe1ea);
            border-radius: 10px;
            padding: 12px;
            background: var(--color-background-primary, #f8fafc);
        }

        .about-item h2 {
            margin: 0;
            font-size: 1rem;
            color: var(--color-text-primary, #0f172a);
        }

        .about-item p {
            margin: 6px 0 0;
            font-size: 0.95rem;
        }

        @media (max-width: 700px) {
            .about-grid {
                grid-template-columns: 1fr;
            }

            .about-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
@include('partials.header', ['currentPage' => 'dashboard'])

<main class="main-content-full">
    <div class="about-shell">
        <section class="about-card">
            <h1>Om os</h1>
            <p>
                TaskM8 er udviklet i samarbejde med Mercantec for at gøre planlægning af begivenheder enkel,
                stabil og overskuelig. Vi fokuserer på klare arbejdsgange, tydelige lister og et professionelt design,
                så brugerne hurtigt kan finde det, de har brug for.
            </p>
            <p>
                Platformen bruges til at håndtere begivenheder, deltagere, opgaver og vagter ét sted. Målet er at
                spare tid i hverdagen og gøre koordinering lettere for både arrangører og deltagere.
            </p>

            <div class="about-grid">
                <article class="about-item">
                    <h2>Vores fokus</h2>
                    <p>Brugervenlighed, tydelige data og høj driftssikkerhed.</p>
                </article>
                <article class="about-item">
                    <h2>Hvem står bag</h2>
                    <p>Mercantec i samarbejde med TaskM8-teamet.</p>
                </article>
                <article class="about-item">
                    <h2>For hvem</h2>
                    <p>Skoler, foreninger og teams, der skal koordinere aktiviteter.</p>
                </article>
                <article class="about-item">
                    <h2>Kontakt</h2>
                    <p>Se Mercantecs kontaktoplysninger på den officielle hjemmeside.</p>
                </article>
            </div>
        </section>
    </div>
</main>

@include('partials.footer')
</body>
</html>

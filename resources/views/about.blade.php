<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Om os';
        $metaDescription = 'TaskM8 - En platform til planlægning af begivenheder, opgavehåndtering og vagtplanlægning. Skabt af Mercantec for en moderne og gratis løsning.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --neutral-50: #f9fafb;
            --neutral-100: #f3f4f6;
            --neutral-200: #e5e7eb;
            --neutral-300: #d1d5db;
            --neutral-400: #9ca3af;
            --neutral-600: #4b5563;
            --neutral-900: #111827;
        }

        .dark-mode {
            --neutral-50: #0f172a;
            --neutral-100: #1e293b;
            --neutral-200: #334155;
            --neutral-300: #475569;
            --neutral-400: #64748b;
            --neutral-600: #cbd5e1;
            --neutral-900: #f1f5f9;
        }

        body {
            background: var(--neutral-50);
        }

        /* Hero Section */
        .about-hero {
            position: relative;
            overflow: hidden;
            padding: 64px 20px 88px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }

        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .about-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(118, 75, 162, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .hero-eyebrow {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            font-weight: 800;
            line-height: 1.2;
            color: var(--neutral-900);
            margin: 0 0 24px;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: var(--neutral-600);
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto 32px;
            font-weight: 400;
        }

        /* Main Content */
        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            margin-bottom: 88px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-label {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            display: block;
        }

        .section-title {
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 700;
            color: var(--neutral-900);
            margin: 0 0 16px;
            letter-spacing: -0.01em;
        }

        .section-description {
            font-size: 1.0625rem;
            color: var(--neutral-600);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Values Grid */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 32px;
            margin-top: 40px;
            align-items: stretch;
        }

        .value-card {
            padding: 32px 28px;
            background: var(--neutral-100);
            border-radius: 16px;
            border: 1px solid var(--neutral-200);
            position: relative;
        }

        .dark-mode .value-card {
            background: rgba(30, 41, 59, 0.5);
            border-color: var(--neutral-300);
        }

        .value-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .value-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin: 0 0 12px;
        }

        .value-description {
            font-size: 0.9375rem;
            color: var(--neutral-600);
            line-height: 1.6;
            margin: 0;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 36px;
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            gap: 18px;
        }

        .feature-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            flex-shrink: 0;
            line-height: 1;
        }

        .feature-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin: 0 0 8px;
        }

        .feature-content p {
            font-size: 0.9375rem;
            color: var(--neutral-600);
            line-height: 1.6;
            margin: 0;
        }

        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 32px;
            margin-top: 40px;
        }

        .team-member {
            text-align: center;
        }

        .member-avatar {
            width: 140px;
            height: 140px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        .member-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--neutral-900);
            margin: 0 0 4px;
        }

        .member-role {
            font-size: 0.9375rem;
            color: var(--neutral-600);
            margin: 0;
        }

        /* Call-to-Action Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 64px 36px;
            text-align: center;
            margin-top: 88px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 700;
            color: white;
            margin: 0 0 16px;
            letter-spacing: -0.01em;
        }

        .cta-description {
            font-size: 1.0625rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0 0 32px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            padding: 16px 40px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 48px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.9375rem;
            color: var(--neutral-600);
            font-weight: 600;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .about-hero {
                padding: 52px 20px 68px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .section {
                margin-bottom: 64px;
            }

            .section-header {
                margin-bottom: 32px;
            }

            .values-grid {
                gap: 20px;
            }

            .value-card {
                padding: 28px 22px;
            }

            .features-grid {
                gap: 28px;
            }

            .feature-item {
                gap: 14px;
            }

            .feature-number {
                font-size: 2rem;
            }

            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 24px;
            }

            .cta-section {
                padding: 52px 22px;
                margin-top: 64px;
            }

            .cta-title {
                font-size: 1.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
        }

        @media (max-width: 480px) {
            .about-hero {
                padding: 36px 16px 52px;
            }

            .hero-eyebrow {
                font-size: 0.75rem;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .hero-subtitle {
                font-size: 0.9375rem;
            }

            .values-grid {
                grid-template-columns: 1fr;
            }

            .value-card {
                padding: 22px 18px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .cta-section {
                padding: 36px 16px;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
@include('partials.header', ['currentPage' => 'dashboard'])

<main class="main-content-full">

    <div class="about-container">
        <!-- Mission & Vision Section -->
        <section class="section">
            <div class="section-header">
                <span class="section-label">Vores Mission</span>
                <h2 class="section-title">Styr på begivenheden</h2>
                <p class="section-description">TaskM8 er udviklet med formål at gøre planlægning af begivenheder enkel, stabil og overskuelig. Vi fokuserer på klare arbejdsgange, tydelige lister og godt design, så brugerne hurtigt kan finde det, de har brug for.</p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">✨</div>
                    <h3 class="value-title">Vores Fokus</h3>
                    <p class="value-description">Brugervenlighed, tydelig data og høj driftsikkerhed</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">👥</div>
                    <h3 class="value-title">Hvem Står Bag</h3>
                    <p class="value-description">TaskM8-teamet består af elever fra Mercantec Skoleoplæring Viborg</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">📞</div>
                    <h3 class="value-title">Kontakt</h3>
                    <p class="value-description">Se Mercantecs kontaktoplysninger på den officielle hjemmeside.</p>
                </div>

              </main>

@include('partials.footer')
</body>
</html>

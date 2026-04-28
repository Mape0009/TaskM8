<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Privatlivspolitik';
        $metaDescription = 'Sådan behandler TaskM8 dine personoplysninger.';
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
            <section class="legal-section" id="hvad-indsamler-vi">
                <h2>Hvilke data indsamler vi?</h2>
                <div class="legal-table">
                    <div class="legal-table-row">
                        <strong>Kontodata</strong>
                        <span>Dit navn, e-mail og loginoplysninger skal til for, at du kan få adgang til TaskM8.</span>
                    </div>
                    <div class="legal-table-row">
                        <strong>Dit indhold</strong>
                        <span>Informationer om begivenheder, opgaver, vagter og deltagere som du selv udfylder for at bruge platformen.</span>
                    </div>
                </div>
            </section>

            <section class="legal-section" id="hvorfor-bruger-vi-det">
                <h2>Hvorfor bruger vi dine data?</h2>
                <ul class="legal-list">
                    <li>For at du kan oprette en konto og logge ind</li>
                    <li>For at gemme dine begivenheder, opgaver og vagter</li>
                    <li>For at holde platformen sikker og fungerende</li>
                    <li>For at kontakte dig hvis der er problemer</li>
                </ul>
                <div class="legal-note">
                    Vi bruger <strong>IKKE</strong> dine data til at sælge, dele med tredjepart (undtagen nødvendige driftsleverandører) eller til at profilere dig.
                </div>
            </section>

            <section class="legal-section" id="hvor-længe">
                <h2>Hvor længe gemmer vi data?</h2>
                <p>
                    Vi gemmer kun data så længe det er nødvendigt. Når du sletter din konto, fjerner vi dine personlige oplysninger. 
                
                </p>
            </section>

            <section class="legal-section" id="dine-rettigheder">
                <h2>Dine rettigheder</h2>
                <ul class="legal-list">
                    <li><strong>Indsigt:</strong> Du kan bede om at se hvad vi har gemt om dig</li>
                    <li><strong>Rettelse:</strong> Du kan få ændret forkerte oplysninger</li>
                    <li><strong>Sletning:</strong> Du kan bede om at få dine data slettet</li>
                    <li><strong>Dataportabilitet:</strong> Du kan få dine data i et format du kan bruge andetsteds</li>
                </ul>
                <p>
                    <strong>Vil du bruge en af dine rettigheder?</strong> Kontakt Mercantec på deres kontaktoplysninger.
                </p>
            </section>

            <section class="legal-section" id="sikkerhed">
                <h2>Hvordan beskytter vi dine data?</h2>
                <p>
                    Vi bruger moderne sikkerhed som kryptering, sikker login (SSL) og begrænset adgang. 
                    Kun vigtige medarbejdere kan se dine data, og de underskriver altid fortrolighedsaftaler.
                </p>
            </section>

            <section class="legal-section" id="kontakt">
                <h2>Kontakt</h2>
                <p>
                    Har du spørgsmål til hvordan vi håndterer data? Kontakt Mercantec. 
                    Du kan også klage til Datatilsynet hvis du mener vi behandler dine data uhensigtsmæssigt.
                </p>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


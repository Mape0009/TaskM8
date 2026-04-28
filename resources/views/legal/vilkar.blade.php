<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Vilkår og betingelser | TaskM8';
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

        <div class="legal-content">
            <section class="legal-section" id="accept">
                <h2>Ved brug af TaskM8 accepterer du</h2>
                <p>
                    Når du bruger TaskM8, accepterer du disse vilkår. Hvis du ikke er enig, skal du ikke bruge tjenesten. 
                    Vi er berettiget til at ændre vilkårene, og hvis vi gør det, varsler vi dig. Fortsat brug betyder at du accepterer de nye vilkår.
                </p>
            </section>

            <section class="legal-section" id="konto">
                <h2>Din Konto</h2>
                <ul class="legal-list">
                    <li>Du er ansvarlig for at holde dit password hemmeligt</li>
                    <li>Du er ansvarlig for alt hvad der sker via din konto</li>
                <li>Hvis du tror nogen bruger din konto uden tilladelse, anbefaler vi, at du ændrer din adgangskode</li>
                </ul>
            </section>

            <section class="legal-section" id="brug">
                <h2>Sådan bruger du TaskM8</h2>
                <p>Du skal bruge TaskM8 lovligt og på hensigtsmæssig måde. Det betyder:</p>
                <ul class="legal-list">
                    <li>Ingen forsøg på at hacke, afprøve sikkerhed eller få uautoriseret adgang</li>
                    <li>Ingen bots, spam eller automater der overbelaster systemet</li>
                    <li>Ingen forsøg på at skade, forstyrre eller udnytte platformen</li>
                    <li>Respekt for andre brugeres rettigheder og data</li>
                </ul>
                <div class="legal-note">
                    Hvis vi opdager misbrug, kan vi suspendere eller lukke din konto uden varsel.
                </div>
            </section>

            <section class="legal-section" id="indhold">
                <h2>Dit Indhold</h2>
                <p>
                    Du ejer dit indhold (begivenheder, opgaver, osv.). Vi har rettigheder til at bruge det for at yde tjenesten 
                    (f.eks. at gemme det, sikkerhedskopiere det, og vise det til dig).
                </p>
            </section>

            <section class="legal-section" id="ansvar">
                <h2>Vores Ansvar</h2>
                <p>
                    Vi stiller TaskM8 til rådighed og forsøger at gøre det sikkert og stabilt. Vi kan dog ikke garantere, at det 
                    altid fungerer perfekt, at der ikke er fejl, eller at det passer til dine særlige behov.
                </p>
                <p>
                    <strong>I det omfang loven tillader det:</strong> Vi hæfter ikke for indirekte skade eller tab, selv hvis vi blev advaret på forhånd.
                </p>
            </section>

            <section class="legal-section" id="sluk-konto">
                <h2>Hvis du vil stoppe</h2>
                <p>
                    Du kan slette din konto når som helst. Når du gør det, slettes dine data efter kort tid. 
                    Hvis du skylder penge (f.eks. for premium), skal det være betalt først.
                </p>
            </section>

            <section class="legal-section" id="lovvalg">
                <h2>Lovvalg</h2>
                <p>
                    Disse vilkår er underlagt dansk ret. Hvis der opstår uenighed, forsøger vi at løse det i dialog. 
                    Hvis det ikke lykkes, kan sagen gå til de danske domstole.
                </p>
            </section>

            <section class="legal-section" id="kontakt">
                <h2>Spørgsmål?</h2>
                <p>
                    Har du spørgsmål til vilkårene? Kontakt Mercantec. 
                    Vi svarer gerne på dine henvendelser.
                </p>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>


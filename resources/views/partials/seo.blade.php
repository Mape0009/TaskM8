@php
    $appName = 'TaskM8';
    $defaultTitle = $title ?? ($pageTitle ?? ($metaTitle ?? 'TaskM8 – Planlæg og saml begivenheder'));
    $defaultDescription = $description ?? ($metaDescription ?? 'TaskM8 gør det nemt at planlægge, invitere og holde styr på begivenheder – alt samlet ét sted.');
    $defaultUrl = $canonical ?? url()->current();
    $defaultImage = $image ?? asset('TaskM8-Logo.png');
    $robots = $robots ?? 'index, follow';
    $locale = app()->getLocale() ?: 'da';
    $ogLocale = match ($locale) {
        'en' => 'en_US',
        'es' => 'es_ES',
        default => 'da_DK',
    };
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $defaultTitle }}</title>
<meta name="description" content="{{ $defaultDescription }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $defaultUrl }}">
<meta name="theme-color" content="#111827">

<!-- Open Graph -->
<meta property="og:locale" content="{{ $ogLocale }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $appName }}">
<meta property="og:title" content="{{ $defaultTitle }}">
<meta property="og:description" content="{{ $defaultDescription }}">
<meta property="og:url" content="{{ $defaultUrl }}">
<meta property="og:image" content="{{ $defaultImage }}">
<meta property="og:image:alt" content="{{ $appName }}">

<!-- Resource Hints -->
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Favicons -->
<link rel="icon" href="{{ asset('favicon.ico') }}">

<!-- JSON-LD Organization -->
@php
    $orgJson = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $appName,
        'url' => config('app.url') ?? url('/'),
        'logo' => asset('TaskM8-Logo.png'),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($orgJson, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>

@isset($structuredData)
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endisset

<!-- Preload critical assets -->
<link rel="preload" as="style" href="{{ asset('css/header.css') }}">
<link rel="preload" as="style" href="{{ asset('css/dashboard.css') }}">
<link rel="preload" as="style" href="{{ asset('css/event.css') }}">
<link rel="preload" as="style" href="{{ asset('css/design-system.css') }}">
<link rel="preload" as="image" href="{{ asset('TaskM8-Logo.png') }}">
<link rel="preload" as="image" href="{{ asset('TaskM8-Logo-Dark.png') }}">

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.guide_page_title');
        $metaDescription = __('ui.guide_meta');

        $guideSections = [
            [
                'id' => 'oprettelse-af-bruger',
                'number' => 1,
                'title' => __('ui.guide_user_title'),
                'text' => __('ui.guide_user_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>',
            ],
            [
                'id' => 'oversigt',
                'number' => 2,
                'title' => __('ui.guide_overview_title'),
                'text' => __('ui.guide_overview_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>',
            ],
            [
                'id' => 'begivenheder',
                'number' => 3,
                'title' => __('ui.guide_events_title'),
                'text' => __('ui.guide_events_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
            ],
            [
                'id' => 'opgaver',
                'number' => 4,
                'title' => __('ui.guide_tasks_title'),
                'text' => __('ui.guide_tasks_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
            ],
            [
                'id' => 'vagter',
                'number' => 5,
                'title' => __('ui.guide_shifts_title'),
                'text' => __('ui.guide_shifts_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>',
            ],
            [
                'id' => 'frivillig',
                'number' => 6,
                'title' => __('ui.guide_volunteer_title'),
                'text' => __('ui.guide_volunteer_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
            ],
            [
                'id' => 'tidligere-begivenheder',
                'number' => 7,
                'title' => __('ui.guide_previous_title'),
                'text' => __('ui.guide_previous_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
            ],
            [
                'id' => 'grupper',
                'number' => 8,
                'title' => __('ui.guide_groups_title'),
                'text' => __('ui.guide_groups_text'),
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
            ],
            [
                'id' => 'indstillinger',
                'number' => 9,
                'title' => __('ui.guide_settings_title'),
                'text' => null,
                'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>',
                'subitems' => [
                    [
                        'title' => __('ui.guide_settings_notifications_title'),
                        'text' => __('ui.guide_settings_notifications_text'),
                        'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>',
                    ],
                    [
                        'title' => __('ui.guide_settings_password_title'),
                        'text' => __('ui.guide_settings_password_text'),
                        'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>',
                    ],
                    [
                        'title' => __('ui.guide_settings_account_title'),
                        'text' => __('ui.guide_settings_account_text'),
                        'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>',
                    ],
                ],
            ],
        ];

        foreach ($guideSections as &$section) {
            $searchParts = [
                $section['title'],
                $section['text'] ?? '',
            ];

            foreach ($section['subitems'] ?? [] as $subitem) {
                $searchParts[] = $subitem['title'] ?? '';
                $searchParts[] = $subitem['text'] ?? '';
            }

            $section['search'] = mb_strtolower(trim(preg_replace('/\s+/u', ' ', implode(' ', $searchParts))));
        }
        unset($section);
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <link rel="stylesheet" href="{{ asset('css/legal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guide.css') }}">
    <style>
        .guide-shell .legal-hero {
            padding: 28px 28px 30px;
            margin-bottom: 32px;
            gap: 14px;
        }

        .guide-shell .legal-eyebrow {
            display: none;
        }

        .guide-shell .legal-title {
            font-size: clamp(1.8rem, 3vw, 2.2rem);
        }

        .guide-shell .legal-sub {
            max-width: 58ch;
        }

        .guide-search-wrap,
        .guide-search,
        .guide-search-icon,
        .guide-search-empty {
            display: none;
        }

        @media (max-width: 768px) {
            .guide-shell .legal-hero {
                padding: 22px 20px 24px;
                margin-bottom: 24px;
            }
        }
    </style>
</head>
<body>
    @include('partials.header', ['currentPage' => null])

    <main class="legal-shell guide-shell">
        <header class="legal-hero" aria-labelledby="guide-title">
            <div class="legal-hero-head">
                <h1 id="guide-title" class="legal-title">{{ __('ui.guide_eyebrow') }}</h1>
                <p class="legal-sub">{{ __('ui.guide_sub') }}</p>
            </div>
        </header>

        <div class="guide-layout">
            <nav class="guide-toc" aria-label="{{ __('ui.guide_toc') }}">
                <h2>{{ __('ui.guide_toc') }}</h2>
                <div class="guide-toc-links">
                    @foreach ($guideSections as $section)
                        <a href="#{{ $section['id'] }}">{{ $section['title'] }}</a>
                    @endforeach
                </div>
            </nav>

            <div class="guide-content" id="guide-content">
                @foreach ($guideSections as $section)
                    <section
                        class="guide-section"
                        id="{{ $section['id'] }}"
                        aria-labelledby="guide-heading-{{ $section['id'] }}"
                        data-guide-search="{{ $section['search'] }}"
                    >
                        <div class="guide-section__icon" aria-hidden="true">
                            {!! $section['icon'] !!}
                        </div>
                        <div class="guide-section__body">
                            <span class="guide-section__number" aria-hidden="true">{{ $section['number'] }}</span>
                            <h2 id="guide-heading-{{ $section['id'] }}">{{ $section['title'] }}</h2>

                            @if (!empty($section['text']))
                                <p>{{ $section['text'] }}</p>
                            @endif

                            @if (!empty($section['subitems']))
                                <ul class="guide-sublist">
                                    @foreach ($section['subitems'] as $subitem)
                                        <li>
                                            <span class="guide-sublist__icon" aria-hidden="true">{!! $subitem['icon'] !!}</span>
                                            <div>
                                                <strong>{{ $subitem['title'] }}</strong>
                                                <span>{{ $subitem['text'] }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </section>
                @endforeach

            </div>
        </div>
    </main>

    <button
        type="button"
        id="guide-back-to-top"
        class="guide-back-to-top"
        aria-label="{{ __('ui.guide_back_to_top') }}"
        hidden
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75 12 8.25l7.5 7.5"/>
        </svg>
    </button>

    @include('partials.footer')

    <script src="{{ asset('js/guide.js') }}" defer></script>
</body>
</html>

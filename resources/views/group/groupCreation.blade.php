<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.group_create_page_title') }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupCreation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>

<body>
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="main-content-full">
        <section class="group-create-shell form-card">
            <header class="form-header">
                <div>
                    <p class="eyebrow">{{ __('ui.group_new') }}</p>
                    <h1>{{ __('ui.group_create_title') }}</h1>
                    <p class="lede">{{ __('ui.group_create_intro') }}</p>
                </div>
                <a href="{{ url('/groups/overview') }}" class="ghost-link">{{ __('ui.back_to_overview') }}</a>
            </header>

            <form class="group-form" action="{{ route('groups.create') }}" method="POST">
                @csrf

                <div class="form-row">
                    <label for="groupName">{{ __('ui.group_name') }}</label>
                    <p class="helper">{{ __('ui.group_name_helper') }}</p>
                    <input type="text" id="groupName" name="groupName" placeholder="{{ __('ui.group_name_placeholder') }}" required>
                </div>

                <div class="form-row">
                    <label for="description">{{ __('ui.description') }}</label>
                    <p class="helper">{{ __('ui.group_description_helper') }}</p>
                    <div class="textarea-wrap">
                        <textarea id="description" name="description" placeholder="{{ __('ui.group_description_placeholder') }}"></textarea>
                        <span class="counter" id="description-counter">0 / 240</span>
                    </div>
                </div>

                <div class="form-row visibility-row">
                    <div>
                        <label for="private">{{ __('ui.visibility') }}</label>
                        <p class="helper">{{ __('ui.group_visibility_helper') }}</p>
                    </div>
                    <label class="toggle">
                        <input type="hidden" name="private" value="0">
                        <input type="checkbox" id="private" name="private" value="1">
                        <span class="toggle-track">
                            <span class="toggle-thumb"></span>
                        </span>
                        <span class="toggle-label" data-off="{{ __('ui.group_public') }}" data-on="{{ __('ui.group_private') }}">{{ __('ui.group_public') }}</span>
                    </label>
                </div>

                <div class="form-actions">
                    <a href="{{ url('/groups/overview') }}" class="btn secondary-btn">{{ __('ui.cancel') }}</a>
                    <button type="submit" class="btn primary-btn">{{ __('ui.group_create_btn') }}</button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const description = document.querySelector('#description');
            const counter = document.querySelector('#description-counter');
            const maxLength = 240;
            const toggleInput = document.querySelector('#private');
            const toggleLabel = document.querySelector('.toggle-label');

            if (description && counter) {
                const updateCount = () => {
                    const length = description.value.length;
                    counter.textContent = `${length} / ${maxLength}`;
                };
                description.setAttribute('maxlength', maxLength);
                description.addEventListener('input', updateCount);
                updateCount();
            }

            if (toggleInput && toggleLabel) {
                const updateToggleLabel = () => {
                    toggleLabel.textContent = toggleInput.checked
                        ? toggleLabel.dataset.on
                        : toggleLabel.dataset.off;
                };
                toggleInput.addEventListener('change', updateToggleLabel);
                updateToggleLabel();
            }
        });
    </script>
</body>
</html>
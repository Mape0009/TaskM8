<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Creation | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupCreation.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="group-create-shell">
        <section class="form-card">
            <header class="form-header">
                <div>
                    <p class="eyebrow">Ny gruppe</p>
                    <h1>Opret en gruppe</h1>
                    <p class="lede">Navngiv gruppen, tilføj en kort beskrivelse og vælg synlighed.</p>
                </div>
                <a href="{{ url('/groups/overview') }}" class="ghost-link">Tilbage til oversigten</a>
            </header>

            <form class="group-form" action="{{ route('groups.create') }}" method="POST">
                @csrf

                <div class="form-row">
                    <label for="groupName">Gruppenavn</label>
                    <p class="helper">Et klart navn gør det let at finde gruppen senere.</p>
                    <input type="text" id="groupName" name="groupName" placeholder="Fx 'Vagtplan Team Nord'" required>
                </div>

                <div class="form-row">
                    <label for="description">Beskrivelse</label>
                    <p class="helper">Beskriv kort formålet med gruppen.</p>
                    <div class="textarea-wrap">
                        <textarea id="description" name="description" placeholder="Hvad samler gruppen, hvilke aktiviteter osv."></textarea>
                        <span class="counter" id="description-counter">0 / 240</span>
                    </div>
                </div>

                <div class="form-row visibility-row">
                    <div>
                        <label for="private">Synlighed</label>
                        <p class="helper">Privat skjuler gruppen for andre end inviterede medlemmer.</p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" id="private" name="private">
                        <span class="toggle-track">
                            <span class="toggle-thumb"></span>
                        </span>
                        <span class="toggle-label" data-off="Offentlig" data-on="Privat">Offentlig</span>
                    </label>
                </div>

                <div class="form-actions">
                    <a href="{{ url('/groups/overview') }}" class="btn secondary-btn">Annuller</a>
                    <button type="submit" class="btn primary-btn">Opret gruppe</button>
                </div>
            </form>
        </section>
    </main>

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
@include('shared.html')
@include('shared.head', ['pageTitle' => 'Zawodnicy spełniający wymagania'])

<body>
    @include('shared.navbar')

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        @include('shared.session-error')
        @include('shared.validation-error')

        <h1 class="mb-3">Zapisz sportowców na wydarzenie</h1>

        <h3 class="mb-3">Wybrane wydarzenie</h3>

<p class="mb-1">
    <strong>Nazwa wydarzenia:</strong>
    {{ $event->name }}
</p>

<p class="mb-1">
    <strong>Data:</strong>
    {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}
</p>

<p class="mb-1">
    <strong>Wymagana kategoria:</strong>
    {{ $event->requiredCategory->name }}
</p>

<p class="mb-1">
    <strong>Przedział wiekowy:</strong>
    {{ $event->age_from }} – {{ $event->age_to }} lat
</p>


        {{-- Miejsce na komunikat błędu --}}
        <div id="athletes-error" class="alert alert-danger w-100 text-center mb-3" style="display: none;"></div>

        <form action="{{ route('admin.event-user.store') }}" method="POST" class="mt-4" id="athletesForm">
            @csrf
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <input type="hidden" name="event_id" value="{{ $event->event_id }}">

                        <h4 class="text-center">
                            Zawodnicy spełniający jednocześnie wymagania kategorii oraz wieku:
                        </h4>

                        @if($athletes->isEmpty())
                            <p>Brak zawodników spełniających jednocześnie wymagania kategorii oraz wieku</p>
                        @else
                            @foreach($athletes as $athlete)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="user_id[]" value="{{ $athlete->user_id }}" 
                                           id="athlete_{{ $athlete->user_id }}">
                                    <label class="form-check-label" for="athlete_{{ $athlete->user_id }}">
                                        {{ $athlete->name }} {{ $athlete->surname }} 
                                        (Kategoria: {{ $athlete->category->name }}, 
                                        wiek: {{ \Carbon\Carbon::parse($athlete->birthdate)->age }})
                                    </label>
                                </div>
                            @endforeach
                        @endif

                        <button type="submit" class="btn btn-primary w-100 mt-3">Zapisz wybranych zawodników</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="container mt-3">
            <div class="row">
                <div class="col text-center">
                <a href="{{ route('admin.event-user.show', $event->event_id) }}"
   class="btn btn-secondary">
    Powróć do listy uczestników wydarzenia
</a>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('athletesForm');
            const errorDiv = document.getElementById('athletes-error');

            form.addEventListener('submit', function(e) {
                const checkboxes = form.querySelectorAll('input[name="user_id[]"]');
                const checked = Array.from(checkboxes).some(cb => cb.checked);

                if (!checked) {
                    e.preventDefault();
                    errorDiv.textContent = 'Proszę wybrać przynajmniej jednego zawodnika!';
                    errorDiv.style.display = 'block';
                    errorDiv.scrollIntoView({ behavior: 'smooth' });
                } else {
                    errorDiv.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>

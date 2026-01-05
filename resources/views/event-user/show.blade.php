@include('shared.html')
@include('shared.head', ['pageTitle' => 'Uczestnicy wydarzenia'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 mb-5">
        @include('shared.session-error')
        @include('shared.validation-error')
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-3">
            <h1>Uczestnicy wydarzenia: {{ $event->name }}</h1>
            <h4>Data: {{ $event->date }}, Wymagana kategoria: {{ $event->requiredCategory->name }}</h4>
        </div>

        <div class="row mb-3">
            <div class="col text-center">
                @if($event->date > now())
                    <a href="{{ route('admin.event-user.select', $event->event_id) }}" class="btn btn-primary">
                        Zapisz nowych zawodników
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Uczestnik</th>
                        <th>Punkty</th>
                        <th>Wypisz</th>
                        <th>Przyznaj punkty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eventUsers as $eventUser)
                        <tr>
                            <td>{{ $eventUser->user->name }} {{ $eventUser->user->surname }}</td>
                            <td>{{ $eventUser->points ?? 'Brak' }}</td>
                            <td>
                                @can('remove-athlete', $eventUser)
                                <form method="POST" action="{{ route('admin.event-user.destroy', $eventUser->event_user_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Czy na pewno chcesz wypisać sportowca z wydarzenia?')">
                                        Wypisz
                                    </button>
                                </form>
                                @else
                                <span>Akcja niedostępna</span>
                                @endcan
                            </td>
                            <td>
                                @can('assign-points', $eventUser)
                                <a href="{{ route('admin.event-user.edit', $eventUser->event_user_id) }}" class="btn btn-primary">
                                    Przyznaj punkty
                                </a>
                                @else
                                <span>Akcja niedostępna</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Brak zapisanych uczestników.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-2">
                {{ $eventUsers->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Powróć do listy wydarzeń</a>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

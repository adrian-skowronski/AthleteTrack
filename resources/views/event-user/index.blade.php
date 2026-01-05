@include('shared.html')
@include('shared.head', ['pageTitle' => 'Wydarzenia z uczestnikami'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

   
        <div class="row mb-1">
            <h1>Lista wydarzeń z uczestnikami</h1>
        </div>
        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nazwa</th>
                        <th scope="col">Opis</th>
                        <th scope="col">Wymagana kategoria</th>
                        <th scope="col">Data</th>
                        <th scope="col">Uczestnik</th>
                        <th scope="col">Punkty</th>
                        <th scope="col">Wypisz sportowca</th>
                        <th scope="col">Przyznaj punkty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($eventUsers as $eventUser)
                        <tr>
                            <td>{{ $eventUser->event->name }}</td>
                            <td>{{ $eventUser->event->description }}</td>
                            <td>{{ $eventUser->event->requiredCategory->name }}</td>
                            <td>{{ $eventUser->event->date }}</td>
                            <td>{{ $eventUser->user->name }} {{ $eventUser->user->surname }}</td>
                            <td>{{ $eventUser->points ?? 'Brak' }}</td>
                            <td>
                            @can('remove-athlete', $eventUser)
                <form method="POST" action="{{ route('admin.event-user.destroy', $eventUser->event_user_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz wypisać sportowca z wydarzenia?')">Wypisz sportowca</button>
                </form>
            @else
                <span>Akcja niedostępna</span>
            @endcan
                            </td>
                            <td>
                            @can('assign-points', $eventUser)
        <a href="{{ route('admin.event-user.edit', $eventUser->event_user_id) }}" class="btn btn-primary">Przyznaj punkty</a>
    @else
        <span>Akcja niedostępna</span>
    @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Brak wydarzeń do wyświetlenia z uczestnikami.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $eventUsers->links('pagination::bootstrap-4') }}
        </div> 

        <div class="container mt-3">
            <div class="row">
                <div class="col text-center">
                    <a href="{{ route('admin.events.index') }}" class="btn btn-primary mb-2">Zapisz sportowca</a>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Powróć do panelu admina</a>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

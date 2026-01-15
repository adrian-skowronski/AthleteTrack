@include('shared.html')
@include('shared.head', ['pageTitle' => 'Wydarzenia - lista'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 mb-5">
        {{-- Wyświetlanie komunikatów sukcesu i błędów --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif
        @include('shared.session-error')
        @include('shared.validation-error')

        <div class="row mb-1">
            <h1>Lista wydarzeń</h1>
        </div>

        <div class="row mb-3 mt-3 p-3">
            <div class="col d-flex justify-content-center">
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Dodaj nowe wydarzenie</a>
            </div>
        </div>

        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nazwa</th>
                        <th scope="col">Opis</th>
                        <th scope="col">Wymagana kategoria</th>
                        <th scope="col">Wiek od</th>
                        <th scope="col">Wiek do</th>
<th scope="col" class="text-nowrap">Data</th>
                        <th scope="col">Godzina</th>
                        <th scope="col">Maks. liczba uczest.</th>
                        <th scope="col">Liczba zapisanych</th>
                        <th scope="col">Uczestnicy</th>
                        <th scope="col">Edytuj</th>
                        <th scope="col">Usuń</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->requiredCategory->name }}</td>
                            <td>{{ $event->age_from }}</td>
                            <td>{{ $event->age_to }}</td>
<td class="text-nowrap">
    {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}
</td><td>{{ \Carbon\Carbon::parse($event->start_hour)->format('H:i') }}</td>
                            <td class="text-end">{{ $event->max_participants }}</td>
                            <td class="text-end">{{ $event->users_count }}</td>
                            <td>
                                <a href="{{ route('admin.event-user.show', $event->event_id) }}" class="btn btn-primary">
                                    Lista
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-warning">Edytuj</a>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.events.destroy', $event->event_id) }}" onsubmit="return confirm('Czy na pewno chcesz usunąć?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">Brak wydarzeń do wyświetlenia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="row">
                <div class="d-flex justify-content-center mt-2">
                    {{ $events->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

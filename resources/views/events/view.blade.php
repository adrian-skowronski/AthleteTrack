@include('shared.html')
@include('shared.head', ['pageTitle' => 'Zawody - lista'])

<style>
    .col-name {
        min-width: 250px;
    }

    .col-date, .col-hour {
        min-width: 100px;
        white-space: nowrap;
    }
</style>

<body>
    @include('shared.navbar')

    <div class="container mt-3 mb-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    <div class="container mt-3 mb-5">
        <div class="row mb-2">
            <h1>Lista zawodów</h1>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="col-name">Nazwa</th>
                            <th scope="col">Opis</th>
                            <th scope="col">Kategoria</th>
                            <th scope="col">Wiek od</th>
                            <th scope="col">Wiek do</th>
                            <th scope="col" class="col-date">Data</th>
                            <th scope="col" class="col-hour">Godzina</th>
                            <th scope="col">Maks. liczba uczest.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td class="col-name">{{ $event->name }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->requiredCategory->name }}</td>
                                <td>{{ $event->age_from }}</td>
                                <td>{{ $event->age_to }}</td>
                                <td class="col-date">{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}</td>
                                <td class="col-hour">{{ \Carbon\Carbon::parse($event->start_hour)->format('H:i') }}</td>
                                <td class="text-end">{{ $event->max_participants }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Brak zawodów do wyświetlenia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2">
                {{ $events->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

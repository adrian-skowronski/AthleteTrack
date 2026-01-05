@include('shared.html')
@include('shared.head', ['pageTitle' => 'Uczestnicy treningu'])

<body>
    @include('shared.navbar')

    <div class="container mt-3 mb-5">
        <div class="row mt-3 mb-3">
            <h1>Uczestnicy treningu: {{ $training->description }} ({{ $training->date }})</h1>
        </div>

        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>E-mail</th>
                        <th>Status obecności</th>
                        <th>Punkty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participants as $participant)
                        <tr>
                            <td>{{ $participant->user->name }}</td>
                            <td>{{ $participant->user->surname }}</td>
                            <td>{{ $participant->user->email }}</td>
                            <td>
                                @php
                                    $status = $participant->status;
                                    $statusText = match($status) {
                                        'Przyszły' => 'Brak',
                                        'obecność' => 'Obecny',
                                        'nieobecność nieusprawiedliwiona' => 'Nieobecność (nieusprawiedliwiona)',
                                        'nieobecność usprawiedliwiona' => 'Nieobecność (usprawiedliwiona)',
                                        default => 'Brak',
                                    };
                                @endphp
                                {{ $statusText }}
                            </td>
                            <td>{{ $participant->points ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Brak zapisanych uczestników.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">
                {{ $participants->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col text-center">
                <a href="{{ route('admin.trainings.index') }}" class="btn btn-secondary">Powróć do listy treningów</a>
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

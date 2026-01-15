@include('shared.html')
@include('shared.head', ['pageTitle' => 'Moje Treningi'])

<body>
@include('shared.navbar')

<div class="container mt-5">
    <h1>Moje Treningi</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Opis</th>
                <th>Data</th>
                <th>Od</th>
                <th>Do</th>
                <th>Dyscyplina</th>
                <th>Max pkt</th>
                <th>Uczestnicy</th>
                <th>Edycja</th>
                <th>Usuń</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainings as $training)
            <tr>
                <td>{{ $training->description }}</td>
                <td>{{ \Carbon\Carbon::parse($training->date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</td>
                <td>
                    @if($training->trainer->sport && $training->trainer->sport->is_active)
                        {{ $training->trainer->sport->name }}
                    @else
                        <span class="text-muted">Dyscyplina zarchiwizowana</span>
                    @endif
                </td>
                <td class="text-end">{{ $training->max_points }}</td>
                <td>
                    <a href="{{ route('trainer.participants', $training->training_id) }}"
                       class="btn btn-primary btn-sm">
                        Lista
                    </a>
                </td>
                <td>
                    <a href="{{ route('trainer.editTraining', $training->training_id) }}"
                       class="btn btn-warning btn-sm">
                        Edytuj
                    </a>
                </td>
                <td>
                    <form method="POST"
                          action="{{ route('trainer.trainingDestroy', $training->training_id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Usunąć trening?')">
                            Usuń
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Brak treningów do wyświetlenia</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $trainings->links('pagination::bootstrap-4') }}

    @if(Auth::user()->sport && Auth::user()->sport->is_active)
        <a href="{{ route('trainer.createTraining') }}" class="btn btn-primary mt-3 mb-3">
            Dodaj trening
        </a>
    @else
        <div class="alert alert-info mt-3 mb-3">
            Nie możesz dodać nowego treningu – Twoja dyscyplina jest zarchiwizowana.
        </div>
    @endif
</div>

@include('shared.footer')
</body>
</html>

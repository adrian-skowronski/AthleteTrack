<table class="table">
    <thead>
        <tr>
            <th>Nazwa</th>
            <th>Data</th>
            <th>Start</th>
            <th>Koniec</th>
            <th>Trener</th>
            <th>Status</th>
            <th>Punkty</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($trainings as $training)
        <tr>
            <td>{{ $training->description }}</td>
            <td>{{ \Carbon\Carbon::parse($training->date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</td>
            <td>{{ $training->trainer_name }} {{ $training->trainer_surname }}</td>
            <td>{{ $training->status }}</td>
            <td>{{ $training->points }}</td>
            <td>
                @can('athlete.removeTraining', $training)
                    <form method="POST" action="{{ route('athlete.removeTraining') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz się wypisać z treningu?')">Wypisz się</button>
                    </form>
                @else
                    <span>Akcja niedostępna</span>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-2">
    {{ $trainings->links('pagination::bootstrap-4') }}
</div>

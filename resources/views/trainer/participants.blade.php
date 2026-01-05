@include('shared.html')
@include('shared.head', ['pageTitle' => 'Uczestnicy Treningu'])

<body>
    @include('shared.navbar')
    <div class="container mt-5">
        <h1>Uczestnicy Treningu</h1>
        <ul>
        <li>Trening: {{ $training->description }}</li>
        <li>Data: {{ $training->date }}</li>
</ul>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Status</th>
                    <th>Punkty</th>
                    <th>Wypisz</th>
                    <th>Przyznaj punkty</th>
                </tr>
            </thead>
       <tbody>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->surname }}</td>
        <td>{{ $user->pivot->status ?? '-' }}</td>
        <td>{{ $user->pivot->points ?? 0 }}</td>
        <td>
            @if(!Carbon\Carbon::parse($training->date . ' ' . $training->end_time)->lt(Carbon\Carbon::now()))
            <form method="POST" action="{{ route('trainer.removeParticipant', ['training_id' => $training->training_id, 'user_id' => $user->user_id]) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz wypisać z treningu tego zawodnika?')">Wypisz</button>
            </form>
            @else
            Nie można wypisać
            @endif
        </td>
        <td>
            @if(Carbon\Carbon::parse($training->date . ' ' . $training->end_hour)->lt(Carbon\Carbon::now()))
            <a href="{{ route('trainer.editStatus', ['training_id' => $training->training_id, 'user_id' => $user->user_id]) }}" class="btn btn-primary">Zmień status</a>
            @else
            Nie można zmienić statusu
            @endif
        </td>
    </tr>
    @endforeach
</tbody>

        </table>

        <div class="mt-2">
            {{ $users->links('pagination::bootstrap-4') }}
            </div> 

        <div class="mt-5">
            <a href="{{ route('trainer.trainings') }}" class="btn btn-secondary">Powróć do listy moich treningów</a>
        </div>
    </div>
    @include('shared.footer')
</body>
</html>

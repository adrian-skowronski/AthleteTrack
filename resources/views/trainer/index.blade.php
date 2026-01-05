@include('shared.html')
@include('shared.head', ['pageTitle' => 'Mój Panel'])

<body>
    @include('shared.navbar')
    
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Sekcja Moje Treningi --}}
        <div class="mt-5">
            <h1>Moje Treningi</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Opis</th>
                        <th>Data</th>
                        <th scope="col">Od</th>
                        <th scope="col">Do</th>
                        <th scope="col">Dyscyplina</th>
                        <th scope="col">Max. pkt</th>
                        <th scope="col">Pokaż uczestników</th>
                        <th scope="col">Edytuj</th>
                        <th scope="col">Usuń</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $training)
                    <tr>
                        <td>{{ $training->description }}</td>
<td>{{ \Carbon\Carbon::parse($training->date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }}</td>
<td>{{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</td>

                        <td>{{ $training->trainer->sport->name }}</td>
                        <td>{{ $training->max_points }}</td>
                        <td>
                            <a href="{{ route('trainer.participants', $training->training_id) }}" class="btn btn-primary">Pokaż uczestników</a>
                        </td>
                        <td>
                            <a href="{{ route('trainer.editTraining', $training->training_id) }}" class="btn btn-warning">Edycja</a>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('trainer.trainingDestroy', $training->training_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2">
                {{ $trainings->links('pagination::bootstrap-4') }}
            </div>

            <div class="container mt-2 mb-3">
                <div class="row">
                    <div class="col">
                        <a href="{{ route('trainer.createTraining') }}" class="btn btn-primary">Dodaj trening</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-info col-sm mb-5 mt-5">
            <div class="row">
                <div class="col-sm">
                    <h1>Moje Dane</h1>
                    <ul>
                        <li><strong>Imię:</strong> {{ Auth::user()->name }}</li>
                        <li><strong>Nazwisko:</strong> {{ Auth::user()->surname }}</li>
<li>
    <strong>Data urodzenia:</strong> 
    {{ \Carbon\Carbon::parse(Auth::user()->birthdate)->format('d-m-Y') }} 
    (wiek: {{ $age }} l.)
</li>
                        <li><strong>Telefon:</strong> {{ Auth::user()->phone }}</li>
                        <li><strong>Dyscyplina:</strong> {{ Auth::user()->sport->name }}</li>
                    </ul>
                    <a href="{{ route('trainer.edit') }}" class="btn btn-warning">Edytuj dane</a>
                </div>
                <div class="col-sm">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User Photo" class="user-photo">
                    @else
                        <div class="mt-5">
                            <i>Brak wgranego zdjęcia użytkownika.</i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @include('shared.footer')
</body>
</html>

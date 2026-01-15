@include('shared.html')
@include('shared.head', ['pageTitle' => 'Mój Panel'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
    @include('shared.navbar')
   
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="user-info mb-5">
            <div class="row">
                <div class="col-sm">
                    <h1>Moje dane</h1>
                    <ul>
                        <li><strong>Imię:</strong> {{ Auth::user()->name }}</li>
                        <li><strong>Nazwisko:</strong> {{ Auth::user()->surname }}</li>
                        <li><strong>Kategoria rozwoju (ranga):</strong> {{ Auth::user()->category ? Auth::user()->category->name : 'Brak kategorii' }}</li>
                        <li>
                            <strong>Data urodzenia:</strong> 
                            {{ \Carbon\Carbon::parse(Auth::user()->birthdate)->format('d-m-Y') }} 
                            (wiek: {{ $age }} l.)
                        </li>
                        <li><strong>Telefon:</strong> {{ Auth::user()->phone }}</li>
                        <li><strong>Dyscyplina:</strong> {{ Auth::user()->sport ? Auth::user()->sport->name : 'Brak' }}</li>
                    </ul>

                    <div class="container">
                        <a href="{{ route('athlete.edit') }}" class="btn btn-warning">Edytuj dane</a>
                        <a href="{{ route('athlete.changePasswordForm') }}" class="btn btn-secondary">Zmień hasło</a>
                    </div>
                </div>

                <div class="col-sm">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User Photo" class="user-photo">
                    @else
                        <div class="mt-5"><i>Brak wgranego zdjęcia użytkownika.</i></div>
                    @endif
                </div>

                <div class="col-sm"></div>
            </div>

            {{-- ================= MOJE TRENINGI ================= --}}
            <div class="mt-5">
                <h2>Moje treningi</h2>

                @if($trainings->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Data</th>
                                <th>Start</th>
                                <th>Koniec</th>
                                <th>Trener</th>
                                <th>Status</th>
                                <th class="text-end">Otrzymane punkty</th>
                                <th>Wypisz się</th>
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
                                    <td class="text-end">{{ $training->points ?? 0 }}</td>
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
                @else
                    <div class="alert alert-info text-center">Brak treningów do wyświetlenia</div>
                @endif

                <div class="mt-2">{{ $trainings->links('pagination::bootstrap-4') }}</div>

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

            {{-- ================= MOJE ZAWODY ================= --}}
            <div class="mt-5">
                <h2>Moje zawody</h2>

                @if($events->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Data</th>
                                <th>Godzina</th>
                                <th class="text-end">Punkty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->start_hour)->format('H:i') }}</td>
                                    <td class="text-end">
                                        @php
                                            $eventUser = \App\Models\EventUser::where('event_id', $event->event_id)
                                                ->where('user_id', auth()->id())
                                                ->first();
                                        @endphp

                                        @if(\Carbon\Carbon::parse($event->date)->isFuture())
                                            <span class="text-muted">Wydarzenie w przyszłości</span>
                                        @else
                                            {{ $eventUser ? $eventUser->points : 0 }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info text-center">Brak zawodów do wyświetlenia</div>
                @endif

                <div class="mt-2">{{ $events->links('pagination::bootstrap-4') }}</div>
            </div>

            {{-- ================= STATYSTYKI ================= --}}
            <h2 class="mt-5">Moje statystyki</h2>
            <ul>
                <li><b>Liczba zdobytych punktów:</b> {{ Auth::user()->points }}</li>
                <li><b>Liczba zrealizowanych treningów:</b> {{ $total_trainings }}</li>
                <li><b>Łączny czas treningów:</b> {{ $total_time }}h</li>
                <li><b>Ostatni zrealizowany trening:</b>
                    @if ($last_training)
                        {{ $last_training->description }} |
                        Data: {{ \Carbon\Carbon::parse($last_training->date)->format('d-m-Y') }} |
                        Status: {{ $last_training->status }} | 
                        Punkty: {{ $last_training->points }}
                    @else
                        Brak danych
                    @endif
                </li>
                <li><b>Ostatni opuszczony trening:</b>
                    @if ($last_missed_training)
                        {{ $last_missed_training->description }} |
                        Data: {{ \Carbon\Carbon::parse($last_missed_training->date)->format('d-m-Y') }} |
                        Status: {{ $last_missed_training->status }} | 
                        Punkty: {{ $last_missed_training->points }}
                    @else
                        Brak danych
                    @endif
                </li>
                <li><b>Liczba punktów zdobytych w bieżącym miesiącu:</b> {{ $total_points_last_month }}</li>
            </ul>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container h-100 w-100 position-relative">
                            <canvas id="timeSpentChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container h-100 w-100 position-relative">
                            <canvas id="trainingCountChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Wykres: Średni czas na treningu
    var timeSpentCtx = document.getElementById('timeSpentChart').getContext('2d');
    var timeSpentData = @json($trainingData);
    var timeSpentLabels = timeSpentData.map(d => d.date).reverse();
    var timeSpentValues = timeSpentData.map(d => d.average_time).reverse();

    new Chart(timeSpentCtx, {
        type: 'line',
        data: {
            labels: timeSpentLabels,
            datasets: [{
                label: 'Średni czas na treningu (godziny)',
                data: timeSpentValues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 0.5 } } }
        }
    });

    // Wykres: Liczba treningów w miesiącu
    var trainingCountCtx = document.getElementById('trainingCountChart').getContext('2d');
    var trainingCountLabels = {!! json_encode($labels) !!};
    var trainingCountData = {!! json_encode($trainingCounts) !!};

    new Chart(trainingCountCtx, {
        type: 'bar',
        data: {
            labels: trainingCountLabels,
            datasets: [{
                label: 'Liczba treningów z obecnością w miesiącu',
                data: trainingCountData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>

@include('shared.footer')
</body>
</html>
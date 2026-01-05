@include('shared.html')
@include('shared.head', ['pageTitle' => 'Treningi - lista'])

<body>
    @include('shared.navbar')

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    <div class="container mt-3 mb-5">
        <div class="row mb-1">
            <h1>Lista treningów</h1>
        </div>
@if(auth()->check() && auth()->user()->role_id == 3)
    <form method="GET" action="{{ route('trainings.view') }}" class="mb-3">
        <div class="d-flex align-items-center gap-2">
            <label for="filter" class="form-label mb-0">Pokaż:</label>

            <select name="filter" id="filter" class="form-select form-select-sm w-auto"
                    onchange="this.form.submit()">
                <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>
                    Wszystkie treningi
                </option>
                <option value="my" {{ request('filter') == 'my' ? 'selected' : '' }}>
                    Tylko moja dyscyplina
                </option>
            </select>
        </div>
    </form>
@endif

        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Opis</th>
                        <th scope="col">Data</th>
                        <th scope="col">Od</th>
                        <th scope="col">Do</th>
                        <th scope="col">Dyscyplina</th>
                        <th scope="col">Trener</th>
                        @if(auth()->check() && auth()->user()->role_id == 3)
                            <th scope="col">Status zapisu</th>
                        @endif
                        <th scope="col">Zapisz się</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainings as $training)
                        <tr>
                            <td>{{ $training->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($training->date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</td>
                            <td>{{ $training->trainer->sport->name }}</td>
                            <td>
                                <a href="{{ route('trainer.details', $training->trainer->user_id) }}">
                                    {{ $training->trainer->name }} {{ $training->trainer->surname }}
                                </a>
                            </td>

                            @if(auth()->check() && auth()->user()->role_id == 3)
                                <td>
                                    @if($training->users->contains(auth()->user()->user_id))
                                        <span class="badge bg-success">Zapisany</span>
                                    @else
                                        <span class="badge bg-secondary">Nie zapisany</span>
                                    @endif
                                </td>
                            @endif

                           <td>
    @auth
        @if(auth()->user()->role_id == 3)
            
            @php
                $sameSport = auth()->user()->sport_id === $training->trainer->sport_id;
                $isFuture = \Carbon\Carbon::parse($training->date)->isFuture();
                $isSigned = $training->users->contains(auth()->user()->user_id);
            @endphp

            @if(!$sameSport)
                <em>Opcja niedostępna</em>

            @elseif(!$isFuture)
                <em>Opcja niedostępna</em>

            @elseif($isSigned)
                <em>Opcja niedostępna</em>

            @else
                <form action="{{ route('training.signUp', $training->training_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Zapisz się</button>
                </form>
            @endif

        @else
            <em>Opcja niedostępna</em>
        @endif
    @else
        <em>Opcja niedostępna</em>
    @endauth
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->check() && auth()->user()->role_id == 3 ? 8 : 7 }}" class="text-center">
                                Brak treningów do wyświetlenia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">
                {{ $trainings->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

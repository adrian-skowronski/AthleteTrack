@include('shared.html')
@include('shared.head', ['pageTitle' => 'Treningi - lista'])


<body>
    
    @include('shared.navbar')

    <div class="container mt-3 mb-5">
        <div class="row mt-3 mb-3">
            <h1>Lista treningów</h1>
        </div>
        <div class="row mb-3 mt-3">
    <div class="col d-flex justify-content-center">
        <a href="{{ route('admin.trainings.create') }}" class="btn btn-primary">Dodaj nowy trening</a>
    </div>
</div>

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
                        <th scope="col">Max. pkt</th>
                        <th scope="col">Uczestnicy</th>
                        <th scope="col">Edytuj</th>
                        <th scope="col">Usuń</th>
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
                            <td>{{ $training->trainer->name }} {{ $training->trainer->surname }}</a></td>                           
                           <td class="text-end">{{ $training->max_points }}</td>
                          <td>
    <a href="{{ route('admin.trainings.participants', $training->training_id) }}" class="btn btn-info">
        Lista
    </a>
</td>

                            <td>
                                <a href="{{ route('admin.trainings.edit', $training->training_id) }}" class="btn btn-warning">Edytuj</a>
</td>
<td>
                                <form method="POST" action="{{ route('admin.trainings.destroy', $training->training_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Brak treningów do wyświetlenia.</td>
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

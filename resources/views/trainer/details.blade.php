@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dane trenera'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 text-center">
        <h2 class="mb-4">Dane trenera</h2>
         <table class="table mx-auto" style="max-width: 500px;">
        <tbody>
            <tr>
                <td class="text-start"><strong>Imię i nazwisko:</strong></td>
                <td class="text-start">{{ $trainer->name }} {{ $trainer->surname }}</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Dyscyplina:</strong></td>
                <td class="text-start">{{ $trainer->sport->name }}</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Data urodzenia:</strong></td>
                <td class="text-start">{{ \Carbon\Carbon::parse($trainer->birthdate)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Telefon:</strong></td>
                <td class="text-start">{{ $trainer->phone }}</td>
            </tr>
        </tbody>
    </table>
                <strong>Zdjęcie:</strong><br>
                <div class="col-sm mt-1">
    @if(($trainer)->photo)
        <img src="{{ asset('storage/' . $trainer->photo) }}" alt="User Photo" class="user-photo">
    @else
        <div class="mt-5">
            <i>Brak wgranego zdjęcia trenera.</i>
        </div>
    @endif
</div>
            </li>
        </ul>

        <div class="mt-3 mb-3">
            
            <a href="{{ route('trainings.view') }}" class="btn btn-secondary">Powróć do listy treningów</a>
        </div>
    </div>

    @include('shared.footer')
</body>

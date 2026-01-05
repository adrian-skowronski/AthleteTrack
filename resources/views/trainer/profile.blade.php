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

        <h1 class="mb-4">Moje Dane</h1>

        <div class="row">
            <div class="col-lg-8">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Imię</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Nazwisko</th>
                            <td>{{ Auth::user()->surname }}</td>
                        </tr>
                        <tr>
                            <th>Data urodzenia</th>
                            <td>{{ \Carbon\Carbon::parse(Auth::user()->birthdate)->format('d-m-Y') }} (wiek: {{ $age }} l.)</td>
                        </tr>
                        <tr>
                            <th>Telefon</th>
                            <td>{{ Auth::user()->phone }}</td>
                        </tr>
                        <tr>
                            <th>Dyscyplina</th>
                            <td>{{ Auth::user()->sport->name }}</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('trainer.edit') }}" class="btn btn-warning mt-2">Edytuj dane</a>
                <a href="{{ route('trainer.changePasswordForm') }}" class="btn btn-secondary mt-2">Zmień hasło</a>

            </div>

            <div class="col-lg-4 d-flex justify-content-center align-items-start mt-4 mt-lg-0 mb-4">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User Photo" class="img-fluid rounded" style="max-width: 100%; max-height: 300px;">
                @else
                    <div class="text-center mt-3">
                        <i>Brak wgranego zdjęcia użytkownika.</i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

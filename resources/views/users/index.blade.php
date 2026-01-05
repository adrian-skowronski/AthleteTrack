@include('shared.html')
@include('shared.head', ['pageTitle' => 'Lista użytkowników - panel admina'])

<body>

@include('shared.navbar')

<div class="container mt-5 mb-5">


    <div class="container mt-3">
@include('shared.session-error')
@include('shared.validation-error')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>

    <div class="row mt-5 mb-3">
        <h1>Lista użytkowników</h1>
    </div>

    <div class="row mb-4 mt-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <label for="filter" class="form-label fw-bold">Filtr użytkowników:</label>
                <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>Wszyscy</option>
                    <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Aktywni</option>
                    <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Archiwizowani</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Przycisk dodawania -->
    <div class="row mb-3">
        <div class="col d-flex justify-content-center">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Dodaj nowego użytkownika</a>
        </div>
    </div>

    <!-- Tabela użytkowników -->
    <div class="row">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">Imię</th>
                    <th scope="col">Nazwisko</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Data urodzenia</th>
                    <th scope="col">Punkty</th>
                    <th scope="col">Telefon</th>
                    <th scope="col">Rola</th>
                    <th scope="col">Kategoria</th>
                    <th scope="col">Dyscyplina</th>
                    <th scope="col">Zatwierdzony</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edytuj</th>
                    <th scope="col">Usuń</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->surname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->birthdate }}</td>
                        <td>{{ $user->points }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->role ? $user->role->name : 'Brak' }}</td>
                        <td>{{ $user->category ? $user->category->name : 'Brak' }}</td>
                        <td>{{ $user->sport ? $user->sport->name : 'Brak' }}</td>
                        <td>{{ $user->approved ? 'Tak' : 'Nie' }}</td>

                        <td>
                            @if($user->is_active)
                                <span class="text-success fw-bold">Aktywny</span>
                            @else
                                <span class="text-danger fw-bold">Archiwizowany</span>
                            @endif
                        </td>

                        <!-- Edycja -->
                        <td>
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-warning">Edytuj</a>
                        </td>

                        <!-- Usuwanie -->
                        <td>
                            <form method="POST" action="{{ route('admin.users.destroy', $user->user_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Czy na pewno chcesz usunąć?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Brak użytkowników do wyświetlenia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginacja -->
    <div class="row">
        <div class="d-flex justify-content-center mt-2">
            {{ $users->appends(['filter' => request('filter')])->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Powrót -->
    <div class="row justify-content-center mt-3">
        <div class="col text-center">
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Powróć do panelu admina</a>
        </div>
    </div>

</div>

@include('shared.footer')
</body>
</html>

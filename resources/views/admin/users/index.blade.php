@include('shared.html')
@include('shared.head', ['pageTitle' => 'Użytkownicy - lista'])

<body>
@include('shared.navbar')

<div class="container mt-3">
    @include('shared.session-error')
    @include('shared.validation-error')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<div class="container mt-5 mb-5">

    <div class="row mb-3">
        <div class="col">
            <h1>Użytkownicy</h1>
        </div>
        <div class="col d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Dodaj nowego użytkownika
            </a>
        </div>
    </div>

    <!-- OCZEKUJĄCY UŻYTKOWNICY -->
    <div class="row mb-4">
        <h3>Oczekujący na zatwierdzenie</h3>

        @if($pendingUsers->count() > 0)
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Email</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('admin.approve', $user->user_id) }}" class="btn btn-success btn-sm">
                                    Zatwierdź
                                </a>
                                <form action="{{ route('admin.reject', $user->user_id) }}" method="POST"
                                      onsubmit="return confirm('Czy na pewno chcesz odrzucić tego użytkownika?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Odrzuć</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center">Brak oczekujących próśb o zatwierdzenie.</p>
        @endif
    </div>

    <!-- FILTR -->
    <h3>Zatwierdzeni</h3>

    <div class="row mb-4 mt-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <label class="form-label fw-bold">Filtr użytkowników:</label>
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Wszyscy</option>
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Aktywni</option>
                    <option value="archived" {{ $filter === 'archived' ? 'selected' : '' }}>Archiwizowani</option>
                </select>
            </form>
        </div>
    </div>

    <!-- TABELA UŻYTKOWNIKÓW -->
    <div class="row">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Data urodzenia</th>
                    <th>Telefon</th>
                    <th>Dyscyplina</th>
                    <th>Rola</th>
                    <th>Punkty</th>
                    <th>Status</th>
                    <th>Akcje</th>
                    <th>Edytuj</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->surname }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            {{ $user->birthdate
                                ? \Carbon\Carbon::parse($user->birthdate)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td>{{ $user->phone ?? '-' }}</td>

                        <td>
                            @if(in_array($user->role_id, [2,3]) && $user->sport)
                                {{ $user->sport->name }}
                            @else
                                Brak
                            @endif
                        </td>

                        <td>{{ $user->role?->name ?? 'Brak' }}</td>
<td>
    @if($user->role_id == 3)
        {{ $user->points !== null ? $user->points : 'Brak' }}
    @else
        Brak
    @endif
</td>

                        <td>
                            @if($user->is_active)
                                <span class="text-success fw-bold">Aktywny</span>
                            @else
                                <span class="text-danger fw-bold">Archiwizowany</span>
                            @endif
                        </td>

                        <td class="d-flex gap-1">
                            @if($user->is_active)
                                <form method="POST" action="{{ route('admin.users.deactivate', $user->user_id) }}">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">Archiwizuj</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.activate', $user->user_id) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Aktywuj</button>
                                </form>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-primary btn-sm">
                                Edytuj
                            </a>
                        </td>

                        <td>
                            @if($user->role_id !== 1)
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST"
                                      onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            @else
                                <span class="text-muted">Brak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">
                            Brak użytkowników do wyświetlenia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINACJA -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>

</div>

@include('shared.footer')
</body>
</html>

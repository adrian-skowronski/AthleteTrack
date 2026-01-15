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
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                Dodaj nowego użytkownika
            </a>
        </div>
    </div>

    <!-- FILTR -->
    <div class="row mb-4 mt-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <label class="form-label fw-bold">Filtr użytkowników:</label>
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Wszyscy</option>
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Aktywni</option>
                    <option value="archived" {{ $filter === 'archived' ? 'selected' : '' }}>Zarchiwizowani</option>
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
                        <td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ in_array($user->role_id,[2,3]) && $user->sport ? $user->sport->name : 'Brak' }}</td>
                        <td>{{ $user->role?->name ?? 'Brak' }}</td>
                        <td>{{ $user->role_id==3 && $user->points!==null ? $user->points : 'Brak' }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="text-success fw-bold">Aktywny</span>
                            @else
                                <span class="text-warning fw-bold">Zarchiwizowany</span>
                            @endif
                        </td>

                        <td>
    <div class="d-flex flex-column gap-1">
        @if($user->role_id != 1)
            @if($user->is_active)
                <form method="POST" action="{{ route('admin.users.deactivate', $user->user_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm w-100"
                            onclick="return confirm('Czy na pewno chcesz zarchiwizować tego użytkownika?')">
                        Zarchiwizuj
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.activate', $user->user_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100">Przywróć</button>
                </form>
            @endif
        @else
            <span class="text-muted">Nie można archiwizować</span>
        @endif
    </div>
</td>

                        <!-- Edytuj -->
                        <td>
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-primary btn-sm">
                                Edytuj
                            </a>
                        </td>

                        <!-- Usuń -->
                        <td>
                            @if($user->role_id != 1)
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST"
                                      onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Usuń</button>
                                </form>
                            @else
                                <span class="text-muted">Nie można usunąć</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Brak użytkowników do wyświetlenia.</td>
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

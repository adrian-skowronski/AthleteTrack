@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dyscypliny - lista'])

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
        <div class="row mt-5 mb-3">
            <h1>Lista dyscyplin</h1>
        </div>

        <!-- PRZYCISK + FILTR -->
        <div class="row mb-4 mt-3 align-items-center">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.sports.index') }}">
                    <label class="form-label fw-bold">Filtr dyscyplin:</label>
                    <select name="filter" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Wszystkie</option>
                        <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Aktywne</option>
                        <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Archiwizowane</option>
                    </select>
                </form>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="{{ route('admin.sports.create') }}" class="btn btn-primary">Dodaj nową dyscyplinę</a>
            </div>
        </div>

        <!-- TABELA -->
        <div class="row">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa</th>
                        <th>Status</th>
                        <th>Akcja</th>
                        <th>Edytuj</th>
                        <th>Usuń</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sports as $sport)
                        <tr>
                            <td>{{ $sport->sport_id }}</td>
                            <td>{{ $sport->name }}</td>

                            <td>
                                @if($sport->is_active)
                                    <span class="text-success fw-bold">Aktywna</span>
                                @else
                                    <span class="text-danger fw-bold">Archiwizowana</span>
                                @endif
                            </td>

                            <!-- ARCHIWIZUJ / PRZYWRÓĆ -->
                            <td>
                                @if($sport->is_active)
                                    <form method="POST" action="{{ route('admin.sports.deactivate', $sport->sport_id) }}">
                                        @csrf
                                        <button class="btn btn-warning">Archiwizuj</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.sports.activate', $sport->sport_id) }}">
                                        @csrf
                                        <button class="btn btn-success">Przywróć</button>
                                    </form>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.sports.edit', $sport->sport_id) }}" class="btn btn-warning">Edytuj</a>
                            </td>

                            <td>
                                <form method="POST" action="{{ route('admin.sports.destroy', $sport->sport_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Czy chcesz usunąć?')">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Brak dyscyplin do wyświetlenia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('shared.footer')
</body>
</html>

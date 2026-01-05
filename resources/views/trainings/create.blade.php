@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dodaj nowy trening'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 mb-5">
        @include('shared.session-error')
        @include('shared.validation-error')

        <div class="row mt-4 mb-4 text-center">
            <h1>Dodaj nowy trening</h1>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-6">
                <form method="POST" action="{{ route('admin.trainings.store') }}">
                    @csrf

                    <div class="form-group mb-2">
                        <label for="description" class="form-label">Opis <em>(wymagane)</em></label>
                        <input id="description" name="description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required maxlength="500">
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="date" class="form-label">Data <em>(wymagane)</em></label>
                        <input id="date" name="date" type="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required min="2024-01-01">
                        @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="start_time" class="form-label">Godzina rozpoczęcia <em>(wymagane)</em></label>
                        <input id="start_time" name="start_time" type="time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                        @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="end_time" class="form-label">Godzina zakończenia <em>(wymagane)</em></label>
                        <input id="end_time" name="end_time" type="time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                        @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="trainer_id" class="form-label">Trener <em>(wymagane)</em></label>
                        <select id="trainer_id" name="trainer_id" class="form-select @error('trainer_id') is-invalid @enderror" required>
                            <option value="">Wybierz trenera</option>
                            @foreach ($trainers as $trainer)
<option value="{{ $trainer->user_id }}"
    @if (old('trainer_id') == $trainer->user_id) selected @endif>
    {{ $trainer->name }} {{ $trainer->surname }}
    ({{ $trainer->sport->name ?? 'brak dyscypliny' }})
</option>
                            @endforeach
                        </select>
                        @error('trainer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="max_points" class="form-label">Maksymalna liczba punktów <em>(wymagane)</em></label>
                        <input id="max_points" name="max_points" type="number" class="form-control @error('max_points') is-invalid @enderror" value="{{ old('max_points') }}" min="0" max="200">
                        @error('max_points')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center mt-4 mb-4">
                        <input class="btn btn-success" type="submit" value="Dodaj">
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('shared.footer')
</body>

</html>

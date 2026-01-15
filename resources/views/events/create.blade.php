@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dodaj nowe wydarzenie'])

<body>
@include('shared.navbar')

<div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')
    <div class="row mt-4 mb-4 text-center">
        <h1>Dodaj nowe wydarzenie</h1>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <form id="eventForm" method="POST" action="{{ route('admin.events.store') }}" novalidate>
                @csrf

                <div class="form-group mb-2">
                    <label for="name" class="form-label">Nazwa <em>(wymagane)</em></label>
                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="100">
                    <div class="invalid-feedback">Nieprawidłowa nazwa!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="description" class="form-label">Opis <em>(wymagane)</em></label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" maxlength="500" required>{{ old('description') }}</textarea>
                    <div class="invalid-feedback">Nieprawidłowy opis! Maksymalna długość opisu to 500 znaków.</div>
                </div>

                <div class="form-group mb-2">
                    <label for="required_category_id" class="form-label">Wymagana kategoria <em>(wymagane)</em></label>
                    <select id="required_category_id" name="required_category_id" class="form-select @error('required_category_id') is-invalid @enderror" required>
                        <option value="">Wybierz kategorię</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ old('required_category_id') == $category->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Nieprawidłowa kategoria!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="age_from" class="form-label">Wiek od <em>(wymagane)</em></label>
                    <input id="age_from" name="age_from" type="number" min="0" max="100" class="form-control @error('age_from') is-invalid @enderror" value="{{ old('age_from', 0) }}" required>
                    <div class="invalid-feedback">Nieprawidłowy wiek od!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="age_to" class="form-label">Wiek do <em>(wymagane)</em></label>
                    <input id="age_to" name="age_to" type="number" min="0" max="100" class="form-control @error('age_to') is-invalid @enderror" value="{{ old('age_to', 0) }}" required>
                    <div class="invalid-feedback">Nieprawidłowy wiek do!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="date" class="form-label">Data <em>(wymagane)</em></label>
                    <input id="date" name="date" type="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                    <div class="invalid-feedback">Nieprawidłowa data!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="start_hour" class="form-label">Godzina <em>(wymagane)</em></label>
                    <input id="start_hour" name="start_hour" type="time" class="form-control @error('start_hour') is-invalid @enderror" value="{{ old('start_hour') }}" required>
                    <div class="invalid-feedback">Nieprawidłowa godzina rozpoczęcia!</div>
                </div>

                <div class="form-group mb-2">
                    <label for="max_participants" class="form-label">Maks. liczba uczestników <em>(wymagane)</em></label>
                    <input id="max_participants" name="max_participants" type="number" class="form-control @error('max_participants') is-invalid @enderror" value="{{ old('max_participants') }}" required min="3" max="299">
                    <div class="invalid-feedback">Nieprawidłowa liczba uczestników! Minimalna liczba to 3, maksymalna 299.</div>
                </div>

                <div class="text-center mt-4 mb-4">
                    <input class="btn btn-success" type="submit" value="Dodaj">
                </div>
            </form>
        </div>
    </div>
</div>

@include('shared.footer')

<script>
    // Bootstrap 5 validation script
    (function() {
        'use strict';
        var form = document.getElementById('eventForm');

        form.addEventListener('submit', function(event) {
            // Resetowanie klas przed walidacją
            var inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(function(input) {
                input.classList.remove('is-invalid');
            });

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                inputs.forEach(function(input) {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });
            }
        }, false);
    })();
</script>
</body>
</html>

@include('shared.html')
@include('shared.head', ['pageTitle' => 'Edytuj użytkownika'])

<body>
@include('shared.navbar')

<div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')

    <div class="row mt-4 mb-4 text-center">
        <h1>Edytuj użytkownika - {{ $user->name }} {{ $user->surname }}</h1>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}"
                  enctype="multipart/form-data" id="editUserForm" novalidate>
                @csrf
                @method('PUT')

                {{-- IMIĘ --}}
                <div class="form-group mb-2">
                    <label for="name" class="form-label">Imię <em>(wymagane)</em></label>
                    <input id="name" name="name" type="text" class="form-control"
                           value="{{ old('name', $user->name) }}" required maxlength="100"
                           pattern="^[A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż]{1,99}$"
                           title="Pierwsza litera duża, min. 2 znaki, bez znaków specjalnych">
                    <div class="invalid-feedback">Pierwsza litera duża, min. 2 znaki, bez znaków specjalnych.</div>
                </div>

                {{-- NAZWISKO --}}
                <div class="form-group mb-2">
                    <label for="surname" class="form-label">Nazwisko <em>(wymagane)</em></label>
                    <input id="surname" name="surname" type="text" class="form-control"
                           value="{{ old('surname', $user->surname) }}" required maxlength="100"
                           pattern="^[A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż\-]{1,99}$"
                           title="Pierwsza litera duża, min. 2 znaki">
                    <div class="invalid-feedback">Min. 2 znaki, pierwsza litera duża.</div>
                </div>

                {{-- EMAIL --}}
                <div class="form-group mb-2">
                    <label for="email" class="form-label">E-mail <em>(wymagane)</em></label>
                    <input id="email" name="email" type="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required maxlength="120">
                    <div class="invalid-feedback">Nieprawidłowy email!</div>
                </div>

                {{-- DATA URODZENIA --}}
                <div class="form-group mb-2">
                    <label for="birthdate" class="form-label">Data urodzenia <em>(wymagane)</em></label>
                    <input id="birthdate" name="birthdate" type="date" class="form-control"
                           value="{{ old('birthdate', $user->birthdate) }}" required min="1920-01-01" max="{{ now()->toDateString() }}">
                    <div class="invalid-feedback">Nieprawidłowa data urodzenia!</div>
                </div>

                {{-- TELEFON --}}
                <div class="form-group mb-2">
                    <label for="phone" class="form-label">Telefon <em>(wymagane)</em></label>
                    <input id="phone" name="phone" type="text" class="form-control"
                           value="{{ old('phone', $user->phone) }}" required pattern="\d{9}" title="Numer telefonu: 9 cyfr">
                    <div class="invalid-feedback">Nieprawidłowy telefon! Dokładnie 9 cyfr.</div>
                </div>

                {{-- ROLA --}}
                <div class="form-group mb-2">
                    <label for="role_id" class="form-label">Rola <em>(wymagane)</em></label>
                    <select id="role_id" name="role_id" class="form-select" required>
                        <option value="">Wybierz rolę</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}" @if(old('role_id', $user->role_id) == $role->role_id) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Wybierz rolę!</div>
                </div>

                {{-- SPORT --}}
                <div class="form-group mb-2" id="sportGroup">
                    <label for="sport_id" class="form-label">Dyscyplina <em>(wymagane dla trenerów i sportowców)</em></label>
                    <select id="sport_id" name="sport_id" class="form-select">
                        <option value="">Wybierz dyscyplinę</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->sport_id }}" @if(old('sport_id', $user->sport_id) == $sport->sport_id) selected @endif>{{ $sport->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Wybierz dyscyplinę!</div>
                </div>

                {{-- PUNKTY --}}
                <div class="form-group mb-2" id="pointsGroup">
                    <label for="points" class="form-label">Punkty <em>(wymagane dla sportowców)</em></label>
                    <input id="points" name="points" type="number" class="form-control"
                           value="{{ old('points', $user->points) }}">
                    <div class="invalid-feedback">Wprowadź poprawną liczbę punktów!</div>
                </div>

                {{-- ZATWIERDZONY --}}
                <div class="form-group mb-2">
                    <label for="approved" class="form-label">Zatwierdzony</label>
                    <select id="approved" name="approved" class="form-select" required>
                        <option value="0" @if(old('approved', $user->approved) == '0') selected @endif>Nie</option>
                        <option value="1" @if(old('approved', $user->approved) == '1') selected @endif>Tak</option>
                    </select>
                    <div class="invalid-feedback">Wybierz status zatwierdzenia!</div>
                </div>

                {{-- ZDJĘCIE --}}
                <div class="form-group mb-2">
                    <label for="photo" class="form-label">Zdjęcie</label>
                    <input id="photo" name="photo" type="file" class="form-control" accept="image/*">
                    <div class="invalid-feedback">Nieprawidłowy plik!</div>
                </div>

                <div class="text-center mt-4 mb-4">
                    <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('shared.footer')

<script>
    const roleSelect = document.getElementById('role_id');
    const pointsGroup = document.getElementById('pointsGroup');
    const sportGroup = document.getElementById('sportGroup');
    const pointsInput = document.getElementById('points');
    const sportInput = document.getElementById('sport_id');
    const form = document.getElementById('editUserForm');

    function updateFields() {
        const roleId = roleSelect.value;
        if(roleId == '1') { // Admin
            pointsGroup.style.display = 'none';
            sportGroup.style.display = 'none';
            pointsInput.required = false;
            sportInput.required = false;
        } else if(roleId == '2') { // Trener
            pointsGroup.style.display = 'none';
            sportGroup.style.display = 'block';
            pointsInput.required = false;
            sportInput.required = true;
        } else if(roleId == '3') { // Sportowiec
            pointsGroup.style.display = 'block';
            sportGroup.style.display = 'block';
            pointsInput.required = true;
            sportInput.required = true;
        } else {
            pointsGroup.style.display = 'none';
            sportGroup.style.display = 'none';
            pointsInput.required = false;
            sportInput.required = false;
        }
    }

    roleSelect.addEventListener('change', updateFields);
    window.addEventListener('DOMContentLoaded', updateFields);

    // WALIDACJA FORMULARZA
    form.addEventListener('submit', function(event) {
        let valid = true;
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => input.classList.remove('is-invalid'));

        inputs.forEach(input => {
            if(!input.checkValidity()) {
                valid = false;
                input.classList.add('is-invalid');
            }
        });

        // Walidacja zależna od roli
        const roleId = roleSelect.value;
        if(roleId == '2') { // Trener
            if(!sportInput.value) {
                valid = false;
                sportInput.classList.add('is-invalid');
            }
        }
        if(roleId == '3') { // Sportowiec
            if(!sportInput.value) {
                valid = false;
                sportInput.classList.add('is-invalid');
            }
            if(!pointsInput.value) {
                valid = false;
                pointsInput.classList.add('is-invalid');
            }
        }

        if(!valid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
</script>

</body>
</html>

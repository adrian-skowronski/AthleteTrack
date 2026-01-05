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
                <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}" enctype="multipart/form-data" id="editUserForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-2">
                        <label for="name" class="form-label">Imię <em>(wymagane)</em></label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required maxlength="100"
                            pattern="^[A-ZĄĆĘŁŃÓŚŹŻ][a-ząćęłńóśźż]{1,99}$"
                            title="Pierwsza litera duża, min. 2 znaki, bez znaków specjalnych">
                        <small class="form-text text-muted">Pierwsza litera duża, min. 2 znaki, bez znaków specjalnych.</small>
                        @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="surname" class="form-label">Nazwisko <em>(wymagane)</em></label>
                        <input id="surname" name="surname" type="text" class="form-control @error('surname') is-invalid @enderror"
                            value="{{ old('surname', $user->surname) }}" required maxlength="100"
                            pattern="^[A-Za-zĄĆĘŁŃÓŚŹŻąćęłńóśźż][a-ząćęłńóśźż]{1,99}$"
                            title="Pierwsza litera duża, min. 2 znaki">
                        <small class="form-text text-muted">Min. 2 znaki.</small>
                        @error('surname') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="email" class="form-label">E-mail <em>(wymagane)</em></label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required maxlength="120">
                        @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="birthdate" class="form-label">Data urodzenia <em>(wymagane)</em></label>
                        <input id="birthdate" name="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror"
                            value="{{ old('birthdate', $user->birthdate) }}" required min="1920-01-01" max="{{ now()->toDateString() }}">
                        @error('birthdate') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                     <div class="form-group mb-2">
                        <label for="phone" class="form-label">Telefon <em>(wymagane)</em></label>
                        <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone) }}" required pattern="\d{9}" title="Numer telefonu: 9 cyfr">
                        <small class="form-text text-muted">9 cyfr.</small>
                        @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
 <div class="form-group mb-2">
                        <label for="role_id" class="form-label">Rola <em>(wymagane)</em></label>
                        <select id="role_id" name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">Wybierz rolę</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->role_id }}" @if (old('role_id', $user->role_id) == $role->role_id) selected @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                   

                   

                   

                    <div class="form-group mb-2" id="sportGroup">
                        <label for="sport_id" class="form-label">Dyscyplina <em>(wymagane dla trenerów i sportowców)</em></label>
                        <select id="sport_id" name="sport_id" class="form-select @error('sport_id') is-invalid @enderror">
                            <option value="">Wybierz dyscyplinę</option>
                            @foreach ($sports as $sport)
                                <option value="{{ $sport->sport_id }}" @if (old('sport_id', $user->sport_id) == $sport->sport_id) selected @endif>{{ $sport->name }}</option>
                            @endforeach
                        </select>
                        @error('sport_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                     <div class="form-group mb-2" id="pointsGroup">
                        <label for="points" class="form-label">Punkty <em>(wymagane dla sportowców)</em></label>
                        <input id="points" name="points" type="number" class="form-control @error('points') is-invalid @enderror"
                            value="{{ old('points', $user->points) }}">
                        <small class="form-text text-muted">Wymagane tylko dla sportowców.</small>
                        @error('points') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="approved" class="form-label">Zatwierdzony</label>
                        <select id="approved" name="approved" class="form-select @error('approved') is-invalid @enderror" required>
                            <option value="0" @if (old('approved', $user->approved) == '0') selected @endif>Nie</option>
                            <option value="1" @if (old('approved', $user->approved) == '1') selected @endif>Tak</option>
                        </select>
                        @error('approved') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="photo" class="form-label">Zdjęcie</label>
                        <input id="photo" name="photo" type="file" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">Maksymalny rozmiar: 5MB.</small>
                        @error('photo') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-center mt-4 mb-4">
                        <input class="btn btn-success" type="submit" value="Zapisz zmiany">
                    </div>
                </form>

            
            </div>
        </div>
    </div>

    @include('shared.footer')

    <script>
        function updateFields() {
            const roleId = document.getElementById('role_id').value;
            const pointsGroup = document.getElementById('pointsGroup');
            const sportGroup = document.getElementById('sportGroup');

            // Admin (1) – ukryj punkty i sport
            if(roleId == '1') {
                pointsGroup.style.display = 'none';
                sportGroup.style.display = 'none';
                document.getElementById('points').required = false;
                document.getElementById('sport_id').required = false;
            }
            // Trener (2) – ukryj punkty, pokaz sport
            else if(roleId == '2') {
                pointsGroup.style.display = 'none';
                sportGroup.style.display = 'block';
                document.getElementById('points').required = false;
                document.getElementById('sport_id').required = true;
            }
            // Sportowiec (3) – pokaz punkty i sport
            else if(roleId == '3') {
                pointsGroup.style.display = 'block';
                sportGroup.style.display = 'block';
                document.getElementById('points').required = true;
                document.getElementById('sport_id').required = true;
            }
            else {
                pointsGroup.style.display = 'none';
                sportGroup.style.display = 'none';
                document.getElementById('points').required = false;
                document.getElementById('sport_id').required = false;
            }
        }

        document.getElementById('role_id').addEventListener('change', updateFields);
        window.addEventListener('DOMContentLoaded', updateFields); // uruchom przy załadowaniu strony
    </script>

</body>
</html>

@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dodaj nowego użytkownika'])

<body>
@include('shared.navbar')

<div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')

    <div class="row text-center mb-4">
        <h1>Dodaj nowego użytkownika</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- ================= IMIĘ ================= --}}
                <div class="mb-3">
                    <label class="form-label">Imię <em>(wymagane)</em></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control"
                           required maxlength="80"
                           pattern="[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź]{1,}">
                    <small class="text-muted">
                        Pierwsza litera duża, min. 2 znaki, bez cyfr.
                    </small>
                </div>

                {{-- ================= NAZWISKO ================= --}}
                <div class="mb-3">
                    <label class="form-label">Nazwisko <em>(wymagane)</em></label>
                    <input type="text" name="surname" value="{{ old('surname') }}"
                           class="form-control"
                           required maxlength="80"
                           pattern="[A-ZĄĆĘŁŃÓŚŻŹ][a-ząćęłńóśżź\-]{1,}">
                    <small class="text-muted">
                        Min. 2 znaki.
                    </small>
                </div>

                {{-- ================= EMAIL ================= --}}
                <div class="mb-3">
                    <label class="form-label">Email <em>(wymagane)</em></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control"
                           required maxlength="150">
                </div>

                {{-- ================= TELEFON ================= --}}
                <div class="mb-3">
                    <label class="form-label">Telefon <em>(wymagane)</em></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-control"
                           required pattern="\d{9}">
                    <small class="text-muted">
                        Dokładnie 9 cyfr.
                    </small>
                </div>

                {{-- ================= DATA URODZENIA ================= --}}
                <div class="mb-3">
                    <label class="form-label">Data urodzenia <em>(wymagane)</em></label>
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}"
                           class="form-control"
                           required min="1920-01-01" max="{{ now()->toDateString() }}">
                </div>

                {{-- ================= HASŁO ================= --}}
                <div class="mb-3">
                    <label class="form-label">Hasło <em>(wymagane)</em></label>
                    <input type="password" name="password"
                           class="form-control"
                           required minlength="8">
                    <small class="text-muted">
                        Min. 8 znaków.
                    </small>
                </div>

                {{-- ================= ROLA ================= --}}
                <div class="mb-3">
                    <label class="form-label">Rola <em>(wymagane)</em></label>
                    <select name="role_id" id="roleSelect" class="form-select" required>
                        <option value="">Wybierz rolę</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->role_id }}"
                                {{ old('role_id') == $role->role_id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ================= SPORT ================= --}}
                <div class="mb-3 role-field" id="sportField">
                    <label class="form-label">Dyscyplina <em>(wymagane dla trenerów i sportowców)</em></label>
                    <select name="sport_id" class="form-select">
                        <option value="">Wybierz dyscyplinę</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}">
                                {{ $sport->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ================= KATEGORIA ================= --}}
                <div class="mb-3 role-field" id="categoryField">
                    <label class="form-label">Kategoria <em>(wymagane dla sportowców)</em></label>
                    <select name="category_id" class="form-select">
                        <option value="">Wybierz kategorię</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ================= PUNKTY ================= --}}
                <div class="mb-3 role-field" id="pointsField">
                    <label class="form-label">Punkty <em>(wymagane dla sportowców)</em></label>
                    <input type="number" name="points"
                           class="form-control"
                           min="0" max="100000">
                   
                </div>

                {{-- ================= ZATWIERDZONY ================= --}}
                <div class="mb-3">
                    <label class="form-label">Zatwierdzony <em>(wymagane)</em></label>
                    <select name="approved" class="form-select">
                        <option value="0">Nie</option>
                        <option value="1">Tak</option>
                    </select>
                </div>

                {{-- ================= ZDJĘCIE ================= --}}
                <div class="mb-3">
                    <label class="form-label">Zdjęcie</label>
                    <input type="file" name="photo"
                           class="form-control"
                           accept="image/*">
                    <small class="text-muted">
                        Maks. 5MB.
                    </small>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-success">Dodaj użytkownika</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('shared.footer')

{{-- ================= JS LOGIKA RÓL ================= --}}
<script>
    const roleSelect = document.getElementById('roleSelect');
    const sport = document.getElementById('sportField');
    const category = document.getElementById('categoryField');
    const points = document.getElementById('pointsField');

    function updateFields() {
        const role = roleSelect.options[roleSelect.selectedIndex]?.text.toLowerCase();

        sport.style.display = 'none';
        category.style.display = 'none';
        points.style.display = 'none';

        if (role.includes('sport')) {
            sport.style.display = '';
            category.style.display = '';
            points.style.display = '';
        }

        if (role.includes('trener')) {
            sport.style.display = '';
        }
    }

    roleSelect.addEventListener('change', updateFields);
    document.addEventListener('DOMContentLoaded', updateFields);
</script>

</body>
</html>

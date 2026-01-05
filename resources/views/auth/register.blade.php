@include('shared.html')
@include('shared.head', ['pageTitle' => 'Rejestracja'])

@include('shared.navbar')

<div class="container mt-5" style="max-width: 500px;">
    <h1 class="mb-4">Rejestracja</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Imię <em>(wymagane)</em></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="80" autofocus class="form-control">
             <small class="form-text text-muted">
        Pierwsza litera duża, min. 2 znaki, bez znaków specjalnych.
    </small>
            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="surname" class="form-label">Nazwisko <em>(wymagane)</em></label>
            <input id="surname" type="text" name="surname" value="{{ old('surname') }}" required maxlength="80" class="form-control">
             <small class="form-text text-muted">
        Min. 2 znaki.
    </small>
            @error('surname') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefon <em>(wymagane)</em></label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required pattern="\d{9,11}" title="Telefon musi zawierać 9 cyfr" class="form-control">
             <small class="form-text text-muted">
9 cyfr.    </small>
            @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adres email <em>(wymagane)</em></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="150" class="form-control">
            @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="birthdate" class="form-label">Data urodzenia <em>(wymagane)</em></label>
            <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required min="1920-01-01" max="{{ now()->toDateString() }}"
 class="form-control">
            @error('birthdate') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło <em>(wymagane)</em></label>
            <input id="password" type="password" name="password" required minlength="8" class="form-control">
            <small class="form-text text-muted">
Min. 8 znaków.    </small>
            @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Powtórz hasło <em>(wymagane)</em></label>
            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" class="form-control">
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
            <a href="{{ route('login') }}">Już zarejestrowany?</a>
            <button type="submit" class="btn btn-primary">Zarejestruj się</button>
        </div>
        <div></div>
    </form>
</div>

@include('shared.footer')

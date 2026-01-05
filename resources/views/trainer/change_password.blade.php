@extends('shared.html')
@include('shared.head', ['pageTitle' => 'Zmień Hasło'])

<body>
@include('shared.navbar')

<div class="container mt-5">
    <h2>Zmień Hasło</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('trainer.changePassword') }}" method="POST">
        @csrf

        <div class="mb-3 col-md-6">
            <label for="current_password" class="form-label">Obecne hasło</label>
            <input type="password" name="current_password" id="current_password"
                   class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label for="new_password" class="form-label">Nowe hasło</label>
            <input type="password" name="new_password" id="new_password"
                   class="form-control @error('new_password') is-invalid @enderror" required>
            @error('new_password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 col-md-6">
            <label for="new_password_confirmation" class="form-label">Powtórz nowe hasło</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Zmień hasło</button>
        <a href="{{ route('trainer.profile') }}" class="btn btn-secondary">Anuluj</a>
    </form>
</div>

@include('shared.footer')
</body>
</html>

@include('shared.html')
@include('shared.head', ['pageTitle' => 'Logowanie'])

@include('shared.navbar')

<div class="container mt-5" style="max-width: 400px;">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h1 class="mb-4">Logowanie</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
             @error('email')
                <div class="text-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
            <label for="email" class="form-label">Adres email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
           
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło</label>
            <input id="password" type="password" name="password" required class="form-control">
            @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="remember" id="remember_me" class="form-check-input">
            <label for="remember_me" class="form-check-label">Zapamiętaj mnie</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">Zaloguj się</button>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Zapomniałeś hasło?</a>
            @endif
        </div>
    </form>
</div>

@include('shared.footer')

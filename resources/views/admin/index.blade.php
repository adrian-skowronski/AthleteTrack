@include('shared.html')
@include('shared.head', ['pageTitle' => 'Mój Panel'])

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

<div class="container mt-3">
<h2>Twoje Dane</h2>
    <ul>
        <li><strong>Imię:</strong> {{ $admin->name }}</li>
        <li><strong>Nazwisko:</strong> {{ $admin->surname }}</li>
        <li><strong>Email:</strong> {{ $admin->email }}</li>
        <li><strong>Telefon:</strong> {{ $admin->phone }}</li>
    </ul>

    <div class="mt-3">
        <a href="{{ route('admin.edit', $admin->user_id) }}" class="btn btn-warning">Edytuj dane</a>
        <a href="{{ route('admin.changePasswordForm') }}" class="btn btn-secondary">Zmień hasło</a>
    </div>

    <div class="mt-3">
        @if($admin->photo)
            <img src="{{ asset('storage/' . $admin->photo) }}" alt="Zdjęcie admina" class="user-photo">
        @else
            <div><i>Brak zdjęcia.</i></div>
        @endif
    </div>
</div>

@include('shared.footer')
</body>
</html>

@include('shared.html')
@include('shared.head', ['pageTitle' => 'Oczekiwanie na weryfikację'])
@include('shared.navbar')
<div class="container mt-5 d-flex flex-column align-items-center justify-content-center verification-container">
    <div class="alert alert-warning text-center verification-alert" role="alert">
        Twoje konto czeka na weryfikację przez administratora.
    </div>
    <a href="{{ route('start.index') }}" class="btn btn-primary mt-3 verification-button">Powróć do strony głównej</a>
</div>
@include('shared.footer')

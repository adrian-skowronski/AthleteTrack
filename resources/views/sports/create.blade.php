@include('shared.html')
@include('shared.head', ['pageTitle' => 'Dodaj nową dyscyplinę'])

<body>
    @include('shared.navbar')

    <div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')

    <div class="row mt-4 mb-4 text-center">
            <h1>Dodaj nową dyscyplinę</h1>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-6">
                <form method="POST" action="{{ route('admin.sports.store') }}">
                    @csrf

                    <div class="form-group mb-2">
                        <label for="name" class="form-label">Nazwa dyscypliny</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="100">
                        <div class="invalid-feedback">Nieprawidłowy opis!</div>
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

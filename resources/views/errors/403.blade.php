@include('shared.html')
    @include('shared.head', ['pageTitle' => 'Błąd'])

    <body>
        @include('shared.navbar')
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <img src="{{ asset('error.jpg') }}" alt="Error Image" class="img-fluid">
                    <h2 class="mt-2">Wystąpił błąd <b> {{ $exception->getStatusCode() }}</b>!</h2>
                    
                </div>
            </div>
        </div>
        @include('shared.footer')
    </body>
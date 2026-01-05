<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Klub Sokół!</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        .navbar { background-color: #0077b6 !important; position: fixed; width: 100%; top: 0; z-index: 9999; }
        .navbar-brand, .navbar-nav .nav-link { color: white !important; }
        .navbar-nav .nav-link.active, .dropdown-item.active { font-weight: bold; color: #caf0f8 !important; }
        .hero-image { background-image: url('hero_image.jpg'); background-size: cover; background-position: center; height: 500px; opacity: 0.7; position: relative; }
        .hero-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #FAFDFF; text-align: center; font-weight: bold; text-shadow: 6px 6px 6px #000000; }
        .footer { background-color: #343a40; color: white; text-align: center; width: 100%; z-index: 9999; padding: 1rem 0; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    @include('shared.navbar')

    <!-- HERO SECTION -->
    <div class="container-fluid p-0 mt-5">
        <div class="hero-image">
            <div class="hero-text">
                <h1><b>Gotowy na niezapomniane przeżycia?</b></h1>
                <p><b>Klub Lekkoatletyczny Sokół czeka na Twój talent!</b></p>
                <a href="#oferta" class="btn btn-primary"><b>Zaczynamy!</b></a>
            </div>
        </div>
    </div>

    <!-- OFERTA -->
    <section id="oferta">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="zawody.jpg" class="card-img-top" alt="Zawody">
                        <div class="card-body">
                            <h5 class="card-title">Zawody</h5>
                            <p class="card-text">Zapoznaj się z kalendarzem zawodów</p>
                            <a href="{{ route('events.view') }}" class="btn btn-primary">Więcej</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="trening.jpg" class="card-img-top" alt="Treningi">
                        <div class="card-body">
                            <h5 class="card-title">Treningi</h5>
                            <p class="card-text">Zapoznaj się z kalendarzem treningów</p>
                            <a href="{{ route('trainings.view') }}" class="btn btn-primary">Więcej</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    @include('shared.footer')

    <!-- Bootstrap JS -->
</body>
</html>

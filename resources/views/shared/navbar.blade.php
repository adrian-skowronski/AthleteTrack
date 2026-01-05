<style>
    .navbar {
        background-color: #0077b6 !important;
    }

    .navbar-brand,
    .navbar-nav .nav-link,
    .navbar-nav .btn-link {
        color: white !important;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .btn-link:hover {
        color: #caf0f8 !important;
        text-decoration: none;
    }

    .navbar .nav-link.active,
    .navbar .dropdown-item.active {
        font-weight: bold;
        color: #caf0f8 !important;
        background-color: transparent !important;
    }
</style>

 <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('start.index') }}"><b>Klub Sokół</b></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarText">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                {{-- ===================== --}}
                {{-- TRENINGI --}}
                {{-- ===================== --}}
                <li class="nav-item">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="nav-link
                                {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}"
                                href="{{ route('admin.trainings.index') }}">
                                Treningi
                            </a>
                        @else
                            <a class="nav-link
                                {{ request()->routeIs('trainings.*') ? 'active' : '' }}"
                                href="{{ route('trainings.view') }}">
                                Treningi
                            </a>
                        @endif
                    @else
                        <a class="nav-link
                            {{ request()->routeIs('trainings.*') ? 'active' : '' }}"
                            href="{{ route('trainings.view') }}">
                            Treningi
                        </a>
                    @endauth
                </li>

                {{-- ===================== --}}
                {{-- ZAWODY --}}
                {{-- ===================== --}}
                <li class="nav-item">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="nav-link
                                {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                                href="{{ route('admin.events.index') }}">
                                Zawody
                            </a>
                        @else
                            <a class="nav-link
                                {{ request()->routeIs('events.*') ? 'active' : '' }}"
                                href="{{ route('events.view') }}">
                                Zawody
                            </a>
                        @endif
                    @else
                        <a class="nav-link
                            {{ request()->routeIs('events.*') ? 'active' : '' }}"
                            href="{{ route('events.view') }}">
                            Zawody
                        </a>
                    @endauth
                </li>

                {{-- ===================== --}}
                {{-- ADMIN --}}
                {{-- ===================== --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link
                                {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                Użytkownicy
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link
                                {{ request()->routeIs('admin.sports.*') ? 'active' : '' }}"
                                href="{{ route('admin.sports.index') }}">
                                Dyscypliny
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link
                                {{ request()->routeIs('admin.index') ? 'active' : '' }}"
                                href="{{ route('admin.index') }}">
                                Mój Panel
                            </a>
                        </li>
                    @elseif(auth()->user()->isCoach())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle
                                {{ request()->routeIs('trainer.*') ? 'active' : '' }}"
                                href="#" data-bs-toggle="dropdown">
                                Mój Panel
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item
                                        {{ request()->routeIs('trainer.profile') ? 'active' : '' }}"
                                        href="{{ route('trainer.profile') }}">
                                        Moje Dane
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item
                                        {{ request()->routeIs('trainer.trainings') ? 'active' : '' }}"
                                        href="{{ route('trainer.trainings') }}">
                                        Moje Treningi
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @elseif(auth()->user()->isAthlete())
                        <li class="nav-item">
                            <a class="nav-link
                                {{ request()->routeIs('athlete.panel') ? 'active' : '' }}"
                                href="{{ route('athlete.panel') }}">
                                Mój Panel
                            </a>
                        </li>
                    @endif

                    {{-- WYLOGOWANIE --}}
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">
                                Wyloguj się
                            </button>
                        </form>
                    </li>
                @else
                    {{-- GOŚĆ --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                            href="{{ route('login') }}">
                            Zaloguj się
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                            href="{{ route('register') }}">
                            Zarejestruj się
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

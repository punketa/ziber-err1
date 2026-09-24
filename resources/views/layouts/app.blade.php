<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKasKude</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ file_exists(public_path('css/style.css')) ? filemtime(public_path('css/style.css')) : time() }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shield-lock-fill me-1"></i> IkasKude
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('index.php') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-book me-1"></i> Ikastaroak
                        </a>
                    </li>
                    @auth
                        @if(Auth::user()->isIkasle())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('student.matriculas') ? 'active' : '' }}" href="{{ route('student.matriculas') }}">
                                    <i class="bi bi-journal-check me-1"></i> Nire Matrikulak
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle nav-link-admin {{ request()->is('administrazioa*') || request()->is('admin/*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear-fill me-1"></i> Administrazioa
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-custom">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Panel Orokorra</a></li>
                                    <li><hr class="dropdown-divider dropdown-divider-custom"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.cursos.index') }}"><i class="bi bi-mortarboard me-2"></i> Ikastaroak</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.usuarios.index') }}"><i class="bi bi-people me-2"></i> Erabiltzaileak</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.matriculas.index') }}"><i class="bi bi-card-checklist me-2"></i> Matrikulak</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>

                <div class="navbar-nav ms-auto align-items-center">
                    @guest
                        <a class="nav-link me-2" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Hasi Saioa
                        </a>
                        <a class="btn btn-cta btn-sm" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> Erregistratu
                        </a>
                    @else
                        <span class="navbar-text navbar-user-text me-3">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            <span class="badge {{ Auth::user()->isAdmin() ? 'badge-violet' : 'badge-green-deep' }} ms-1">
                                {{ Auth::user()->isAdmin() ? 'Administratzailea' : 'Ikaslea' }}
                            </span>
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-nav-logout btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i> Irten
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4 mb-5">
        @if(session('success'))
            <div class="alert alert-corporate-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-shield-exclamation me-2"></i> Errorea gertatu da:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-custom text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-0 fw-semibold footer-brand">Aplikazioen Garapena eta Web Plataforma</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}?v={{ file_exists(public_path('js/app.js')) ? filemtime(public_path('js/app.js')) : '1.0' }}"></script>
</body>
</html>

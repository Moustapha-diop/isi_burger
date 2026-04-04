<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISI BURGER')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --isi-red:    #e63946;
            --isi-gray:   #c2acac;
            --isi-yellow: #ea7312;
            --isi-dark:   #ffffff;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--isi-dark) !important;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--isi-yellow) !important;
        }

        .btn-isi {
            background: var(--isi-yellow);
            color: #fff;
            border: none;
        }

        .btn-isi:hover {
            background: #904a10;
            color: #fff;
        }

        /* ── BADGES STATUT ── */
        .badge-statut-en_attente     { background: #ffc107; color: #000; }
        .badge-statut-en_preparation { background: #0dcaf0; color: #000; }
        .badge-statut-prete          { background: #198754; color: #fff; }
        .badge-statut-payee          { background: #0d6efd; color: #fff; }
        .badge-statut-annulee        { background: #6c757d; color: #fff; }

        /* ── SIDEBAR DESKTOP ── */
        .sidebar {
            min-height: calc(100vh - 56px);
            background: var(--isi-gray);
        }

        .sidebar .nav-link { color: #1d1e1e; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color: var(--isi-yellow); }

        /* ── BOTTOM NAV MOBILE ── */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--isi-dark);
            border-top: 2px solid var(--isi-yellow);
            z-index: 1000;
            padding: 6px 0;
        }

        .bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #aaa;
            text-decoration: none;
            font-size: 0.7rem;
            padding: 4px;
            transition: color .2s;
        }

        .bottom-nav a i {
            font-size: 1.3rem;
            margin-bottom: 2px;
        }

        .bottom-nav a.active,
        .bottom-nav a:hover {
            color: var(--isi-yellow);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            /* Cacher la sidebar sur mobile */
            .sidebar {
                display: none !important;
            }

            /* Afficher la bottom nav sur mobile */
            .bottom-nav {
                display: flex;
            }

            /* Ajouter espace en bas pour la bottom nav */
            .main-content {
                padding-bottom: 80px !important;
            }

            /* Colonne pleine largeur sur mobile */
            .col-md-10 {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Titre plus petit sur mobile */
            h4, h5 {
                font-size: 1.1rem;
            }

            /* Tableaux scrollables sur mobile */
            .table-responsive {
                overflow-x: auto;
            }

            /* Cards pleine largeur */
            .col-md-4, .col-md-6, .col-md-8 {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ISI BURGER</a>

        <div class="d-flex align-items-center gap-2">
            @auth
                <span class="text-light small d-none d-md-inline">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    <span class="badge bg-secondary ms-1">{{ Auth::user()->role }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Déconnexion</span>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

{{-- ── LAYOUT PRINCIPAL ── --}}
<div class="container-fluid">
    <div class="row">

        {{-- SIDEBAR - visible uniquement sur desktop --}}
        @auth
        <nav class="col-md-2 sidebar py-3 d-none d-md-block">
            <ul class="nav flex-column gap-1">
                @if(Auth::user()->isGestionnaire())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}"
                           href="{{ route('gestionnaire.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gestionnaire.burgers.*') ? 'active' : '' }}"
                           href="{{ route('gestionnaire.burgers.index') }}">
                            <i class="bi bi-grid me-2"></i>Burgers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gestionnaire.commandes.*') ? 'active' : '' }}"
                           href="{{ route('gestionnaire.commandes.index') }}">
                            <i class="bi bi-receipt me-2"></i>Commandes
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.catalogue') ? 'active' : '' }}"
                           href="{{ route('client.catalogue') }}">
                            <i class="bi bi-shop me-2"></i>Catalogue
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.commandes.*') ? 'active' : '' }}"
                           href="{{ route('client.commandes.index') }}">
                            <i class="bi bi-bag me-2"></i>Mes commandes
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        @endauth

        {{-- CONTENU PRINCIPAL --}}
        <main class="{{ auth()->check() ? 'col-md-10' : 'col-12' }} py-4 px-3 main-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

{{-- ── BOTTOM NAV MOBILE ── --}}
@auth
<nav class="bottom-nav">
    @if(Auth::user()->isGestionnaire())
        <a href="{{ route('gestionnaire.dashboard') }}"
           class="{{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('gestionnaire.burgers.index') }}"
           class="{{ request()->routeIs('gestionnaire.burgers.*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            <span>Burgers</span>
        </a>
        <a href="{{ route('gestionnaire.commandes.index') }}"
           class="{{ request()->routeIs('gestionnaire.commandes.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Commandes</span>
        </a>
    @else
        <a href="{{ route('client.catalogue') }}"
           class="{{ request()->routeIs('client.catalogue') ? 'active' : '' }}">
            <i class="bi bi-shop"></i>
            <span>Catalogue</span>
        </a>
        <a href="{{ route('client.commandes.index') }}"
           class="{{ request()->routeIs('client.commandes.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i>
            <span>Commandes</span>
        </a>
        <a href="{{ route('client.commandes.create') }}"
           class="{{ request()->routeIs('client.commandes.create') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i>
            <span>Commander</span>
        </a>
    @endif
</nav>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>
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
        }
        body {
             background: #f8f9fa;
              font-family: 'Segoe UI', sans-serif; 
            }

        .navbar-brand { 
            font-weight: 800; 
            font-size: 1.4rem; 
            color: var(--isi-yellow) !important; }

        .navbar {
             background: var(--isi-dark) !important; 
            }

        .btn-isi { 
            background: var(--isi-yellow); 
            color: #fff; 
            border: none; 
        }

        .btn-isi:hover { 
            background: #904a10; color: #fff; 
        }

        .badge-statut-en_attente    { 
            background: #ffc107;
             color: #000; 
        }

        .badge-statut-en_preparation{ 
            background: #0dcaf0; 
            color: #000;
         }

        .badge-statut-prete  {
             background: #198754;
              color: #fff;
             }
        .badge-statut-payee         { 
            background: #0d6efd; 
            color: #fff; 
        }
        .badge-statut-annulee       { 
            background: #6c757d; 
            color: #fff; 
        }
        .sidebar { 
            min-height: calc(100vh - 56px); background: var(--isi-gray); }
        .sidebar .nav-link { color: #1d1e1e; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: var(--isi-yellow); }
    </style>
    @stack('styles')
</head>
<body>
{{-- navBar --}}

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ISI BURGER</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                @auth
                    <li class="nav-item text-dark small">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        <span class="badge bg-secondary ms-1">{{ Auth::user()->role }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
{{-- side bar --}}
<div class="container-fluid">
    <div class="row">

       
        @auth
        <nav class="col-md-2 d-none d-md-block sidebar py-3">
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

        {{-- MAIN --}}
        <main class="{{ auth()->check() ? 'col-md-10' : 'col-12' }} py-4 px-4">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>

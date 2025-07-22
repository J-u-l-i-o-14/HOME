<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Blood Bank') }} - @yield('title', 'Gestion de Stock de Sang')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .blood-type-badge {
            font-weight: bold;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        .blood-A { background-color: #e3f2fd; color: #1976d2; }
        .blood-B { background-color: #f3e5f5; color: #7b1fa2; }
        .blood-AB { background-color: #e8f5e8; color: #388e3c; }
        .blood-O { background-color: #fff3e0; color: #f57c00; }
        .alert-low-stock { background-color: #ffebee; border-color: #f44336; color: #c62828; }
    </style>

    @stack('styles')
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-tint me-2"></i>
                            Blood Bank
                        </h4>
                        <small class="text-white-50">{{ auth()->user()->name }}</small>
                        <br>
                        <span class="badge bg-light text-dark">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Tableau de bord
                            </a>
                        </li>

                        @if(auth()->user()->is_donor)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Mes Rendez-vous
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('campaigns.public') ? 'active' : '' }}" href="{{ route('campaigns.public') }}">
                                    <i class="fas fa-bullhorn me-2"></i>
                                    Campagnes
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->is_doctor || auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                    <i class="fas fa-user-injured me-2"></i>
                                    Patients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('transfusions.*') ? 'active' : '' }}" href="{{ route('transfusions.index') }}">
                                    <i class="fas fa-syringe me-2"></i>
                                    Transfusions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('blood-bags.stock') ? 'active' : '' }}" href="{{ route('blood-bags.stock') }}">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Stock de Sang
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->is_admin)
                            <hr class="text-white-50">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                    <i class="fas fa-users me-2"></i>
                                    Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('blood-bags.*') ? 'active' : '' }}" href="{{ route('blood-bags.index') }}">
                                    <i class="fas fa-tint me-2"></i>
                                    Poches de Sang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}" href="{{ route('donations.index') }}">
                                    <i class="fas fa-heart me-2"></i>
                                    Dons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}" href="{{ route('campaigns.index') }}">
                                    <i class="fas fa-bullhorn me-2"></i>
                                    Campagnes
                                </a>
                            </li>
                        @endif

                        <hr class="text-white-50">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-2"></i>
                                Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Tableau de bord')</h1>
                    @yield('page-actions')
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
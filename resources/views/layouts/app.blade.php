<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #f8fafc; }
        .sidebar { min-height: 100vh; background: #fff; border-right: 1px solid #e2e8f0; }
        .nav-link { color: #475569; font-weight: 500; }
        .nav-link.active { color: #10b981; }
        .nav-link:hover { color: #10b981; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3 d-none d-md-block" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4 fw-bold">{{ config('app.name') }}</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        Perfil
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link w-100 text-start">Sair</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom p-3 d-md-none">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold" href="/">{{ config('app.name') }}</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-content="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">Perfil</a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link p-0 text-start">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <header class="bg-white border-bottom py-3 px-4 shadow-sm mb-4">
                <h2 class="h5 mb-0 fw-bold">@yield('header')</h2>
            </header>

            <div class="container-fluid px-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

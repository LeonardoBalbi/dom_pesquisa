<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'DOECA'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @stack('styles')

    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #f8fafc; }
        .navbar { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .card { border: 1px solid #e2e8f0; border-radius: 0.5rem; transition: transform 0.2s; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .btn-primary { background-color: #10b981; border-color: #10b981; }
        .btn-primary:hover { background-color: #059669; border-color: #059669; }
        
        /* Efeito Marca-texto */
        .highlight {
            background-color: #fef08a; /* Amarelo suave */
            color: #854d0e; /* Marrom escuro para contraste */
            padding: 0 2px;
            border-radius: 2px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header class="bg-white border-bottom py-3 mb-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <p class="text-uppercase text-muted small mb-0 fw-bold" style="letter-spacing: 0.05em;">Diário Oficial Eletrônico</p>
                <h1 class="h3 mb-0 fw-semibold text-dark">
                    {{ config('doeca.municipio', 'Município') }}
                    <span class="text-muted fw-normal">— {{ config('doeca.estado', 'PR') }}</span>
                </h1>
            </div>
            <div class="mt-3 mt-md-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-dark fw-medium">Área logada</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark px-4 py-2 me-2">Entrar</a>
                @endauth
                <a href="/nova" class="btn btn-outline-secondary px-4 py-2">Administração</a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-4 mt-5 text-center text-muted small">
        <div class="container">
            {{ config('doeca.rodape', 'Feito com ❤ para o serviço público') }}
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

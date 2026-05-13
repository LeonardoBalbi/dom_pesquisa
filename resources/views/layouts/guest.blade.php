<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8fafc; display: flex; align-items: center; min-height: 100vh; }
        .auth-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 1rem; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card shadow-sm auth-card bg-white">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none h4 fw-bold text-dark">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>

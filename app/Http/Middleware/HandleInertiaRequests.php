<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'doeca' => [
                'municipio' => config('doeca.municipio'),
                'estado' => config('doeca.estado'),
                'rodape' => config('doeca.rodape'),
            ],
            'ziggy' => fn () => (object) collect(Route::getRoutes()->getRoutesByName())
                ->mapWithKeys(fn ($route, $name) => [$name => [
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'domain' => $route->domain(),
                ]])->toArray(),
        ];
    }
}

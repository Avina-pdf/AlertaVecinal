<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias de middlewares de rutas
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // (opcional) agregar globales o de grupo:
        // $middleware->append(SomeGlobalMiddleware::class);
        // $middleware->web(append: [ ... ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

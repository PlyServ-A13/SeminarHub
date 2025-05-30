<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // Assurez-vous que cette ligne est présente

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrez votre alias de middleware ici
        $middleware->alias([ // <--- AJOUTEZ CETTE SECTION
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);                 // <--- FIN DE L'AJOUT
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
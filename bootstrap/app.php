<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['api'])
                ->prefix('api')
                ->name('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ❌ REMOVE Sanctum cookie-based SPA guard 
        // (You are using token login, not cookie login)
        $middleware->api(prepend: [
            // No Sanctum EnsureFrontendRequestsAreStateful here
        ]);

        // Custom middleware aliases
        $middleware->alias([
            'abilities' => \App\Http\Middleware\Abilities::class,
        ]);

        // Web-only middleware
        $middleware->web([
            \App\Http\Middleware\SetLocale::class,
        ]);

        // ✅ Fix CSRF for API routes
        $middleware->validateCsrfTokens(except: [
            'api/*',   // <-- IMPORTANT
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Custom API unauthenticated response
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
        });

        // Custom API validation error response
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ]);
        });
    })
    ->create();

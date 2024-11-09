<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Define custom middleware if needed
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Report or handle specific exceptions
        $exceptions->report(function (\Throwable $e) {
            // You can log or report the exception here if needed
            return response()->json([
                'error' => 'Unauthorized. Please provide a valid token.'
            ], 401);
    });


        // Customize how AuthenticationException is rendered
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            // Check if the request expects a JSON response (for API requests)
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized. Please provide a valid token.'
                ], 401);
            }

            // If the request is not for API, you can redirect to a login page or show an error
            return redirect()->guest(route('login'));
        });

    })
    ->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use \App\Http\Middleware\EnsureUserRoleIs;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \GoogleOneTap\Services\GoogleOneTapServiceProvider::class,
    ])
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api']],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureUserRoleIs::class
        ]);
        $middleware->statefulApi();
        $middleware->validateCsrfTokens(except: [
            'api/broadcasting/auth',
        ]);
        $middleware->encryptCookies(except: [
            'guest_id', 'language', 'currency', 'i18n_redirected', 'guest_token'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

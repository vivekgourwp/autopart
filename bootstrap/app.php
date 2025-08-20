<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckUser;
use App\Http\Middleware\CheckSeller;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // ✅ must be before SetLocale
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        //
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'admin' => \App\Http\Middleware\IsAdminMiddleware::class,
            'user' => \App\Http\Middleware\CheckUser::class,
            'seller' => \App\Http\Middleware\CheckSeller::class
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

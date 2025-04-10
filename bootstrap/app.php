<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SetDatabaseConnection;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\App;
use Native\Laravel\Http\Middleware\PreventRegularBrowserAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      $middlewareAppends = [
        HandleInertiaRequests::class,
        SetDatabaseConnection::class
      ];

      $middleware->web(append: $middlewareAppends);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

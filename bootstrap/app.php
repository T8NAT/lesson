<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'RedirectIfAuthenticated' => RedirectIfAuthenticated::class,
        ]);

        $middleware->redirectTo(function ($request){
            if (!$request->expectsJson()) {
                if (auth()->guard('user')->check()) {
                    return redirect()->route('show-login');
                }else{
                    return route('show-login');
                }
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();

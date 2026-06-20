<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Railway (and most PaaS hosts) terminate TLS at their edge proxy and forward
        // requests over plain HTTP. Trust that proxy's X-Forwarded-* headers so Laravel
        // knows the original request was HTTPS — otherwise it generates http:// URLs
        // for assets, which browsers block as mixed content on an https:// page.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

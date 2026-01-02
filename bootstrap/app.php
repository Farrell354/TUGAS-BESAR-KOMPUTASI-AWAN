<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // <--- 1. TAMBAHAN PENTING (Jangan dihapus)

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitors::class,
        ]);

        // --- 2. BAGIAN INI DIPERBARUI UNTUK AZURE ---
        $middleware->trustProxies(at: [
            '*',
        ], headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );
        // ---------------------------------------------
        
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'isOwner' => \App\Http\Middleware\IsOwner::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans-callback', // Izinkan URL ini diakses tanpa token CSRF
        ]);

        // (Kode duplikat Anda di bawah ini saya biarkan sesuai permintaan 'tanpa ubah kode lama')
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'isOwner' => \App\Http\Middleware\IsOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

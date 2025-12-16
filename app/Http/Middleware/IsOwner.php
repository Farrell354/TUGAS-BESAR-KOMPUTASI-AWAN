<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Apakah user sudah login?
        // Cek 2: Apakah role-nya 'owner' ATAU 'admin'? (Admin kita izinkan juga supaya mudah memantau)
        if (Auth::check() && (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        // Jika bukan owner, tendang ke halaman utama
        return redirect('/');
    }
}

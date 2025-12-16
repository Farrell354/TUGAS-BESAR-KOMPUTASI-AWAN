<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $role = $request->user()->role;

        // 1. Redirect Admin
        if ($role === 'admin') {
            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat Datang Admin!');
        }

        // 2. Redirect Owner (BARU)
        if ($role === 'owner') {
            return redirect()->intended('/owner/dashboard')->with('success', 'Selamat Datang di Bengkel Anda!');
        }

        // 3. Redirect User Biasa
        return redirect()->intended('/')->with('success', 'Login Berhasil!');
    }
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Pesan Sukses Logout
        return redirect('/')->with('success', 'Logout Berhasil! Sampai Jumpa.');
    }
}

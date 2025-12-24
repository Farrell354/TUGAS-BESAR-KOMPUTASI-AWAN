<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    // 1. Arahkan user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle balikan dari Google
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada berdasarkan google_id
            $finduser = User::where('google_id', $googleUser->id)->first();

            if($finduser){
                // Jika ada, langsung login
                Auth::login($finduser);
                return redirect()->intended('dashboard'); // Sesuaikan mau diarahkan kemana
            }else{
                // Cek apakah email sudah terdaftar manual sebelumnya?
                $existingUser = User::where('email', $googleUser->email)->first();

                if($existingUser){
                    // Jika email ada tapi belum connect Google, update google_id nya
                    $existingUser->update(['google_id' => $googleUser->id]);
                    Auth::login($existingUser);
                } else {
                    // Jika user benar-benar baru, buat user baru
                    $newUser = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id'=> $googleUser->id,
                        'password' => bcrypt('password_default_google_123') // Dummy password
                    ]);
                    Auth::login($newUser);
                }
                
                return redirect()->intended('dashboard');
            }

        } catch (Exception $e) {
            // Jika gagal/cancel
            return redirect('login')->with('error', 'Login Google Gagal: ' . $e->getMessage());
        }
    }
}
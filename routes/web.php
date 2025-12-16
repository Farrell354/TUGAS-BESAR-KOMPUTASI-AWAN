<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// 2. Halaman Peta (Untuk User Mencari Tambal Ban)
Route::get('/peta', function () {
    // Load relasi reviews agar bisa dihitung ratingnya
    $lokasi = \App\Models\TambalBan::with('reviews')->get();
    return view('peta', compact('lokasi'));
})->name('peta.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

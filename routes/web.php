<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TambalBanController;

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

// 3. Route Default Breeze (Dashboard, Profile, dll)
Route::get('/dashboard', function () {
    // Redirect user biasa ke halaman peta saja, Admin ke dashboard admin
    if (auth()->user()->role === 'admin') {
        return redirect()->route('dashboard'); // Nama route dashboard admin
    }
    return redirect('/');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Form Booking
    Route::get('/booking/{id}', [App\Http\Controllers\OrderController::class, 'create'])->name('booking.create');
    Route::post('/booking', [App\Http\Controllers\OrderController::class, 'store'])->name('booking.store');
    // Riwayat Booking
    Route::get('/riwayat-pesanan', [App\Http\Controllers\OrderController::class, 'history'])->name('booking.history');
    Route::get('/booking/{id}/detail', [App\Http\Controllers\OrderController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('booking.cancel');
});

// 4. Group Route Khusus Admin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [TambalBanController::class, 'index'])->name('dashboard');
    Route::get('/live-map', [TambalBanController::class, 'liveMap'])->name('admin.map');
    Route::resource('tambal-ban', TambalBanController::class);
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
});

// ROUTE GROUP OWNER
Route::middleware(['auth', 'isOwner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\OwnerController::class, 'index'])->name('owner.dashboard');
    Route::post('/order/{id}/update', [App\Http\Controllers\OwnerController::class, 'updateStatus'])->name('owner.order.update');
    Route::middleware(['auth', 'isOwner'])->prefix('owner')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\OwnerController::class, 'index'])->name('owner.dashboard');
    Route::post('/order/{id}/update', [App\Http\Controllers\OwnerController::class, 'updateStatus'])->name('owner.order.update');
    Route::get('/order/{id}', [App\Http\Controllers\OwnerController::class, 'show'])->name('owner.order.show');
    Route::get('/orders/{id}', [OrderController::class, 'adminShow'])->name('admin.orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
});
});

// ROUTE CHAT (Bisa diakses User & Owner)
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{order_id}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{order_id}/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{order_id}/get', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.get');
});

require __DIR__.'/auth.php';

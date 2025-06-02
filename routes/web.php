<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__.'/auth.php';

use App\Http\Controllers\AbsensiPegawaiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    // Route::get('/absensi-pegawai', [AbsensiPegawaiController::class, 'index'])->name('absensi-pegawai.index');
     Route::get('/', [AbsensiPegawaiController::class, 'dashboard'])->name('dashboard');
    Route::post('/absensi-pegawai', [AbsensiPegawaiController::class, 'store'])->name('absensi-pegawai.store');
});
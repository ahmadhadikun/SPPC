<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// ==================== DASHBOARD ====================

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==================== MANAJEMEN PENGGUNA ====================

// Menampilkan daftar pengguna
Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('users.index');

// Halaman tambah pengguna
Route::get('/users/create', [UserController::class, 'create'])
    ->middleware('auth')
    ->name('users.create');

// Menyimpan pengguna baru
Route::post('/users', [UserController::class, 'store'])
    ->middleware('auth')
    ->name('users.store');

// Halaman edit pengguna
Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('auth')
    ->name('users.edit');

// Memperbarui data pengguna
Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('users.update');

// Menghapus pengguna
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware('auth')
    ->name('users.destroy');


// ==================== PROFILE ====================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


// ==================== AUTHENTICATION ====================

require __DIR__.'/auth.php';
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PengambilanLaukController;
use App\Models\Santri;
use App\Models\QrCode;
use App\Models\PengambilanLauk;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk Pengguna Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard dengan Data Statistik
    Route::get('/dashboard', function () {
        $totalSantri = Santri::count();
        $presensiHariIni = PengambilanLauk::whereDate('waktu_ambil', Carbon::today())->count();
        $qrTerdaftar = QrCode::count();

        return view('dashboard', compact('totalSantri', 'presensiHariIni', 'qrTerdaftar'));
    })->name('dashboard');

    // Pengelolaan Data Santri (CRUD)
    Route::resource('santri', SantriController::class);

    // Fitur Pemindaian Kode QR Catering / Lauk
    Route::get('/scan', [PengambilanLaukController::class, 'index'])->name('scan.index');
    Route::post('/scan', [PengambilanLaukController::class, 'store'])->name('scan.store');
});
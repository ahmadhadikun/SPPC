<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\PengambilanLaukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\UserController;
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
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk Pengguna Terautentikasi (Sudah Login)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Dashboard berdasarkan Role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Role Admin
        if ($user->role === 'admin') {
            $totalSantri = Santri::count();
            $presensiHariIni = PengambilanLauk::whereDate(
                'created_at',
                Carbon::today()
            )->count();
            $qrTerdaftar = QrCode::count();

            return view(
                'dashboard.admin',
                compact('totalSantri', 'presensiHariIni', 'qrTerdaftar')
            );
        }

        // Role Pengurus Catering
        if ($user->role === 'catering') {
            return view('dashboard.catering');
        }

        // Role Pengasuh
        if ($user->role === 'pengasuh') {
            $presensiHariIni = PengambilanLauk::whereDate(
                'waktu_ambil',
                Carbon::today()
            )->count();

            return view('dashboard.pengasuh', compact('presensiHariIni'));
        }

        abort(403, 'Akses ditolak. Role tidak dikenali.');
    })->name('dashboard');

    // Pengelolaan Data Santri
    Route::resource('santri', SantriController::class);

    // Pengelolaan Jadwal Catering
    Route::resource('catering', CateringController::class)
        ->except(['show']);

    // Pemindaian QR Pengambilan Lauk
    Route::get('/scan', [PengambilanLaukController::class, 'index'])
        ->name('scan.index');

    Route::post('/scan', [PengambilanLaukController::class, 'store'])
        ->name('scan.store');

    // Laporan dan Unduh PDF
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])
        ->name('laporan.pdf');

    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])
        ->name('laporan.bulanan');

    Route::get('/laporan/bulanan/pdf', [LaporanController::class, 'pdfBulanan'])
        ->name('laporan.bulanan.pdf');    

    // Kelola Users — khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)
            ->except(['show']);
    });
});

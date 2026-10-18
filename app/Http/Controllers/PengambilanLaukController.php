<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\PengambilanLauk;
use App\Models\Presensi;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengambilanLaukController extends Controller
{
    /**
     * Menampilkan halaman scan QR dan riwayat hari ini.
     */
    public function index()
    {
        $riwayatHariIni = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->whereDate('waktu_ambil', Carbon::today())
            ->latest('idPengambilan')
            ->get();

        return view('scan.index', compact('riwayatHariIni'));
    }

    /**
     * Memproses scan QR untuk pengambilan lauk.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_qr' => ['required', 'string'],
            'waktu' => ['required', 'in:Pagi,Siang,Sore'],
        ], [
            'kode_qr.required' => 'Silakan scan QR Code KTS santri.',
            'waktu.required' => 'Silakan pilih sesi catering.',
            'waktu.in' => 'Sesi catering harus Pagi, Siang, atau Sore.',
        ]);

        $sekarang = Carbon::now();
        $tanggalHariIni = $sekarang->toDateString();
        $sesi = $validated['waktu'];

        // Cari QR Code.
        $qrCode = QrCode::with('santri')
            ->where('kode_qr', trim($validated['kode_qr']))
            ->first();

        if (!$qrCode) {
            return back()
                ->with('error', 'QR Code tidak terdaftar di dalam sistem!')
                ->withInput();
        }

        // Tolak QR yang tidak aktif.
        if ((int) $qrCode->status !== 1) {
            return back()
                ->with('error', 'QR Code ini tidak aktif. Silakan hubungi admin!')
                ->withInput();
        }

        // Pastikan data santri tersedia.
        $santri = $qrCode->santri;

        if (!$santri) {
            return back()
                ->with('error', 'Data santri pemilik QR ini tidak ditemukan!')
                ->withInput();
        }

        // Cari jadwal catering hari ini sesuai sesi.
        $catering = Catering::whereDate('tanggal', $tanggalHariIni)
            ->where('sesi', $sesi)
            ->first();

        if (!$catering) {
            return back()
                ->with(
                    'error',
                    "Data catering sesi {$sesi} hari ini belum tersedia!"
                )
                ->withInput();
        }

        // Periksa apakah santri sudah mengambil pada catering yang sama.
        $sudahAmbil = PengambilanLauk::where(
                'santri_id',
                $santri->idSantri
            )
            ->where('catering_id', $catering->idCatering)
            ->exists();

        if ($sudahAmbil) {
            return back()
                ->with(
                    'error',
                    "Santri {$santri->nama_santri} (Kamar: {$santri->kamar}) "
                    . "sudah mengambil lauk pada sesi {$sesi} hari ini!"
                )
                ->withInput();
        }

        // Simpan presensi dan pengambilan lauk secara bersamaan.
        DB::transaction(function () use (
            $santri,
            $catering,
            $sekarang,
            $tanggalHariIni
        ) {
            Presensi::create([
                'santri_id' => $santri->idSantri,
                'catering_id' => $catering->idCatering,
                'tanggal' => $tanggalHariIni,
                'waktu' => $sekarang->format('H:i:s'),
                'status' => 'Hadir',
            ]);

            PengambilanLauk::create([
                'santri_id' => $santri->idSantri,
                'catering_id' => $catering->idCatering,
                'user_id' => Auth::id(),
                'waktu_ambil' => $sekarang,
                'status_ambil' => 'Sudah Ambil',
            ]);
        });

        return back()->with(
            'success',
            "Berhasil! Jatah lauk untuk {$santri->nama_santri} "
            . "(Kamar: {$santri->kamar}) pada sesi {$sesi} "
            . "berhasil dicatat."
        );
    }
}

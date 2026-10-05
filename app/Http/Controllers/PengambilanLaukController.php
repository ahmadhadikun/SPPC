<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\PengambilanLauk;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengambilanLaukController extends Controller
{
    /**
     * Menampilkan halaman scan dan riwayat pengambilan hari ini.
     */
    public function index()
    {
        $riwayatHariIni = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->whereDate('waktu_ambil', Carbon::today())
            ->latest('waktu_ambil')
            ->get();

        return view('scan.index', compact('riwayatHariIni'));
    }

    /**
     * Memproses QR Code yang di-scan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_qr' => ['required', 'string'],
        ]);

        $qrCode = QrCode::with('santri')
            ->where('kode_qr', $validated['kode_qr'])
            ->first();

        if (!$qrCode) {
            return back()->with(
                'error',
                'Kode QR tidak terdaftar di dalam sistem!'
            );
        }

        if (!$qrCode->status) {
            return back()->with(
                'error',
                'Kode QR ini sudah nonaktif!'
            );
        }

        $santri = $qrCode->santri;

        if (!$santri) {
            return back()->with(
                'error',
                'Data santri pemilik QR ini tidak ditemukan!'
            );
        }

        // Cek apakah santri sudah mengambil lauk hari ini.
        $sudahAmbil = PengambilanLauk::where(
            'santri_id',
            $santri->idSantri
        )
            ->whereDate('waktu_ambil', Carbon::today())
            ->exists();

        if ($sudahAmbil) {
            return back()->with(
                'error',
                "PERINGATAN: Santri {$santri->nama_santri} " .
                "(Kamar: {$santri->kamar}) sudah mengambil " .
                "jatah lauk hari ini!"
            );
        }

        // Ambil catering untuk hari ini.
        $catering = Catering::whereDate(
            'tanggal',
            Carbon::today()
        )->first();

        if (!$catering) {
            return back()->with(
                'error',
                'Data catering untuk hari ini belum tersedia!'
            );
        }

        // Simpan data pengambilan.
        PengambilanLauk::create([
            'santri_id' => $santri->idSantri,
            'catering_id' => $catering->idCatering,
            'user_id' => auth()->id(),
            'waktu_ambil' => Carbon::now(),
            'status_ambil' => 'Sudah Ambil',
        ]);

        return back()->with(
            'success',
            "Berhasil! Jatah lauk untuk {$santri->nama_santri} " .
            "(Kamar: {$santri->kamar}) telah dicatat."
        );
    }
}
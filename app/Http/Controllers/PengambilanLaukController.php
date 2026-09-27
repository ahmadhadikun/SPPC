<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\PengambilanLauk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengambilanLaukController extends Controller
{
    // Menampilkan halaman scan dan riwayat pengambilan hari ini
    public function index()
    {
        $riwayatHariIni = PengambilanLauk::with('santri')
            ->whereDate('waktu_ambil', Carbon::today())
            ->latest('waktu_ambil')
            ->get();

        return view('scan.index', compact('riwayatHariIni'));
    }

    // Memproses data QR Code yang di-scan
    public function store(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string',
        ]);

        $qrCode = QrCode::where('kode_qr', $request->kode_qr)->first();

        if (!$qrCode) {
            return back()->with('error', 'Kode QR tidak terdaftar di dalam sistem!');
        }

        if (!$qrCode->status) {
            return back()->with('error', 'Kode QR ini sudah nonaktif!');
        }

        $santri = $qrCode->santri;

        if (!$santri) {
            return back()->with('error', 'Data santri pemilik QR ini tidak ditemukan!');
        }

        // Cek apakah santri sudah mengambil lauk hari ini
        $sudahAmbil = PengambilanLauk::where('santri_id', $santri->idSantri)
            ->whereDate('waktu_ambil', Carbon::today())
            ->exists();

        if ($sudahAmbil) {
            return back()->with('error', "PERINGATAN: Santri atas nama {$santri->nama_santri} (Kamar: {$santri->kamar}) SUDAH mengambil jatah lauk hari ini!");
        }

        // Catat pengambilan lauk ke database
        PengambilanLauk::create([
            'santri_id' => $santri->idSantri,
            'waktu_ambil' => Carbon::now(),
        ]);

        return back()->with('success', "Berhasil! Jatah lauk untuk santri {$santri->nama_santri} (Kamar: {$santri->kamar}) telah dicatat.");
    }
}
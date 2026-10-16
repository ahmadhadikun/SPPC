<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\PengambilanLauk;
use App\Models\Presensi;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengambilanLaukController extends Controller
{
    /**
     * Menampilkan halaman scan dan riwayat pengambilan hari ini.
     */
    public function index()
    {
        $riwayatHariIni = PengambilanLauk::with([
            'presensi.santri',
            'presensi.catering',
        ])
            ->whereHas('presensi', function ($query) {
                $query->whereDate('waktu_scan', Carbon::today());
            })
            ->latest('id_pengambilan')
            ->get();

        return view('scan.index', compact('riwayatHariIni'));
    }

    /**
     * Memproses QR Code pengambilan lauk.
     * Setiap santri hanya boleh mengambil satu kali
     * pada setiap sesi per hari.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_qr' => ['required', 'string'],
            'waktu' => ['required', 'in:Pagi,Siang,Malam'],
        ]);

        // Cari QR Code dan data santri pemiliknya.
        $qrCode = QrCode::with('santri')
            ->where('kode_qr', trim($validated['kode_qr']))
            ->first();

        if (!$qrCode) {
            return back()
                ->with('error', 'Kode QR tidak terdaftar di dalam sistem!')
                ->withInput();
        }

        $santri = $qrCode->santri;

        if (!$santri) {
            return back()
                ->with('error', 'Data santri pemilik QR ini tidak ditemukan!')
                ->withInput();
        }

        // Cari catering berdasarkan tanggal dan sesi.
        $catering = Catering::whereDate('tanggal', Carbon::today())
            ->where('waktu', $validated['waktu'])
            ->first();

        if (!$catering) {
            return back()
                ->with(
                    'error',
                    'Data catering untuk sesi ' .
                    $validated['waktu'] .
                    ' hari ini belum tersedia!'
                )
                ->withInput();
        }

        // Periksa pengambilan sebelumnya pada sesi yang sama.
        $sudahAmbil = PengambilanLauk::whereHas(
            'presensi',
            function ($query) use ($santri, $validated) {
                $query->where('id_santri', $santri->id_santri)
                    ->whereDate('waktu_scan', Carbon::today())
                    ->whereHas(
                        'catering',
                        function ($cateringQuery) use ($validated) {
                            $cateringQuery
                                ->whereDate('tanggal', Carbon::today())
                                ->where('waktu', $validated['waktu']);
                        }
                    );
            }
        )->exists();

        if ($sudahAmbil) {
            return back()
                ->with(
                    'error',
                    "Santri {$santri->nama} (Asrama: {$santri->asrama}) " .
                    "sudah mengambil lauk pada sesi " .
                    "{$validated['waktu']} hari ini!"
                )
                ->withInput();
        }

        // Simpan presensi dan pengambilan dalam satu transaksi.
        DB::transaction(function () use ($santri, $catering) {
            $presensi = Presensi::create([
                'id_santri' => $santri->id_santri,
                'id_catering' => $catering->id_catering,
                'status' => 'Hadir',
                'waktu_scan' => Carbon::now(),
            ]);

            PengambilanLauk::create([
                'id_presensi' => $presensi->id_presensi,
                'jumlah_lauk' => 1,
            ]);
        });

        return back()->with(
            'success',
            "Berhasil! Jatah lauk untuk {$santri->nama} " .
            "(Asrama: {$santri->asrama}) pada sesi " .
            "{$validated['waktu']} telah dicatat."
        );
    }
}

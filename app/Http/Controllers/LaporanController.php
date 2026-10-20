<?php

namespace App\Http\Controllers;

use App\Models\PengambilanLauk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->orderByDesc('waktu_ambil')
            ->get();

        return view('laporan.index', compact('laporan'));
    }

    public function pdf()
    {
        $laporan = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->orderByDesc('waktu_ambil')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('laporan'));

        return $pdf->download('rekap-laporan-lauk.pdf');
    }

    public function bulanan(Request $request)
    {
        $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)
            ->startOfMonth();

        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $laporan = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->whereBetween('waktu_ambil', [
                $awalBulan,
                $akhirBulan,
            ])
            ->orderBy('waktu_ambil')
            ->get();

        $jumlahTransaksi = $laporan->count();

        return view('laporan.bulanan', compact(
            'laporan',
            'bulan',
            'tahun',
            'awalBulan',
            'akhirBulan',
            'jumlahTransaksi'
        ));
    }

    public function pdfBulanan(Request $request)
    {
        $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)
            ->startOfMonth();

        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $laporan = PengambilanLauk::with([
            'santri',
            'catering',
            'user',
        ])
            ->whereBetween('waktu_ambil', [
                $awalBulan,
                $akhirBulan,
            ])
            ->orderBy('waktu_ambil')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf-bulanan', compact(
            'laporan',
            'bulan',
            'tahun',
            'awalBulan',
            'akhirBulan'
        ));

        return $pdf->download(
            'rekap-laporan-lauk-' . $tahun . '-' .
            str_pad((string) $bulan, 2, '0', STR_PAD_LEFT) . '.pdf'
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Presensi::with([
            'santri',
            'catering',
            'pengambilanLauk',
        ])
            ->orderByDesc('waktu_scan')
            ->get();

        return view('laporan.index', compact('laporan'));
    }

    public function pdf()
    {
        $laporan = Presensi::with([
            'santri',
            'catering',
            'pengambilanLauk',
        ])
            ->orderByDesc('waktu_scan')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('laporan'));

        return $pdf->download('rekap-laporan-lauk.pdf');
    }
}

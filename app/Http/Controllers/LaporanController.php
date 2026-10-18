<?php

namespace App\Http\Controllers;

use App\Models\PengambilanLauk;
use Barryvdh\DomPDF\Facade\Pdf;

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
}

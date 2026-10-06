<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengambilanLauk;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan namespace ini ditambahkan

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = PengambilanLauk::with('santri')->latest('waktu_ambil')->get();
        return view('laporan.index', compact('laporan'));
    }

    public function pdf()
    {
        $laporan = PengambilanLauk::with('santri')->latest('waktu_ambil')->get();

        // Load view khusus PDF
        $pdf = Pdf::loadView('laporan.pdf', compact('laporan'));

        // Langsung unduh file PDF dengan nama 'rekap-laporan-lauk.pdf'
        return $pdf->download('rekap-laporan-lauk.pdf');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\QrCode;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        $santris = Santri::with('qrCode')->latest('idSantri')->get();
        return view('santri.index', compact('santris'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:santris,nis',
            'nama_santri' => 'required|string|max:255',
            'kamar' => 'required|string|max:100',
        ]);

        $santri = Santri::create([
            'nis' => $request->nis,
            'nama_santri' => $request->nama_santri,
            'kamar' => $request->kamar,
        ]);

        // Otomatis buatkan QR Code unik untuk santri baru
        QrCode::create([
            'santri_id' => $santri->idSantri,
            'kode_qr' => 'QR-SANTRI-' . $santri->nis,
            'status' => true,
        ]);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $request->validate([
            'nis' => 'required|string|unique:santris,nis,' . $id . ',idSantri',
            'nama_santri' => 'required|string|max:255',
            'kamar' => 'required|string|max:100',
        ]);

        $santri->update([
            'nis' => $request->nis,
            'nama_santri' => $request->nama_santri,
            'kamar' => $request->kamar,
        ]);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }
}
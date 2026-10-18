<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SantriController extends Controller
{
    public function index()
    {
        $santris = Santri::with('qrCode')
            ->orderByDesc('idSantri')
            ->get();

        return view('santri.index', compact('santris'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'    => 'nullable|string|max:50',
            'nama'   => 'required|string|max:255',
            'asrama' => 'nullable|string|max:100',
            'kelas'  => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $nis = !empty($validated['nis']) ? $validated['nis'] : 'NIS-' . time();

            $santri = Santri::create([
                'nis'         => $nis,
                'nama_santri' => $validated['nama'],
                'kamar'       => $validated['asrama'] ?? $request->input('kamar', 'Kamar Utama'),
            ]);

            // DI SINI PERUBAHAN UTAMANYA: Gunakan 'santri_id'
            QrCode::create([
                'santri_id' => $santri->idSantri,
                'kode_qr'   => 'QR-' . Str::uuid(),
            ]);
        });

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri dan QR Code berhasil ditambahkan.');
    }

    public function show($id)
    {
        $santri = Santri::with('qrCode')->findOrFail($id);

        return response()->json($santri);
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);

        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $validated = $request->validate([
            'nis'    => 'nullable|string|max:50',
            'nama'   => 'required|string|max:255',
            'asrama' => 'nullable|string|max:100',
            'kelas'  => 'nullable|string|max:100',
        ]);

        $santri->update([
            'nis'         => !empty($validated['nis']) ? $validated['nis'] : $santri->nis,
            'nama_santri' => $validated['nama'],
            'kamar'       => $validated['asrama'] ?? $request->input('kamar', $santri->kamar),
        ]);

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);

        if ($santri->presensis()->exists()) {
            return redirect()
                ->route('santri.index')
                ->with('error', 'Santri tidak dapat dihapus karena memiliki riwayat presensi.');
        }

        DB::transaction(function () use ($santri) {
            $santri->qrCode()->delete();
            $santri->delete();
        });

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
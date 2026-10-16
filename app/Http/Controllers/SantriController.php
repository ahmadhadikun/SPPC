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
            ->orderByDesc('id_santri')
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
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'asrama' => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($validated) {
            $santri = Santri::create($validated);

            QrCode::create([
                'id_santri' => $santri->id_santri,
                'kode_qr' => 'QR-' . Str::uuid(),
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
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'asrama' => 'required|string|max:100',
        ]);

        $santri->update($validated);

        return redirect()
            ->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);

        // Jangan hapus santri jika sudah memiliki riwayat presensi.
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

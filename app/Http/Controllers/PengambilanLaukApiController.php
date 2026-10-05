<?php

namespace App\Http\Controllers;

use App\Models\PengambilanLauk;
use Illuminate\Http\Request;

class PengambilanLaukApiController extends Controller
{
    public function index()
    {
        return response()->json(
            PengambilanLauk::with([
                'santri',
                'catering',
                'user',
            ])->latest('waktu_ambil')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,idSantri',
            'catering_id' => 'nullable|exists:caterings,idCatering',
            'user_id' => 'nullable|exists:users,id',
            'waktu_ambil' => 'nullable|date',
            'status_ambil' => 'nullable|string',
        ]);

        $pengambilan = PengambilanLauk::create($validated);

        return response()->json([
            'message' => 'Pengambilan lauk berhasil ditambahkan',
            'data' => $pengambilan->load([
                'santri',
                'catering',
                'user',
            ]),
        ], 201);
    }

    public function show(PengambilanLauk $pengambilanLauk)
    {
        return response()->json(
            $pengambilanLauk->load([
                'santri',
                'catering',
                'user',
            ])
        );
    }

    public function update(
        Request $request,
        PengambilanLauk $pengambilanLauk
    ) {
        $validated = $request->validate([
            'santri_id' => 'sometimes|required|exists:santris,idSantri',
            'catering_id' => 'nullable|exists:caterings,idCatering',
            'user_id' => 'nullable|exists:users,id',
            'waktu_ambil' => 'nullable|date',
            'status_ambil' => 'nullable|string',
        ]);

        $pengambilanLauk->update($validated);

        return response()->json([
            'message' => 'Pengambilan lauk berhasil diperbarui',
            'data' => $pengambilanLauk->fresh()->load([
                'santri',
                'catering',
                'user',
            ]),
        ]);
    }

    public function destroy(PengambilanLauk $pengambilanLauk)
    {
        $pengambilanLauk->delete();

        return response()->json([
            'message' => 'Pengambilan lauk berhasil dihapus',
        ]);
    }
}
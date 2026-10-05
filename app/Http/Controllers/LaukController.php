<?php

namespace App\Http\Controllers;

use App\Models\Lauk;
use Illuminate\Http\Request;

class LaukController extends Controller
{
    public function index()
    {
        return response()->json(
            Lauk::all()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $lauk = Lauk::create($validated);

        return response()->json([
            'message' => 'Lauk berhasil ditambahkan',
            'data' => $lauk
        ], 201);
    }

    public function show(Lauk $lauk)
    {
        return response()->json(
            $lauk
        );
    }

    public function update(Request $request, Lauk $lauk)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|required|string',
            'stok' => 'sometimes|required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $lauk->update($validated);

        return response()->json([
            'message' => 'Lauk berhasil diperbarui',
            'data' => $lauk->fresh()
        ]);
    }

    public function destroy(Lauk $lauk)
    {
        $lauk->delete();

        return response()->json([
            'message' => 'Lauk berhasil dihapus'
        ]);
    }
}
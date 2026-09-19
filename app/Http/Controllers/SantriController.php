<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index()
    {
        return response()->json(
            Santri::all()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:santris,nis',
            'nama' => 'required',
            'kamar' => 'nullable',
            'kelas' => 'nullable',
        ]);

        $santri = Santri::create($request->all());

        return response()->json([
            'message' => 'Data santri berhasil ditambahkan',
            'data' => $santri
        ], 201);
    }
}
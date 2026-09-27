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
        $request->validate([
            'nama' => 'required',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
        ]);

        $lauk = Lauk::create($request->all());

        return response()->json([
            'message' => 'Lauk berhasil ditambahkan',
            'data' => $lauk
        ], 201);
    }
}
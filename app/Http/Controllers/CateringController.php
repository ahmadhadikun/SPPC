<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CateringController extends Controller
{
    public function index()
    {
        $caterings = Catering::orderByDesc('tanggal')
            ->orderBy('waktu')
            ->get();

        return view('catering.index', compact('caterings'));
    }

    public function create()
    {
        return view('catering.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'waktu' => [
                'required',
                Rule::in(['Pagi', 'Siang', 'Malam']),
            ],
            'menu' => ['required', 'string', 'max:255'],
        ]);

        Catering::create($validated);

        return redirect()
            ->route('catering.index')
            ->with('success', 'Jadwal catering berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $catering = Catering::findOrFail($id);

        return view('catering.edit', compact('catering'));
    }

    public function update(Request $request, $id)
    {
        $catering = Catering::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'waktu' => [
                'required',
                Rule::in(['Pagi', 'Siang', 'Malam']),
            ],
            'menu' => ['required', 'string', 'max:255'],
        ]);

        $catering->update($validated);

        return redirect()
            ->route('catering.index')
            ->with('success', 'Jadwal catering berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $catering = Catering::findOrFail($id);

        // Cegah penghapusan jadwal yang sudah memiliki riwayat presensi.
        if ($catering->presensis()->exists()) {
            return redirect()
                ->route('catering.index')
                ->with(
                    'error',
                    'Jadwal tidak dapat dihapus karena sudah memiliki riwayat presensi.'
                );
        }

        $catering->delete();

        return redirect()
            ->route('catering.index')
            ->with('success', 'Jadwal catering berhasil dihapus.');
    }
}

@extends('layouts.app')

@section('title', 'Data Santri - SPPC')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Data Santri</h2>
    <a href="{{ route('santri.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Tambah Santri
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Santri</th>
                        <th>Kamar</th>
                        <th>Kode QR</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santris as $index => $santri)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $santri->nis }}</td>
                        <td class="fw-semibold">{{ $santri->nama_santri }}</td>
                        <td>{{ $santri->kamar }}</td>
                        <td>
                            <code>{{ $santri->qrCode->kode_qr ?? '-' }}</code>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('santri.edit', $santri->idSantri) }}" class="btn btn-sm btn-warning text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('santri.destroy', $santri->idSantri) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data santri ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data santri.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
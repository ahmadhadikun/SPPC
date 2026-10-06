@extends('layouts.app')

@section('title', 'Data Laporan - SPPC')

@section('content')
<div class="container-fluid px-2">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-file-lines me-2 text-primary"></i>Rekapitulasi Pengambilan Lauk Santri
            </h3>
            <p class="text-muted text-sm mb-0">Daftar santri yang telah melakukan pengambilan lauk berdasarkan hasil scan katering.</p>
        </div>
        <!-- Ubah tombol cetak menjadi link Unduh PDF -->
        <a href="{{ route('laporan.pdf') }}" target="_blank" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-file-pdf me-2"></i>Unduh PDF
        </a>
    </div>

    <!-- Tabel Data (Seterusnya ke bawah tetap sama) -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark text-uppercase fs-7">
                        <tr>
                            <th class="py-3 px-4 text-center" style="width: 5%;">No</th>
                            <th class="py-3 px-3">NIS</th>
                            <th class="py-3 px-3">Nama Santri</th>
                            <th class="py-3 px-3">Kamar</th>
                            <th class="py-3 px-3">Waktu Ambil</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-3 fw-medium">{{ $item->santri->nis ?? '-' }}</td>
                                <td class="py-3 px-3 fw-bold text-dark">{{ $item->santri->nama_santri ?? 'Santri Tidak Ditemukan' }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                        {{ $item->santri->kamar ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-muted">
                                    <i class="fa-regular fa-clock me-1 text-secondary"></i>
                                    {{ $item->waktu_ambil ? \Carbon\Carbon::parse($item->waktu_ambil)->format('d M Y, H:i') : ($item->created_at ? $item->created_at->format('d M Y, H:i') : '-') }}
                                </td>
                                <td class="text-center py-3 px-4">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">
                                        <i class="fa-solid fa-check-circle me-1"></i> Berhasil
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="my-3">
                                        <i class="fa-solid fa-folder-open fa-3x text-secondary opacity-50 mb-3"></i>
                                        <p class="mb-0 fw-medium">Belum ada data pengambilan lauk yang tercatat.</p>
                                        <small class="text-muted">Data akan otomatis muncul di sini ketika katering berhasil melakukan *scan* QR santri.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
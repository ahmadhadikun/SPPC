@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard Pengasuh</h2>
            <p class="text-muted">Monitoring kehadiran dan rekapitulasi pengambilan lauk santri.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Santri Ambil Lauk Hari Ini</h5>
                    <h3>{{ $presensiHariIni ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Cetak Rekap Laporan</h5>
                    <p class="card-text small">Unduh laporan lengkap dalam format PDF.</p>
                    <a href="{{ route('laporan.index') }}" class="btn btn-light btn-sm text-primary fw-bold">Buka Menu Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
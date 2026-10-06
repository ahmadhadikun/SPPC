@extends('layouts.app') {{-- Sesuaikan dengan layout utama aplikasi Anda --}}

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard Admin</h2>
            <p class="text-muted">Selamat datang, Administrator. Berikut adalah ringkasan sistem.</p>
        </div>
    </div>

    {{-- Kotak Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Santri</h5>
                    <h3>{{ $totalSantri }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Presensi Hari Ini</h5>
                    <h3>{{ $presensiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">QR Code Terdaftar</h5>
                    <h3>{{ $qrTerdaftar }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Pintasan Admin --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Menu Cepat</h5>
                    <a href="{{ route('santri.index') }}" class="btn btn-outline-primary me-2">Kelola Santri</a>
                    <a href="{{ route('scan.index') }}" class="btn btn-outline-success me-2">Buka Pemindai QR</a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
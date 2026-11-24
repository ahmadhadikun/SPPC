@extends('layouts.app')

@section('title', 'Dashboard - SPPC')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Dashboard Sistem</h2>
    <span class="badge bg-primary px-3 py-2">Halo, {{ Auth::user()->name ?? 'Pengguna' }}</span>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 bg-primary text-white">
            <h5>Total Santri</h5>
            <h2 class="fw-bold m-0">{{ \App\Models\Santri::count() }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 bg-success text-white">
            <h5>Presensi Hari Ini</h5>
            <h2 class="fw-bold m-0">{{ \App\Models\PengambilanLauk::whereDate('waktu_ambil', now()->toDateString())->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 bg-warning text-dark">
            <h5>Kode QR Terdaftar</h5>
            <h2 class="fw-bold m-0">{{ \App\Models\QrCode::where('status', true)->count() }}</h2>
        </div>
    </div>
</div>
@endsection
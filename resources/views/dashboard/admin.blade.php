
@extends('layouts.app')

@section('content')
<style>
    .admin-dashboard {
        --admin-green: #245b48;
        --admin-green-dark: #194535;
        --admin-sage: #e9f1eb;
        --admin-muted: #7b8981;
        color: #26372f;
        padding: 12px 0 28px;
    }

    .admin-dashboard .welcome-panel {
        background: linear-gradient(120deg, #245b48, #39765d);
        color: #fff;
        border-radius: 22px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }

    .admin-dashboard .welcome-panel::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        border: 32px solid rgba(255,255,255,.08);
        border-radius: 50%;
        right: -40px;
        top: -70px;
    }

    .admin-dashboard .eyebrow {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.8px;
        font-weight: 700;
        opacity: .8;
    }

    .admin-dashboard .welcome-title {
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 750;
        margin: 10px 0;
    }

    .admin-dashboard .welcome-description {
        margin: 0;
        color: rgba(255,255,255,.82);
        font-size: 14px;
    }

    .admin-dashboard .section-heading {
        font-size: 19px;
        font-weight: 750;
        margin-bottom: 5px;
        color: #26372f;
    }

    .admin-dashboard .section-description {
        color: var(--admin-muted);
        font-size: 13px;
        margin-bottom: 0;
    }

    .admin-dashboard .stat-card {
        height: 100%;
        padding: 22px;
        background: #fff;
        border: 1px solid #edf0ed;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(31, 64, 46, .045);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .admin-dashboard .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 26px rgba(31, 64, 46, .09);
    }

    .admin-dashboard .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 22px;
    }

    .admin-dashboard .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 15px;
        font-size: 23px;
    }

    .admin-dashboard .icon-green {
        background: #e5f2e9;
        color: #28724b;
    }

    .admin-dashboard .icon-blue {
        background: #e8f0fc;
        color: #386bb2;
    }

    .admin-dashboard .icon-amber {
        background: #fff1d9;
        color: #a36b16;
    }

    .admin-dashboard .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #78857d;
        margin-bottom: 8px;
    }

    .admin-dashboard .stat-number {
        font-size: clamp(28px, 3vw, 35px);
        line-height: 1.2;
        font-weight: 800;
        color: #26372f;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .admin-dashboard .stat-note {
        font-size: 12px;
        color: #849087;
        margin-top: 9px;
        margin-bottom: 0;
    }

    .admin-dashboard .quick-panel {
        background: #fff;
        border: 1px solid #edf0ed;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 5px 20px rgba(31, 64, 46, .045);
    }

    .admin-dashboard .quick-link {
        display: flex;
        align-items: center;
        gap: 15px;
        height: 100%;
        padding: 18px;
        text-decoration: none;
        color: inherit;
        background: #fff;
        border: 1px solid #e9eee9;
        border-radius: 16px;
        transition: .2s ease;
    }

    .admin-dashboard .quick-link:hover {
        background: #f7faf7;
        border-color: #b9d3c1;
        transform: translateY(-3px);
        color: var(--admin-green);
    }

    .admin-dashboard .quick-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: var(--admin-sage);
        color: var(--admin-green);
        font-size: 21px;
    }

    .admin-dashboard .quick-title {
        font-size: 14px;
        font-weight: 750;
        margin-bottom: 5px;
    }

    .admin-dashboard .quick-description {
        color: var(--admin-muted);
        font-size: 12px;
        margin: 0;
        line-height: 1.5;
    }

    .admin-dashboard .quick-arrow {
        margin-left: auto;
        color: #98a49c;
        font-size: 20px;
    }

    .admin-dashboard .bottom-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 18px;
        margin-top: 22px;
        border-radius: 14px;
        background: #edf5ef;
        color: #456451;
        font-size: 13px;
    }

    @media (max-width: 576px) {
        .admin-dashboard .welcome-panel {
            padding: 24px 20px;
        }

        .admin-dashboard .quick-panel {
            padding: 18px;
        }

        .admin-dashboard .stat-card {
            padding: 18px;
        }
    }
</style>

<div class="container-fluid admin-dashboard">

    {{-- Header --}}
    <div class="welcome-panel">
        <div class="eyebrow">SPPC · Sistem Pengambilan Lauk</div>
        <h1 class="welcome-title">Selamat Datang, Admin! 👋</h1>
        <p class="welcome-description">
            Kelola data dan pantau aktivitas sistem pengambilan lauk
            santri dengan lebih mudah melalui dashboard ini.
        </p>
    </div>

    {{-- Judul statistik --}}
    <div class="mb-3">
        <h2 class="section-heading">Ringkasan Sistem</h2>
        <p class="section-description">
            Informasi singkat mengenai aktivitas sistem.
        </p>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-green">♟</div>
                    <span class="badge rounded-pill text-bg-light">Santri</span>
                </div>
                <div class="stat-label">Total Santri</div>
                <h3 class="stat-number">{{ $totalSantri }}</h3>
                <p class="stat-note">Jumlah santri yang tercatat dalam sistem</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-blue">✓</div>
                    <span class="badge rounded-pill text-bg-light">Hari ini</span>
                </div>
                <div class="stat-label">Presensi Hari Ini</div>
                <h3 class="stat-number">{{ $presensiHariIni }}</h3>
                <p class="stat-note">Jumlah presensi yang tercatat hari ini</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-icon icon-amber">▦</div>
                    <span class="badge rounded-pill text-bg-light">QR Code</span>
                </div>
                <div class="stat-label">QR Code Terdaftar</div>
                <h3 class="stat-number">{{ $qrTerdaftar }}</h3>
                <p class="stat-note">Jumlah QR Code yang tercatat dalam sistem</p>
            </div>
        </div>

    </div>

    {{-- Menu pintasan --}}
    <div class="quick-panel">
        <div class="mb-4">
            <h2 class="section-heading">Akses Cepat</h2>
            <p class="section-description">
                Pilih menu untuk mengakses fitur administrasi.
            </p>
        </div>

        <div class="row g-3">

            {{-- Kelola Data Santri --}}
            <div class="col-12 col-lg-4">
                <a href="{{ route('santri.index') }}" class="quick-link">
                    <div class="quick-icon">♟</div>
                    <div>
                        <div class="quick-title">Kelola Data Santri</div>
                        <p class="quick-description">
                            Lihat dan kelola informasi santri.
                        </p>
                    </div>
                    <span class="quick-arrow">›</span>
                </a>
            </div>

            {{-- Pemindai QR --}}
            <div class="col-12 col-lg-4">
                <a href="{{ route('scan.index') }}" class="quick-link">
                    <div class="quick-icon">▦</div>
                    <div>
                        <div class="quick-title">Pemindai QR</div>
                        <p class="quick-description">
                            Buka fitur pemindaian QR Code.
                        </p>
                    </div>
                    <span class="quick-arrow">›</span>
                </a>
            </div>

            {{-- Laporan Sistem --}}
            <div class="col-12 col-lg-4">
                <a href="{{ route('laporan.index') }}" class="quick-link">
                    <div class="quick-icon">▤</div>
                    <div>
                        <div class="quick-title">Laporan Sistem</div>
                        <p class="quick-description">
                            Lihat laporan aktivitas sistem.
                        </p>
                    </div>
                    <span class="quick-arrow">›</span>
                </a>
            </div>

            {{-- Jadwal Catering --}}
            <div class="col-12 col-lg-4">
                <a href="{{ route('catering.index') }}" class="quick-link">
                    <div class="quick-icon">🍽</div>
                    <div>
                        <div class="quick-title">Jadwal Catering</div>
                        <p class="quick-description">
                            Tambah dan kelola jadwal serta menu catering.
                        </p>
                    </div>
                    <span class="quick-arrow">›</span>
                </a>
            </div>

        </div>
    </div>

    <div class="bottom-note">
        <span>●</span>
        <span>Dashboard Admin SPPC — pusat pemantauan dan pengelolaan sistem.</span>
    </div>

</div>
@endsection

```blade
@extends('layouts.app')

@section('title', 'Scan QR Catering - SPPC')

@section('content')
<div class="container-fluid px-0">

    {{-- Header Halaman & Statistik --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-qrcode text-primary me-2"></i>
                Scan QR Pengambilan Lauk
            </h2>
            <p class="text-muted mb-0">
                Arahkan kamera ke kartu QR santri atau masukkan kode secara manual.
            </p>
        </div>

        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="card border-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <span class="d-block small text-light opacity-75">
                            Total Pengambilan Hari Ini
                        </span>
                        <h4 class="mb-0 fw-bold text-success">
                            {{ count($riwayatHariIni) }} Transaksi
                        </h4>
                    </div>

                    <div class="fs-2 text-success opacity-50">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4"
             role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                <div>
                    <strong>Berhasil!</strong>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Gagal --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4"
             role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-danger"></i>
                <div>
                    <strong>Perhatian!</strong>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validasi Form --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <strong>Data belum valid.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri: Kamera dan Input Manual --}}
        <div class="col-lg-5">

            {{-- Scanner Kamera --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-camera text-primary me-2"></i>
                        Kamera Scanner
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div id="reader" class="rounded-3 overflow-hidden border-0"></div>

                    <small class="text-muted d-block mt-3">
                        Pastikan izin akses kamera sudah diberikan pada browser.
                    </small>
                </div>
            </div>

            {{-- Input Manual --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-keyboard text-secondary me-2"></i>
                        Input Manual
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('scan.store') }}"
                          method="POST"
                          id="scan-form">

                        @csrf

                        {{-- Pilihan Sesi Catering --}}
                        <div class="mb-3">
                            <label for="waktu"
                                   class="form-label text-muted small fw-semibold">
                                SESI PENGAMBILAN LAUK
                            </label>

                            <select class="form-select form-select-lg"
                                    id="waktu"
                                    name="waktu"
                                    required>
                                <option value="">-- Pilih Sesi --</option>
                                <option value="Pagi"
                                    {{ old('waktu') === 'Pagi' ? 'selected' : '' }}>
                                    Pagi
                                </option>
                                <option value="Siang"
                                    {{ old('waktu', 'Siang') === 'Siang' ? 'selected' : '' }}>
                                    Siang
                                </option>
                                <option value="Malam"
                                    {{ old('waktu') === 'Malam' ? 'selected' : '' }}>
                                    Malam
                                </option>
                            </select>

                            <small class="text-muted">
                                Setiap santri dapat mengambil lauk satu kali per sesi.
                            </small>
                        </div>

                        {{-- Input Kode QR --}}
                        <div class="mb-3">
                            <label for="kode_qr"
                                   class="form-label text-muted small fw-semibold">
                                KODE QR SANTRI
                            </label>

                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="fa-solid fa-id-card"></i>
                                </span>

                                <input type="text"
                                       class="form-control border-start-0 ps-0"
                                       id="kode_qr"
                                       name="kode_qr"
                                       value="{{ old('kode_qr') }}"
                                       placeholder="Contoh: QR-SANTRI-..."
                                       required
                                       autocomplete="off">
                            </div>
                        </div>

                        <button type="submit"
                                id="submit-button"
                                class="btn btn-primary w-100 py-3 fw-semibold rounded-3 shadow-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            Proses Pengambilan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Riwayat --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 pt-4 px-4 pb-0
                            d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-clock-rotate-left text-success me-2"></i>
                        Riwayat Pengambilan Hari Ini
                    </h5>

                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                        <i class="fa-solid fa-calendar-day me-1 text-muted"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive"
                         style="max-height: 520px; overflow-y: auto;">

                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase text-muted">
                                <tr>
                                    <th class="py-3 ps-3">Waktu</th>
                                    <th class="py-3">Nama Santri</th>
                                    <th class="py-3">Asrama</th>
                                    <th class="py-3">Sesi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($riwayatHariIni as $riwayat)
                                    <tr>
                                        {{-- Waktu Scan --}}
                                        <td class="ps-3">
                                            <span class="badge bg-light text-secondary border px-2 py-1 fw-normal">
                                                <i class="fa-regular fa-clock me-1"></i>
                                                {{ $riwayat->presensi?->waktu_scan?->format('H:i:s') ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Nama Santri --}}
                                        <td class="fw-semibold text-dark">
                                            {{ $riwayat->presensi?->santri?->nama ?? '-' }}
                                        </td>

                                        {{-- Asrama --}}
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                {{ $riwayat->presensi?->santri?->asrama ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Sesi Catering --}}
                                        <td>
                                            @php
                                                $sesi = $riwayat->presensi?->catering?->waktu;
                                                $warnaSesi = match ($sesi) {
                                                    'Pagi' => 'warning',
                                                    'Siang' => 'primary',
                                                    'Malam' => 'dark',
                                                    default => 'secondary',
                                                };
                                            @endphp

                                            <span class="badge bg-{{ $warnaSesi }}">
                                                {{ $sesi ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="text-center py-5 text-muted">
                                            <div class="py-4">
                                                <i class="fa-solid fa-folder-open fs-1 text-black-50 mb-3 d-block"></i>
                                                <p class="mb-0 fw-semibold">
                                                    Belum ada santri yang mengambil lauk hari ini.
                                                </p>
                                                <small class="text-muted">
                                                    Data akan muncul otomatis setelah QR berhasil diproses.
                                                </small>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    #reader {
        border: 2px dashed #cbd5e1 !important;
        border-radius: 12px;
        background-color: #f8f9fa;
        padding: 10px;
    }

    #reader video {
        border-radius: 8px !important;
    }

    #reader img {
        display: inline-block;
    }

    #reader button {
        margin: 5px;
    }

    #reader select {
        max-width: 100%;
        padding: 6px;
        border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    let scanSedangDiproses = false;

    function onScanSuccess(decodedText, decodedResult) {
        // Cegah form terkirim berulang kali saat QR terbaca beberapa kali.
        if (scanSedangDiproses) {
            return;
        }

        const inputQr = document.getElementById('kode_qr');
        const form = document.getElementById('scan-form');
        const tombolSubmit = document.getElementById('submit-button');
        const pilihanSesi = document.getElementById('waktu');

        // Masukkan hasil scan ke input.
        inputQr.value = decodedText;

        // Jika sesi belum dipilih, hentikan proses agar tidak salah sesi.
        if (!pilihanSesi.value) {
            alert('Silakan pilih sesi pengambilan lauk terlebih dahulu.');
            pilihanSesi.focus();
            return;
        }

        scanSedangDiproses = true;
        tombolSubmit.disabled = true;
        tombolSubmit.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        form.submit();
    }

    function onScanFailure(error) {
        // Kegagalan membaca QR per frame tidak perlu ditampilkan.
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Html5QrcodeScanner === 'undefined') {
            document.getElementById('reader').innerHTML =
                '<div class="alert alert-warning mb-0">' +
                'Library scanner tidak berhasil dimuat. Silakan periksa koneksi internet atau gunakan input manual.' +
                '</div>';
            return;
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            'reader',
            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                rememberLastUsedCamera: true
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endpush
```
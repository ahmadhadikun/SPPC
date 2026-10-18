@extends('layouts.app')

@section('title', 'Scan QR Catering - SPPC')

@section('content')
<div class="container-fluid px-0">

    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-qrcode text-primary me-2"></i>
                Scan QR Pengambilan Lauk
            </h2>
            <p class="text-muted mb-0">
                Pilih sesi, lalu arahkan kamera ke QR Code pada kartu KTS santri.
            </p>
        </div>

        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="card border-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #0f172a, #1e293b);">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <span class="d-block small text-light opacity-75">
                            Total Pengambilan Hari Ini
                        </span>
                        <h4 class="mb-0 fw-bold text-success">
                            {{ count($riwayatHariIni) }} Transaksi
                        </h4>
                    </div>
                    <i class="fa-solid fa-circle-check fs-2 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fa-solid fa-circle-check me-2"></i>Berhasil!</strong>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Perhatian!</strong>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Data belum valid:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-5">

            {{-- Scanner Kamera --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fa-solid fa-camera text-primary me-2"></i>
                        Kamera Scanner
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="waktu"
                               class="form-label small fw-semibold text-muted">
                            PILIH SESI PENGAMBILAN
                        </label>

                        <select class="form-select form-select-lg" id="waktu" required>
                            <option value="">-- Pilih Sesi --</option>
                            <option value="Pagi"
                                {{ old('waktu') === 'Pagi' ? 'selected' : '' }}>
                                Pagi
                            </option>
                            <option value="Siang"
                                {{ old('waktu', 'Siang') === 'Siang' ? 'selected' : '' }}>
                                Siang
                            </option>
                            <option value="Sore"
                                {{ old('waktu') === 'Sore' ? 'selected' : '' }}>
                                Sore
                            </option>
                        </select>
                    </div>

                    <div id="reader" class="rounded-3 overflow-hidden"></div>

                    <div id="scanner-status" class="small text-muted mt-3" aria-live="polite">
                        Pilih sesi terlebih dahulu, kemudian izinkan akses kamera.
                    </div>

                    <div class="alert alert-info small mt-3 mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        QR dibaca otomatis. Pastikan kode QR berasal dari KTS santri yang terdaftar.
                    </div>
                </div>
            </div>

            {{-- Input Manual --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fa-solid fa-keyboard text-secondary me-2"></i>
                        Input Manual
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('scan.store') }}"
                          method="POST"
                          id="scan-form">
                        @csrf

                        <input type="hidden" name="waktu" id="waktu-form"
                               value="{{ old('waktu') }}">

                        <div class="mb-3">
                            <label for="kode_qr" class="form-label small fw-semibold text-muted">
                                KODE QR SANTRI
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   id="kode_qr"
                                   name="kode_qr"
                                   value="{{ old('kode_qr') }}"
                                   placeholder="Masukkan kode dari QR"
                                   autocomplete="off"
                                   required>
                        </div>

                        <button type="submit"
                                id="submit-button"
                                class="btn btn-primary w-100 py-3 fw-semibold rounded-3">
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            Proses Pengambilan
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Riwayat Pengambilan --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-clock-rotate-left text-success me-2"></i>
                            Riwayat Pengambilan Hari Ini
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                            {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Nama Santri</th>
                                    <th>Kamar</th>
                                    <th>Sesi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($riwayatHariIni as $riwayat)
                                    @php
                                        $sesi = $riwayat->catering?->sesi;
                                        $warnaSesi = match ($sesi) {
                                            'Pagi' => 'warning',
                                            'Siang' => 'primary',
                                            'Sore' => 'success',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-secondary border">
                                                {{ $riwayat->waktu_ambil
                                                    ? \Carbon\Carbon::parse($riwayat->waktu_ambil)->format('H:i:s')
                                                    : '-' }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold">
                                            {{ $riwayat->santri?->nama_santri ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $riwayat->santri?->kamar ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $warnaSesi }}">
                                                {{ $sesi ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-folder-open fs-1 mb-3 d-block"></i>
                                            <p class="fw-semibold mb-1">
                                                Belum ada pengambilan hari ini.
                                            </p>
                                            <small>Riwayat muncul setelah QR berhasil diproses.</small>
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
        width: 100%;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8f9fa;
        padding: 10px;
    }

    #reader video {
        border-radius: 8px;
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
document.addEventListener('DOMContentLoaded', function () {
    const readerElement = document.getElementById('reader');
    const sesiSelect = document.getElementById('waktu');
    const sesiForm = document.getElementById('waktu-form');
    const kodeQr = document.getElementById('kode_qr');
    const form = document.getElementById('scan-form');
    const tombol = document.getElementById('submit-button');
    const status = document.getElementById('scanner-status');

    let sedangMemproses = false;
    let scanner = null;

    function sinkronkanSesi() {
        sesiForm.value = sesiSelect.value;
    }

    sesiSelect.addEventListener('change', sinkronkanSesi);
    sinkronkanSesi();

    form.addEventListener('submit', function (event) {
        sinkronkanSesi();

        if (!sesiForm.value) {
            event.preventDefault();
            alert('Pilih sesi Pagi, Siang, atau Sore terlebih dahulu.');
            sesiSelect.focus();
            return;
        }

        if (!kodeQr.value.trim()) {
            event.preventDefault();
            alert('Kode QR santri belum diisi.');
            kodeQr.focus();
            return;
        }

        if (sedangMemproses) {
            event.preventDefault();
        } else {
            sedangMemproses = true;
            tombol.disabled = true;
            tombol.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        }
    });

    if (typeof Html5QrcodeScanner === 'undefined') {
        readerElement.innerHTML =
            '<div class="alert alert-warning mb-0">' +
            'Library scanner gagal dimuat. Periksa koneksi internet atau gunakan input manual.' +
            '</div>';
        status.textContent = 'Scanner belum tersedia.';
        return;
    }

    function onScanSuccess(decodedText) {
        if (sedangMemproses) return;

        if (!sesiSelect.value) {
            status.textContent = 'Pilih sesi terlebih dahulu sebelum memindai QR.';
            sesiSelect.focus();
            return;
        }

        sedangMemproses = true;
        kodeQr.value = decodedText.trim();
        sesiForm.value = sesiSelect.value;

        tombol.disabled = true;
        tombol.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Memproses hasil scan...';
        status.textContent = 'QR terbaca. Sedang memproses pengambilan...';

        form.submit();
    }

    function onScanFailure() {
        // Kesalahan pembacaan pada setiap frame tidak perlu ditampilkan.
    }

    scanner = new Html5QrcodeScanner(
        'reader',
        {
            fps: 10,
            qrbox: { width: 230, height: 230 },
            rememberLastUsedCamera: true
        },
        false
    );

    scanner.render(onScanSuccess, onScanFailure);
});
</script>
@endpush

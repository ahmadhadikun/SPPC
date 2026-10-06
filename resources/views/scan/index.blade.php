@extends('layouts.app')

@section('title', 'Scan QR Catering - SPPC')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Halaman & Statistik Cepat -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-qrcode text-primary me-2"></i> Scan QR Pengambilan Lauk
            </h2>
            <p class="text-muted mb-0">Arahkan kamera ke kartu QR santri atau masukkan kode secara manual pada form di bawah.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="card border-0 shadow-sm bg-gradient text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <span class="d-block small text-light opacity-75">Total Pengambilan Hari Ini</span>
                        <h4 class="mb-0 fw-bold text-success">{{ count($riwayatHariIni) }} Santri</h4>
                    </div>
                    <div class="fs-2 text-success opacity-50">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses / Gagal -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3 text-success"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-danger"></i>
                <div>
                    <strong>Perhatian!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Scanner Kamera & Input Manual -->
        <div class="col-lg-5">
            <!-- Kotak Kamera Scanner -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-camera text-primary me-2"></i>Kamera Scanner
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div id="reader" class="rounded-3 overflow-hidden border-0"></div>
                </div>
            </div>

            <!-- Kotak Input Manual -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-keyboard text-secondary me-2"></i>Input Manual
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('scan.store') }}" method="POST" id="scan-form">
                        @csrf
                        <div class="mb-3">
                            <label for="kode_qr" class="form-label text-muted small fw-semibold">KODE QR / NIS SANTRI</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="kode_qr" name="kode_qr" placeholder="Contoh: QR-SANTRI-..." required autocomplete="off">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold rounded-3 shadow-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i> Proses Pengambilan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Pengambilan Hari Ini -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-clock-rotate-left text-success me-2"></i>Riwayat Pengambilan Hari Ini
                    </h5>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                        <i class="fa-solid fa-calendar-day me-1 text-muted"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th class="py-3 ps-3 rounded-start">Waktu</th>
                                    <th class="py-3">Nama Santri</th>
                                    <th class="py-3 rounded-end">Kamar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatHariIni as $riwayat)
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-light text-secondary border px-2 py-1 fw-normal">
                                            <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($riwayat->waktu_ambil)->format('H:i:s') }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $riwayat->santri->nama_santri ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                            {{ $riwayat->santri->kamar ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i class="fa-solid fa-folder-open fs-1 text-black-50 mb-3 d-block"></i>
                                            <p class="mb-0 fw-semibold">Belum ada santri yang mengambil lauk hari ini.</p>
                                            <small class="text-muted">Data akan muncul otomatis setelah QR discan.</small>
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
    /* Kustomisasi kecil agar kotak scanner html5-qrcode lebih rapi */
    #reader {
        border: 2px dashed #cbd5e1 !important;
        border-radius: 12px;
        background-color: #f8f9fa;
        padding: 10px;
    }
    #reader video {
        border-radius: 8px !important;
    }
</style>
@endpush

@push('scripts')
<!-- Masukkan Library html5-qrcode dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Masukkan hasil scan teks QR ke input form
        document.getElementById('kode_qr').value = decodedText;
        
        // Kirim (submit) form secara otomatis begitu QR terbaca
        document.getElementById('scan-form').submit();
    }

    function onScanFailure(error) {
        // Handle kegagalan scan per frame (diabaikan agar console bersih)
    }

    // Inisialisasi Scanner Kamera
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: { width: 250, height: 250 } },
        /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush
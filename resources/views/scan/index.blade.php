@extends('layouts.app')

@section('title', 'Scan QR Catering - SPPC')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Scan QR Pengambilan Lauk</h2>
        <p class="text-muted">Arahkan kamera ke kartu QR santri atau ketik kode QR secara manual.</p>
    </div>
</div>

<!-- Alert Notifikasi Sukses / Gagal -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Scanner Kamera & Input Manual -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-camera me-2 text-primary"></i>Kamera Scanner</h4>
            <!-- Kotak Tampilan Kamera -->
            <div id="reader" style="width: 100%;"></div>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-keyboard me-2 text-secondary"></i>Input Manual</h4>
            <form action="{{ route('scan.store') }}" method="POST" id="scan-form">
                @csrf
                <div class="mb-3">
                    <label for="kode_qr" class="form-label">Kode QR / NIS Santri</label>
                    <input type="text" class="form-control form-control-lg" id="kode_qr" name="kode_qr" placeholder="Contoh: QR-SANTRI-..." required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-paper-plane me-1"></i> Proses Pengambilan
                </button>
            </form>
        </div>
    </div>

    <!-- Riwayat Pengambilan Hari Ini -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left me-2 text-success"></i>Riwayat Pengambilan Hari Ini</h4>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Nama Santri</th>
                            <th>Kamar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatHariIni as $riwayat)
                        <tr>
                            <td><span class="badge bg-secondary">{{ \Carbon\Carbon::parse($riwayat->waktu_ambil)->format('H:i:s') }}</span></td>
                            <td class="fw-semibold">{{ $riwayat->santri->nama_santri ?? '-' }}</td>
                            <td>{{ $riwayat->santri->kamar ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada santri yang mengambil lauk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

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
        // Handle kegagalan scan per frame (biasanya diabaikan agar console tidak penuh)
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
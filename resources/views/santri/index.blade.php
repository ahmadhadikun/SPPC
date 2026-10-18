@extends('layouts.app')

@section('title', 'Data Santri - SPPC')

@push('styles')
<style>
    .santri-page { color: #26372f; }

    .santri-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
    }

    .santri-title {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
        color: #194535;
    }

    .santri-subtitle {
        margin: 6px 0 0;
        color: #7b8981;
        font-size: 13px;
    }

    .santri-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        border-radius: 10px;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .btn-add-santri {
        background: #245b48;
        color: #fff;
    }

    .btn-add-santri:hover {
        background: #194535;
        color: #fff;
        transform: translateY(-1px);
    }

    .santri-summary {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        margin-bottom: 22px;
        background: #fff;
        border: 1px solid #e7ece8;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(30, 70, 50, .04);
    }

    .summary-icon {
        display: grid;
        place-items: center;
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        background: #e9f1eb;
        color: #245b48;
        border-radius: 14px;
        font-size: 22px;
    }

    .summary-label {
        margin: 0 0 4px;
        color: #7b8981;
        font-size: 12px;
    }

    .summary-value {
        margin: 0;
        color: #194535;
        font-size: 25px;
        font-weight: 800;
    }

    .santri-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e7ece8;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(30, 70, 50, .04);
    }

    .santri-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px;
        border-bottom: 1px solid #edf0ed;
    }

    .santri-card-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: #194535;
    }

    .santri-search {
        width: 280px;
        max-width: 100%;
        border: 1px solid #e1e8e2;
        border-radius: 10px;
        padding: 10px 13px;
        font-size: 12px;
        outline: none;
    }

    .santri-search:focus {
        border-color: #79a58b;
        box-shadow: 0 0 0 3px rgba(36, 91, 72, .08);
    }

    .santri-table {
        margin: 0;
        vertical-align: middle;
    }

    .santri-table thead th {
        padding: 14px 16px;
        background: #f5f8f5;
        color: #68776e;
        border-bottom: 1px solid #e7ece8;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }

    .santri-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f0f3f0;
        font-size: 13px;
    }

    .santri-table tbody tr:last-child td {
        border-bottom: none;
    }

    .santri-table tbody tr:hover {
        background: #fbfdfb;
    }

    .santri-number {
        color: #7b8981;
        font-weight: 700;
    }

    .santri-name {
        color: #26372f;
        font-weight: 700;
        min-width: 150px;
    }

    .santri-id {
        color: #68776e;
        font-family: monospace;
    }

    .room-badge {
        display: inline-block;
        padding: 6px 10px;
        background: #f0f5f1;
        color: #245b48;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .qr-cell {
        min-width: 125px;
    }

    .qr-image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        min-height: 100px;
        margin: 0 auto 8px;
        padding: 8px;
        background: #fff;
        border: 1px solid #e7ece8;
        border-radius: 10px;
    }

    .qr-image canvas,
    .qr-image img {
        display: block;
        max-width: 100%;
        height: auto;
    }

    .qr-code-text {
        display: block;
        color: #68776e;
        font-size: 10px;
        text-align: center;
        overflow-wrap: anywhere;
    }

    .qr-empty {
        color: #b54747;
        font-size: 11px;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-buttons form {
        margin: 0;
    }

    .action-button {
        display: inline-grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 9px;
        text-decoration: none;
        transition: .2s ease;
        cursor: pointer;
    }

    .action-edit {
        background: #fff5df;
        color: #a66a00;
    }

    .action-edit:hover {
        background: #ffe8b5;
        color: #805000;
    }

    .action-delete {
        background: #fff0f0;
        color: #b54747;
    }

    .action-delete:hover {
        background: #ffe0e0;
        color: #8d2929;
    }

    .empty-state {
        padding: 45px 20px !important;
        color: #7b8981;
        text-align: center;
    }

    .empty-state i {
        display: block;
        margin-bottom: 12px;
        color: #b7c8bc;
        font-size: 36px;
    }

    .table-footer {
        padding: 14px 20px;
        background: #fbfdfb;
        border-top: 1px solid #edf0ed;
        color: #7b8981;
        font-size: 11px;
    }

    @media (max-width: 767.98px) {
        .santri-title { font-size: 22px; }

        .santri-header { align-items: flex-start; }

        .santri-button { width: 100%; }

        .santri-card-header { align-items: stretch; }

        .santri-search { width: 100%; }
    }

    @media print {
        .santri-header .santri-button,
        .santri-card-header,
        .action-column,
        .action-buttons {
            display: none !important;
        }

        .santri-summary { box-shadow: none; }

        .santri-card {
            border: 1px solid #ddd;
            box-shadow: none;
        }

        .santri-table {
            width: 100%;
            font-size: 10px;
        }

        .santri-table thead th,
        .santri-table tbody td {
            padding: 7px;
        }

        .qr-image {
            border: none;
            margin-bottom: 4px;
        }

        .qr-code-text { font-size: 8px; }

        body { background: #fff !important; }
    }
</style>
@endpush

@section('content')
<div class="santri-page">

    <div class="santri-header">
        <div>
            <h2 class="santri-title">Data Santri</h2>
            <p class="santri-subtitle">
                Kelola data santri dan QR Code untuk pengambilan lauk.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button
                type="button"
                class="santri-button"
                style="background:#fff;color:#245b48;border:1px solid #e1e8e2"
                onclick="printSantriQr()">
                <i class="fa-solid fa-print"></i>
                Cetak Data
            </button>

            <a href="{{ route('santri.create') }}"
               class="santri-button btn-add-santri">
                <i class="fa-solid fa-plus"></i>
                Tambah Santri
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="santri-summary">
        <div class="summary-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <p class="summary-label">Total Santri Terdaftar</p>
            <p class="summary-value">{{ $santris->count() }}</p>
        </div>
    </div>

    <div class="santri-card">
        <div class="santri-card-header">
            <h3 class="santri-card-title">
                <i class="fa-solid fa-list-ul me-2"></i>
                Daftar Santri
            </h3>

            <input
                type="search"
                id="searchSantri"
                class="santri-search"
                placeholder="Cari ID, nama, kelas, atau asrama..."
                aria-label="Cari data santri">
        </div>

        <div class="table-responsive">
            <table class="table santri-table" id="santriTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID Santri</th>
                        <th>Nama Santri</th>
                        <th>Kelas</th>
                        <th>Asrama</th>
                        <th class="text-center">QR Code</th>
                        <th class="text-center action-column">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($santris as $index => $santri)
                        @php
                            $kodeQr = $santri->qrCode->kode_qr ?? null;
                        @endphp

                        <tr class="santri-row">
                            <td class="santri-number">
                                {{ $index + 1 }}
                            </td>

                            <td class="santri-id">
                                {{ $santri->idSantri }}
                            </td>

                            <td class="santri-name">
                                {{ $santri->nama_santri }}
                            </td>

                            <td>
                                <span class="room-badge">
                                    <i class="fa-solid fa-graduation-cap me-1"></i>
                                    {{ $santri->kelas }}
                                </span>
                            </td>

                            <td>
                                <span class="room-badge">
                                    <i class="fa-solid fa-door-open me-1"></i>
                                    {{ $santri->kamar }}
                                </span>
                            </td>

                            <td class="qr-cell text-center">
                                @if($kodeQr)
                                    <div
                                        class="qr-image js-qr-code"
                                        data-code="{{ $kodeQr }}">
                                    </div>

                                    <code class="qr-code-text">{{ $kodeQr }}</code>
                                @else
                                    <span class="qr-empty">
                                        QR Code belum tersedia
                                    </span>
                                @endif
                            </td>

                            <td class="text-center action-column">
                                <div class="action-buttons">
                                    <a
                                        href="{{ route('santri.edit', ['santri' => $santri->idSantri]) }}"
                                        class="action-button action-edit"
                                        title="Edit data santri"
                                        aria-label="Edit data santri">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form
                                        action="{{ route('santri.destroy', ['santri' => $santri->idSantri]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data santri ini? Santri yang memiliki riwayat presensi tidak dapat dihapus.')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-button action-delete"
                                            title="Hapus data santri"
                                            aria-label="Hapus data santri">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-users"></i>
                                <strong>Belum ada data santri</strong>
                                <p class="mb-0 mt-2">
                                    Klik tombol Tambah Santri untuk memasukkan data pertama.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noSearchResults" style="display:none">
                        <td colspan="7" class="empty-state">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Data santri tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <i class="fa-solid fa-circle-info me-1"></i>
            QR Code digunakan untuk proses pemindaian saat pengambilan lauk.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const qrContainers = document.querySelectorAll('.js-qr-code');

    if (typeof QRCode === 'undefined') {
        qrContainers.forEach(function (container) {
            container.textContent =
                'QR gagal dimuat. Periksa koneksi internet.';
        });
    } else {
        qrContainers.forEach(function (container) {
            const kode = container.dataset.code;

            if (!kode) return;

            new QRCode(container, {
                text: kode,
                width: 80,
                height: 80,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    }

    const searchInput = document.getElementById('searchSantri');
    const rows = document.querySelectorAll('.santri-row');
    const noResults = document.getElementById('noSearchResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(function (row) {
                const match = row.textContent
                    .toLowerCase()
                    .includes(keyword);

                row.style.display = match ? '' : 'none';

                if (match) visibleCount++;
            });

            if (noResults) {
                noResults.style.display =
                    visibleCount === 0 && rows.length > 0 ? '' : 'none';
            }
        });
    }
});

function printSantriQr() {
    const qrContainers = document.querySelectorAll('.js-qr-code');

    if (qrContainers.length === 0) {
        alert('Belum ada QR Code yang tersedia untuk dicetak.');
        return;
    }

    const allGenerated = Array.from(qrContainers).every(function (container) {
        return container.querySelector('canvas, img') !== null;
    });

    if (!allGenerated) {
        alert(
            'QR Code belum selesai dibuat atau gagal dimuat. ' +
            'Periksa koneksi internet, lalu coba lagi.'
        );
        return;
    }

    window.print();
}
</script>
@endpush
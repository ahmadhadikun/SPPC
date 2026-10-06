<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Pengambilan Lauk Santri</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .print-header { text-align: center; margin-bottom: 20px; }
        .print-header h3 { margin-bottom: 5px; }
        .print-header p { color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #343a40; color: white; text-align: center; }
        .text-center { text-align: center; }
        .text-success { color: #198754; font-weight: bold; }
    </style>
</head>
<body>
    <div class="print-header">
        <h3>LAPORAN REKAPITULASI PENGAMBILAN LAUK SANTRI</h3>
        <p>Sistem Presensi Catering (SPPC) - Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 35%;">Nama Santri</th>
                <th style="width: 15%;">Kamar</th>
                <th style="width: 20%;">Waktu Ambil</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->santri->nis ?? '-' }}</td>
                    <td>{{ $item->santri->nama_santri ?? 'Santri Tidak Ditemukan' }}</td>
                    <td>{{ $item->santri->kamar ?? '-' }}</td>
                    <td>{{ $item->waktu_ambil ? \Carbon\Carbon::parse($item->waktu_ambil)->format('d M Y, H:i') : '-' }}</td>
                    <td class="text-center text-success">Berhasil</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #777;">Belum ada data pengambilan lauk yang tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
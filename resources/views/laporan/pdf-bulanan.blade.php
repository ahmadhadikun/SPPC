
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Bulanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h2, p { text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #333;
            padding: 7px;
            text-align: left;
        }

        th {
            background: #e6eee9;
        }

        .summary { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>REKAP LAPORAN PENGAMBILAN LAUK</h2>
    <p>
        Periode {{ $awalBulan->translatedFormat('F Y') }}
    </p>

    <div class="summary">
        <strong>Total transaksi: {{ $laporan->count() }}</strong>
    </div>

    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th>Waktu Pengambilan</th>
            <th>Nama Santri</th>
            <th>Lauk</th>
            <th>Catering</th>
            <th>Petugas</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($laporan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->waktu_ambil }}</td>
                <td>{{ $item->santri->nama ?? '-' }}</td>
                <td>{{ $item->lauk->nama ?? $item->nama_lauk ?? '-' }}</td>
                <td>{{ $item->catering->nama ?? '-' }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center">
                    Tidak ada transaksi pada periode ini.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

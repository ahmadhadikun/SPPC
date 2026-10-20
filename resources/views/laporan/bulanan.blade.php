<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #243047;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .08);
        }

        h1 {
            margin-top: 0;
        }

        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            margin: 20px 0;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        select,
        input,
        button,
        .btn {
            padding: 10px 13px;
            border-radius: 6px;
            border: 1px solid #d5dbe5;
            font-size: 14px;
        }

        button,
        .btn {
            background: #176b50;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-pdf {
            background: #b42318;
        }

        .summary {
            background: #edf8f2;
            padding: 18px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 11px;
            border: 1px solid #e1e5eb;
            text-align: left;
        }

        th {
            background: #176b50;
            color: white;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .error {
            color: #b42318;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Rekap Laporan Bulanan</h1>
        <p>
            Periode:
            {{ $awalBulan->translatedFormat('F Y') }}
        </p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('laporan.bulanan') }}" method="GET" class="filter">
            <div>
                <label for="bulan">Bulan</label>
                <select name="bulan" id="bulan">
                    @foreach (range(1, 12) as $nomorBulan)
                        <option value="{{ $nomorBulan }}" {{ (int) $bulan === $nomorBulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($nomorBulan)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tahun">Tahun</label>
                <input type="number" id="tahun" name="tahun" min="2000" max="2100"
                    value="{{ $tahun }}" required>
            </div>

            <button type="submit">Tampilkan Rekap</button>

            <a class="btn btn-pdf" href="{{ route('laporan.bulanan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                Unduh PDF
            </a>
        </form>

        <div class="summary">
            <strong>Total transaksi: {{ $jumlahTransaksi }}</strong>
            <p>
                Tanggal:
                {{ $awalBulan->format('d/m/Y') }}
                sampai
                {{ $akhirBulan->format('d/m/Y') }}
            </p>
        </div>

        <div class="table-wrap">
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

                            <td class="text-muted">
                                <i class="fa-regular fa-clock me-1 text-secondary"></i>
                                {{ $item->waktu_ambil
                                    ? \Carbon\Carbon::parse($item->waktu_ambil)->format('d M Y, H:i')
                                    : ($item->created_at
                                        ? $item->created_at->format('d M Y, H:i')
                                        : '-') }}
                            </td>

                            <td>{{ $item->santri->nama_santri ?? '-' }}</td>
                            <td>{{ $item->lauk->nama ?? ($item->nama_lauk ?? '-') }}</td>
                            <td>{{ $item->catering->nama ?? '-' }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center">
                                Belum ada transaksi pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p style="margin-top:25px">
            <a href="{{ route('laporan.index') }}">Kembali ke Laporan</a>
        </p>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Jadwal Catering - SPPC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 700px">
    <h3 class="mb-4">Tambah Jadwal Catering</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('catering.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="tanggal" class="form-label">
                        Tanggal Catering
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        class="form-control"
                        value="{{ old('tanggal') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="sesi" class="form-label">
                        Sesi Makan
                    </label>

                    <select
                        id="sesi"
                        name="sesi"
                        class="form-select"
                        required
                    >
                        <option value="">-- Pilih Sesi --</option>

                        @foreach(['Pagi', 'Siang', 'Sore'] as $sesi)
                            <option
                                value="{{ $sesi }}"
                                @selected(old('sesi') === $sesi)
                            >
                                {{ $sesi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="menu" class="form-label">
                        Menu Makanan
                    </label>

                    <input
                        type="text"
                        id="menu"
                        name="menu"
                        class="form-control"
                        value="{{ old('menu') }}"
                        placeholder="Contoh: Nasi Ayam"
                        maxlength="255"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Jadwal
                </button>

                <a
                    href="{{ route('catering.index') }}"
                    class="btn btn-secondary"
                >
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>

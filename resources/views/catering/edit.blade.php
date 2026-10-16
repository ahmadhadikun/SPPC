<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Jadwal Catering - SPPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 700px">
    <h3 class="mb-4">Edit Jadwal Catering</h3>

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
            <form action="{{ route('catering.update', $catering->id_catering) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tanggal Catering</label>
                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="{{ old('tanggal', $catering->tanggal->format('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Sesi Makan</label>
                    <select name="waktu" class="form-select" required>
                        @foreach(['Pagi', 'Siang', 'Malam'] as $sesi)
                            <option
                                value="{{ $sesi }}"
                                @selected(old('waktu', $catering->waktu) === $sesi)
                            >
                                {{ $sesi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Menu Makanan</label>
                    <input
                        type="text"
                        name="menu"
                        class="form-control"
                        value="{{ old('menu', $catering->menu) }}"
                        maxlength="255"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('catering.index') }}"
                   class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - SPPC</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #263238;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0000000d;
        }
        h1 { margin: 0 0 8px; font-size: 25px; }
        .subtitle { color: #6b7280; margin-bottom: 25px; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }
        input:focus, select:focus {
            outline: 2px solid #bfdbfe;
            border-color: #2563eb;
        }
        .error-text { color: #dc2626; font-size: 13px; margin-top: 5px; }
        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 11px 16px;
            border: 0;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .hint { color: #6b7280; font-size: 12px; margin-top: 5px; }
        @media (max-width: 600px) {
            body { padding: 12px; }
            .container { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit User</h1>
    <div class="subtitle">
        Perbarui informasi akun pengguna SPPC.
    </div>

    @if ($errors->any())
        <div class="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                maxlength="255"
                autocomplete="name"
            >
            @error('name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                maxlength="255"
                autocomplete="email"
            >
            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role Pengguna</label>
            <select id="role" name="role" required>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>
                    Admin
                </option>
                <option value="catering" @selected(old('role', $user->role) === 'catering')>
                    Pengurus Catering
                </option>
                <option value="pengasuh" @selected(old('role', $user->role) === 'pengasuh')>
                    Pengasuh
                </option>
            </select>
            @error('role')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0;">

        <p><strong>Ganti Password (Opsional)</strong></p>
        <div class="hint" style="margin-bottom: 15px;">
            Kosongkan kedua kolom password jika tidak ingin menggantinya.
        </div>

        <div class="form-group">
            <label for="password">Password Baru</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 8 karakter"
                minlength="8"
                autocomplete="new-password"
            >
            @error('password')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
</body>
</html>

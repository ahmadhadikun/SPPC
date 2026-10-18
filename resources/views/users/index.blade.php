<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - SPPC</title>
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
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0000000d;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        h1 { margin: 0 0 8px; font-size: 25px; }
        .muted { color: #6b7280; font-size: 14px; }
        .btn {
            display: inline-block;
            padding: 9px 13px;
            border: 0;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-primary { background: #2563eb; }
        .btn-edit { background: #d97706; }
        .btn-delete { background: #dc2626; }
        .btn-back { background: #4b5563; }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 14px;
        }
        th { background: #f8fafc; }
        .role {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            background: #e5e7eb;
        }
        .role-admin { background: #dbeafe; color: #1d4ed8; }
        .role-catering { background: #dcfce7; color: #166534; }
        .role-pengasuh { background: #f3e8ff; color: #7e22ce; }
        .alert {
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 7px;
        }
        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }
        .actions form { margin: 0; }
        @media (max-width: 600px) {
            body { padding: 12px; }
            .container { padding: 15px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>Kelola Users</h1>
            <div class="muted">Sistem Presensi Catering (SPPC)</div>
        </div>
        <div class="actions">
            <a href="{{ route('dashboard') }}" class="btn btn-back">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                + Tambah User
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role role-{{ $user->role }}">
                                @switch($user->role)
                                    @case('admin')
                                        Admin
                                        @break
                                    @case('catering')
                                        Pengurus Catering
                                        @break
                                    @case('pengasuh')
                                        Pengasuh
                                        @break
                                    @default
                                        {{ $user->role }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-edit">
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="muted">Akun Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:25px;">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

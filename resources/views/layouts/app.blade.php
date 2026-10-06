<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPPC - Sistem Presensi Catering')</title>
    
    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: white; width: 260px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #0f172a; color: #fff; }
        
        /* Pengaturan khusus saat halaman dicetak */
        @media print {
            .sidebar, .btn, form { display: none !important; }
            .flex-grow-1 { padding: 0 !important; }
            body { background-color: white !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <div class="sidebar p-3 d-flex flex-column">
            <h4 class="text-center py-3 border-bottom border-secondary">
                <i class="fa-solid fa-utensils me-2"></i>SPPC
            </h4>
            
            <div class="nav flex-column mt-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>

                @if(auth()->check())
                    @php $role = auth()->user()->role; @endphp

                    @if($role === 'admin')
                        <!-- MENU KHUSUS ADMIN (Akses Penuh) -->
                        <a href="{{ route('santri.index') }}" class="{{ request()->is('santri*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users me-2"></i> Data Santri
                        </a>
                        <a href="{{ route('scan.index') }}" class="{{ request()->is('scan*') ? 'active' : '' }}">
                            <i class="fa-solid fa-qrcode me-2"></i> Scan QR Catering
                        </a>

                    @elseif($role === 'catering')
                        <!-- MENU KHUSUS CATERING (Hanya Scanner) -->
                        <a href="{{ route('scan.index') }}" class="{{ request()->is('scan*') ? 'active' : '' }}">
                            <i class="fa-solid fa-qrcode me-2"></i> Scan QR Catering
                        </a>

                    @elseif($role === 'pengasuh')
                        <!-- MENU KHUSUS PENGASUH -->
                        <a href="{{ route('laporan.index') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-lines me-2"></i> Data Laporan
                        </a>
                        <a href="#" onclick="window.print(); return false;" class="">
                            <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                        </a>
                    @endif
                @endif
            </div>

            <div class="mt-auto pt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 mt-4">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-grow-1 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
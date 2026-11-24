
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SPPC - Sistem Pengambilan Lauk')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sp-primary: #245b48;
            --sp-dark: #194535;
            --sp-light: #e9f1eb;
            --sp-bg: #f5f7f5;
            --sp-text: #26372f;
            --sp-muted: #7b8981;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--sp-bg);
            color: var(--sp-text);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            min-width: 260px;
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #e7ece8;
            padding: 22px 15px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px 24px;
            border-bottom: 1px solid #edf0ed;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            display: grid;
            place-items: center;
            background: var(--sp-primary);
            color: #fff;
            border-radius: 14px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .brand-name {
            font-size: 21px;
            font-weight: 800;
            margin: 0;
            color: var(--sp-primary);
        }

        .brand-caption {
            color: var(--sp-muted);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .nav-label {
            padding: 0 12px;
            margin: 0 0 10px;
            color: #9aa69e;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #68776e;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 13px;
            margin-bottom: 5px;
            border-radius: 11px;
            text-decoration: none;
            transition: .2s ease;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar .nav-link:hover {
            background: #f1f6f2;
            color: var(--sp-primary);
        }

        .sidebar .nav-link.active {
            background: var(--sp-light);
            color: var(--sp-primary);
            font-weight: 750;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 24px;
        }

        .account-card {
            background: #f5f8f5;
            border-radius: 13px;
            padding: 12px;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        .account-name {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .account-role {
            color: var(--sp-muted);
            font-size: 11px;
            margin: 3px 0 0;
            text-transform: capitalize;
        }

        .logout-button {
            width: 100%;
            border: 1px solid #f1dada;
            border-radius: 11px;
            padding: 11px;
            background: #fff5f5;
            color: #b54747;
            font-size: 13px;
            font-weight: 700;
        }

        .logout-button:hover {
            background: #ffe8e8;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 28px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 22px;
        }

        .page-label {
            color: var(--sp-muted);
            font-size: 12px;
            margin: 0;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #e1e8e2;
            border-radius: 10px;
            padding: 9px 13px;
            background: #fff;
            color: var(--sp-primary);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .back-button:hover {
            background: var(--sp-light);
            color: var(--sp-dark);
        }

        .menu-toggle {
            display: none;
            border: 1px solid #e1e8e2;
            border-radius: 10px;
            padding: 9px 12px;
            background: #fff;
            color: var(--sp-primary);
            flex-shrink: 0;
        }

        @media (max-width: 767.98px) {
            .app-shell {
                display: block !important;
            }

            .sidebar {
                width: 100%;
                min-width: 0;
                min-height: auto;
                padding: 12px;
                border-right: 0;
                border-bottom: 1px solid #e7ece8;
            }

            .brand {
                margin-bottom: 10px;
                padding-bottom: 12px;
            }

            .menu-toggle {
                display: inline-block;
                margin-left: auto;
            }

            .sidebar-nav {
                display: none;
                padding-top: 12px;
            }

            .sidebar-nav.show {
                display: block;
            }

            .sidebar-footer {
                padding-top: 12px;
            }

            .account-card {
                margin-bottom: 8px;
            }

            .main-content {
                padding: 18px 14px;
            }

            .topbar {
                align-items: flex-start;
            }
        }

        @media print {
            .sidebar,
            .topbar,
            .btn,
            button,
            form {
                display: none !important;
            }

            .main-content {
                padding: 0 !important;
            }

            body {
                background: #fff !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="app-shell d-flex">

        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>

                <div>
                    <h1 class="brand-name">SPPC</h1>
                    <p class="brand-caption">Sistem Pengambilan Lauk</p>
                </div>

                <button
                    class="menu-toggle"
                    type="button"
                    aria-label="Buka menu"
                    aria-expanded="false"
                    aria-controls="sidebar-navigation"
                    onclick="toggleSidebar(this)"
                >
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <nav id="sidebar-navigation" class="sidebar-nav">
                <p class="nav-label">Menu Utama</p>

                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <i class="fa-solid fa-gauge-high"></i>
                    Dashboard
                </a>

                @auth
                    @php($role = auth()->user()->role)

                    @if($role === 'admin')
                        <a
                            href="{{ route('santri.index') }}"
                            class="nav-link {{ request()->routeIs('santri.*') ? 'active' : '' }}"
                        >
                            <i class="fa-solid fa-users"></i>
                            Data Santri
                        </a>

                        <a
                            href="{{ route('scan.index') }}"
                            class="nav-link {{ request()->routeIs('scan.*') ? 'active' : '' }}"
                        >
                            <i class="fa-solid fa-qrcode"></i>
                            Scan QR Catering
                        </a>

                        <a
                            href="{{ route('laporan.index') }}"
                            class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
                        >
                            <i class="fa-solid fa-file-lines"></i>
                            Laporan Sistem
                        </a>

                    @elseif($role === 'catering')
                        <a
                            href="{{ route('scan.index') }}"
                            class="nav-link {{ request()->routeIs('scan.*') ? 'active' : '' }}"
                        >
                            <i class="fa-solid fa-qrcode"></i>
                            Scan QR Catering
                        </a>

                    @elseif($role === 'pengasuh')
                        <a
                            href="{{ route('laporan.index') }}"
                            class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
                        >
                            <i class="fa-solid fa-file-lines"></i>
                            Data Laporan
                        </a>

                        <a
                            href="#"
                            onclick="window.print(); return false;"
                            class="nav-link"
                        >
                            <i class="fa-solid fa-print"></i>
                            Cetak Laporan
                        </a>
                    @endif
                @endauth
            </nav>

            <div class="sidebar-footer">
                @auth
                    <div class="account-card">
                        <p class="account-name">
                            {{ auth()->user()->name ?? 'Pengguna SPPC' }}
                        </p>

                        <p class="account-role">
                            {{ auth()->user()->role ?? 'Pengguna' }}
                        </p>
                    </div>
                @endauth

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="topbar">
                <p class="page-label">
                    SPPC / @yield('title', 'Dashboard')
                </p>

                <a
                    href="{{ url()->previous() }}"
                    class="back-button"
                    onclick="if (document.referrer === '' || new URL(document.referrer).origin !== window.location.origin) { window.location.href = '{{ route('dashboard') }}'; return false; }"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div>

            @if(session('success'))
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>
                </div>
            @endif

            {{-- Tampilkan pesan validasi jika tersedia --}}
            @if(isset($errors) && $errors->any())
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >
                    <strong>Periksa kembali data yang dimasukkan.</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar(button) {
            const nav = document.getElementById('sidebar-navigation');
            const isOpen = nav.classList.toggle('show');

            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
    </script>

    @stack('scripts')
</body>
</html>

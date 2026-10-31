
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Absensi Digital</title>

    <style>
        :root {
            --navy: #17243b;
            --navy-light: #223451;
            --blue: #3b82f6;
            --blue-light: #eaf2ff;
            --bg: #f5f7fb;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e8edf4;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }

        a {
            color: inherit;
        }

        .app-header {
            min-height: 76px;
            padding: 0 30px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--navy);
            color: white;
            display: grid;
            place-items: center;
            font-size: 21px;
        }

        .brand small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--blue-light);
            color: #2457d6;
            font-weight: 800;
            font-size: 16px;
        }

        .user-details strong {
            display: block;
            font-size: 13px;
        }

        .user-details span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .logout-button {
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: white;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .logout-button:hover {
            background: #fff1f2;
            color: #be123c;
            border-color: #fecdd3;
        }

        .app-layout {
            display: flex;
            min-height: calc(100vh - 76px);
        }

        .sidebar {
            width: 258px;
            flex-shrink: 0;
            padding: 27px 17px;
            background: var(--navy);
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-heading {
            padding: 0 13px;
            margin: 0 0 15px;
            color: #9aabc3;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #c7d2e3;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background .2s, color .2s;
        }

        .menu-icon {
            width: 22px;
            flex-shrink: 0;
            text-align: center;
            font-size: 17px;
        }

        .menu-link:hover {
            background: var(--navy-light);
            color: white;
        }

        .menu-link.active {
            background: var(--blue);
            color: white;
            box-shadow: 0 5px 14px rgba(59, 130, 246, .22);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 20px 12px 0;
        }

        .sidebar-note {
            padding: 15px;
            border: 1px solid #34445d;
            border-radius: 12px;
            color: #d5deeb;
            font-size: 12px;
            line-height: 1.7;
        }

        .sidebar-note strong {
            display: block;
            margin-bottom: 5px;
            color: white;
        }

        .main-area {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .page-top {
            margin-bottom: 25px;
        }

        .breadcrumb {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .page-heading {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.6px;
            color: #172033;
        }

        .welcome-card {
            position: relative;
            overflow: hidden;
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 16px;
            background: linear-gradient(120deg, #1e3a5f, #2563eb);
            color: white;
            box-shadow: 0 8px 24px rgba(37, 99, 235, .12);
        }

        .welcome-card h2 {
            margin: 0 0 10px;
            font-size: 25px;
        }

        .welcome-card p {
            max-width: 650px;
            margin: 0;
            color: #e5edff;
            line-height: 1.8;
        }

        .section-title {
            margin: 0 0 17px;
            font-size: 18px;
            font-weight: 750;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        
        .content-card {
            min-width: 0;
            min-height: 180px;
            padding: 24px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .025);

            display: flex;
            flex-direction: column;

            transition:
                box-shadow .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .content-card:hover {
            border-color: #d5e3fa;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .07);
            transform: translateY(-2px);
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .card-heading h3 {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
        }

        .card-icon {
            flex-shrink: 0;
            font-size: 20px;
        }

        .content-card p {
            margin: 0 0 16px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .card-action {
            margin-top: auto;
            padding-top: 4px;
        }

        .card-action .primary-button {
            max-width: 100%;
        }

        .feature-note {
            margin-top: auto !important;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f5f7fb;
            font-size: 12px !important;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 6px;
            border-radius: 50%;
            background: #94a3b8;
        }


        .content-card h3 {
            margin: 0 0 10px;
            font-size: 17px;
        }

        .content-card p {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .primary-button,
        .secondary-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 16px;
            border: 0;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .primary-button {
            background: var(--blue);
            color: white;
        }

        .primary-button:hover {
            background: #1d4ed8;
        }

        .secondary-button {
            background: #edf1f7;
            color: #475569;
        }

        .secondary-button:hover {
            background: #dfe7f1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            padding: 21px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .025);
        }

        .stat-card .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .stat-card .stat-value {
            margin-top: 12px;
            font-size: 28px;
            font-weight: 800;
            color: #172033;
        }

        .stat-card .stat-icon {
            margin-top: 12px;
            font-size: 22px;
        }

        .table-card {
            overflow: hidden;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 15px 18px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        tbody tr:hover {
            background: #f8fbff;
        }

        .footer {
            padding: 25px 0 5px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar {
                width: 225px;
            }

            .main-area {
                padding: 24px;
            }
        }

        @media (max-width: 760px) {
            .app-header {
                min-height: 68px;
                padding: 12px 16px;
                flex-wrap: wrap;
            }

            .brand {
                font-size: 17px;
            }

            .brand-mark {
                width: 36px;
                height: 36px;
            }

            .header-right {
                gap: 10px;
            }

            .user-details strong {
                max-width: 130px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .app-layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 16px 12px;
            }

            .sidebar-heading {
                margin-bottom: 10px;
            }

            .sidebar nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 4px;
            }

            .menu-link {
                margin: 0;
                padding: 11px 9px;
                gap: 8px;
                font-size: 12px;
            }

            .sidebar-bottom {
                display: none;
            }

            .main-area {
                padding: 22px 16px;
            }

            .page-heading {
                font-size: 23px;
            }

            .welcome-card {
                padding: 23px;
            }

            .welcome-card h2 {
                font-size: 21px;
            }

            .card-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
        }

        @media (max-width: 420px) {
            .brand small {
                font-size: 10px;
            }

            .user-profile {
                gap: 7px;
            }

            .avatar {
                width: 34px;
                height: 34px;
                font-size: 13px;
            }

            .user-details span {
                font-size: 11px;
            }

            .logout-button {
                padding: 9px 10px;
                font-size: 12px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .sidebar nav {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $role = $user->role;

        $roleLabels = [
            'admin' => 'Admin',
            'tutor' => 'Tutor',
            'peserta_didik' => 'Peserta Didik',
        ];

        $initial = strtoupper(substr($user->nama ?? 'U', 0, 1));
    @endphp

    <header class="app-header">
        <div class="brand">
            <div class="brand-mark">✓</div>
            <div>
                Absensi Digital
                <small>Sistem Informasi Kehadiran</small>
            </div>
        </div>

        <div class="header-right">
            <div class="user-profile">
                <div class="avatar">{{ $initial }}</div>
                <div class="user-details">
                    <strong>{{ $user->nama }}</strong>
                    <span>{{ $roleLabels[$role] ?? $role }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">↪ Logout</button>
            </form>
        </div>
    </header>

    <div class="app-layout">
        <aside class="sidebar">
            <p class="sidebar-heading">Menu Utama</p>

            <nav>
                {{-- MENU ADMIN --}}
                @if ($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">⌂</span> Dashboard
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="menu-icon">♙</span> Manajemen Pengguna
                    </a>

                    <a href="{{ route('admin.peserta.index') }}"
                       class="menu-link {{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                        <span class="menu-icon">🎓</span> Data Peserta Didik
                    </a>

                    <a href="{{ route('admin.tutor.index') }}"
                       class="menu-link {{ request()->routeIs('admin.tutor.*') ? 'active' : '' }}">
                        <span class="menu-icon">♙</span> Data Tutor
                    </a>

                    {{--KELAS DAN JADWAL--}}
                    <a href="{{ route('admin.kelas.index') }}"
                       class="menu-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                        <span class="menu-icon">📚</span> Kelas dan Jadwal
                    </a>

                    <a href="{{ route('admin.absensi.index') }}"
                       class="menu-link {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                        <span class="menu-icon">▣</span> Manajemen Absensi
                    </a>

                    <a href="{{ route('admin.rekap.index') }}"
                       class="menu-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
                        <span class="menu-icon">▤</span> Rekap Absensi
                    </a>

                {{-- MENU TUTOR --}}
                @elseif ($role === 'tutor')
                    <a href="{{ route('tutor.dashboard') }}"
                       class="menu-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">⌂</span> Dashboard
                    </a>

                    <a href="{{ route('tutor.jadwal.index') }}"
                       class="menu-link {{ request()->routeIs('tutor.jadwal.*') ? 'active' : '' }}">
                        <span class="menu-icon">▦</span> Jadwal Mengajar
                    </a>

                    <a href="{{ route('tutor.absensi.index') }}"
                       class="menu-link {{ request()->routeIs('tutor.absensi.*') ? 'active' : '' }}">
                        <span class="menu-icon">▣</span> Input Absensi
                    </a>

                    <a href="{{ route('tutor.rekap.index') }}"
                       class="menu-link {{ request()->routeIs('tutor.rekap.*') ? 'active' : '' }}">
                        <span class="menu-icon">▤</span> Rekap Absensi
                    </a>

              
                {{-- MENU PESERTA DIDIK --}}
                @elseif ($role === 'peserta_didik')
                    <a href="{{ route('peserta.dashboard') }}"
                    class="menu-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">⌂</span>
                        Dashboard
                    </a>

                @endif
                </nav>


            <div class="sidebar-bottom">
                <div class="sidebar-note">
                    <strong>Absensi Digital</strong>
                    Sistem pencatatan kehadiran yang mudah dan terintegrasi.
                </div>
            </div>
        </aside>

        <main class="main-area">
            <div class="page-top">
                <div class="breadcrumb">
                    Absensi Digital / {{ $roleLabels[$role] ?? $role }}
                </div>

                <h1 class="page-heading">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>

            @yield('content')

            <footer class="footer">
                &copy; {{ date('Y') }} Aplikasi Absensi Digital
            </footer>
        </main>
    </div>
</body>
</html>

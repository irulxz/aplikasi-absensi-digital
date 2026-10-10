<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - Absensi Digital</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .navbar {
            min-height: 76px;
            padding: 16px 30px;
            background: #2457d6;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        .user-info {
            text-align: right;
            line-height: 1.5;
            font-size: 14px;
        }

        .logout-button {
            display: inline-block;
            background: white;
            color: #2457d6;
            padding: 11px 17px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .logout-button:hover {
            background: #dbeafe;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 76px);
        }

        .sidebar {
            width: 250px;
            flex-shrink: 0;
            padding: 25px 17px;
            background: white;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-title {
            margin: 0 12px 16px;
            color: #64748b;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .menu-link {
            display: block;
            margin-bottom: 7px;
            padding: 13px 15px;
            border-radius: 8px;
            color: #334155;
            text-decoration: none;
            font-size: 14px;
        }

        .menu-link:hover {
            background: #eff6ff;
            color: #2457d6;
        }

        .menu-link.active {
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: bold;
        }

        .menu-disabled {
            display: block;
            margin-bottom: 7px;
            padding: 13px 15px;
            color: #94a3b8;
            font-size: 14px;
            cursor: default;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 32px;
        }

        .page-heading {
            margin: 0 0 24px;
            font-size: 26px;
        }

        .welcome-card {
            padding: 30px;
            margin-bottom: 28px;
            border-radius: 14px;
            color: white;
            background: linear-gradient(120deg, #2457d6, #3b82f6);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.12);
        }

        .welcome-card h2 {
            margin: 0 0 12px;
            font-size: 27px;
        }

        .welcome-card p {
            margin: 0;
            line-height: 1.7;
        }

        .section-title {
            margin: 0 0 17px;
            font-size: 21px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .content-card {
            padding: 25px;
            background: white;
            border: 1px solid #e8edf4;
            border-radius: 13px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .content-card h3 {
            margin: 0 0 12px;
            font-size: 19px;
        }

        .content-card p {
            margin: 0 0 18px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
        }

        .primary-button {
            display: inline-block;
            padding: 11px 16px;
            background: #2457d6;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 7px;
            font-size: 14px;
            cursor: pointer;
        }

        .primary-button:hover {
            background: #1d4ed8;
        }

        .secondary-button {
            display: inline-block;
            padding: 11px 16px;
            background: #94a3b8;
            color: white;
            border-radius: 7px;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .navbar {
                padding: 16px;
                align-items: flex-start;
            }

            .brand {
                font-size: 20px;
            }

            .navbar-right {
                gap: 10px;
                flex-wrap: wrap;
            }

            .user-info {
                font-size: 12px;
            }

            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 15px;
            }

            .main-content {
                padding: 20px 16px;
            }

            .card-grid {
                grid-template-columns: 1fr;
            }

            .welcome-card {
                padding: 23px;
            }

            .welcome-card h2 {
                font-size: 23px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                flex-direction: column;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    @php
        $role = auth()->user()->role;

        $roleLabels = [
            'admin' => 'Admin',
            'tutor' => 'Tutor',
            'peserta_didik' => 'Peserta Didik',
        ];
    @endphp

    <header class="navbar">
        <div class="brand">Absensi Digital</div>

        <div class="navbar-right">
            <div class="user-info">
                <strong>{{ auth()->user()->nama }}</strong>
                <div>{{ $roleLabels[$role] ?? $role }}</div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <p class="sidebar-title">Menu Utama</p>

            @if ($role === 'admin')
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                >
                    Management Pengguna
                </a>

            @elseif ($role === 'tutor')
                <a
                    href="{{ route('tutor.dashboard') }}"
                    class="menu-link {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

                <span class="menu-disabled">
                    Aktivitas Tutor — segera hadir
                </span>

            @elseif ($role === 'peserta_didik')
                <a
                    href="{{ route('peserta.dashboard') }}"
                    class="menu-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

                <span class="menu-disabled">
                    Aktivitas Peserta — segera hadir
                </span>
            @endif
        </aside>

        <main class="main-content">
            <h1 class="page-heading">
                @yield('page-title', 'Dashboard')
            </h1>

            @yield('content')

            <footer class="footer">
                &copy; {{ date('Y') }} Aplikasi Absensi Digital
            </footer>
        </main>
    </div>
</body>
</html>
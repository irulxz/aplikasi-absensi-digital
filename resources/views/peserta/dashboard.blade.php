@extends('layouts.dashboard')

@section('title', 'Dashboard Peserta Didik')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .student-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        color: #24344d;
    }

    .student-dashboard .welcome-card {
        position: relative;
        overflow: hidden;
        padding: 30px;
        margin-bottom: 25px;
        border-radius: 18px;
        background: linear-gradient(120deg, #2457d6, #4789f5);
        color: white;
        box-shadow: 0 8px 24px rgba(37, 87, 214, .15);
    }

    .student-dashboard .welcome-card::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -45px;
        top: -65px;
        border-radius: 50%;
        background: rgba(255,255,255,.09);
        pointer-events: none;
    }

    .student-dashboard .welcome-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        opacity: .85;
        margin-bottom: 12px;
    }

    .student-dashboard .welcome-card h2 {
        font-size: 28px;
        font-weight: 750;
        margin: 0 0 12px;
        color: white;
    }

    .student-dashboard .welcome-card p {
        max-width: 560px;
        margin: 0;
        line-height: 1.7;
        color: rgba(255,255,255,.9);
    }

    .student-dashboard .welcome-badge {
        display: inline-block;
        margin-top: 20px;
        padding: 8px 13px;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 30px;
        background: rgba(255,255,255,.12);
        font-size: 13px;
    }

    .student-dashboard .section-heading {
        margin-bottom: 16px;
    }

    .student-dashboard .section-heading h3 {
        margin: 0 0 5px;
        color: #1e2e47;
        font-size: 19px;
        font-weight: 750;
    }

    .student-dashboard .section-heading p {
        margin: 0;
        color: #7b8799;
        font-size: 13px;
    }

    .student-dashboard .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .student-dashboard .stat-card {
        position: relative;
        padding: 22px;
        border: 1px solid #e9edf4;
        border-radius: 15px;
        background: white;
        box-shadow: 0 4px 15px rgba(31, 48, 78, .035);
        transition: transform .2s, box-shadow .2s;
    }

    .student-dashboard .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(31, 48, 78, .08);
    }

    .student-dashboard .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 20px;
    }

    .student-dashboard .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        font-size: 23px;
        font-weight: 700;
    }

    .student-dashboard .stat-label {
        color: #718096;
        font-size: 13px;
        font-weight: 600;
    }

    .student-dashboard .stat-number {
        margin-top: 8px;
        color: #1d2b43;
        font-size: 34px;
        line-height: 1.2;
        font-weight: 800;
    }

    .student-dashboard .stat-caption {
        margin-top: 8px;
        color: #9aa4b3;
        font-size: 12px;
    }

    .student-dashboard .hadir .stat-icon {
        color: #16835b;
        background: #e5f7ef;
    }

    .student-dashboard .alpa .stat-icon {
        color: #d94343;
        background: #fff0f0;
    }

    .student-dashboard .izin .stat-icon {
        color: #3475df;
        background: #eaf1ff;
    }

    .student-dashboard .sakit .stat-icon {
        color: #c87918;
        background: #fff4df;
    }

    .student-dashboard .bottom-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        gap: 20px;
    }

    .student-dashboard .info-card {
        padding: 25px;
        border: 1px solid #e9edf4;
        border-radius: 15px;
        background: white;
        box-shadow: 0 4px 15px rgba(31, 48, 78, .035);
    }

    .student-dashboard .info-card h3 {
        margin: 0 0 8px;
        color: #1e2e47;
        font-size: 17px;
        font-weight: 750;
    }

    .student-dashboard .muted {
        color: #8390a3;
        font-size: 13px;
        line-height: 1.7;
    }

    .student-dashboard .attendance-score {
        display: flex;
        align-items: center;
        gap: 22px;
        margin-top: 22px;
    }

    .student-dashboard .score-number {
        color: #2457d6;
        font-size: 36px;
        font-weight: 800;
        white-space: nowrap;
    }

    .student-dashboard .progress-track {
        height: 10px;
        overflow: hidden;
        border-radius: 20px;
        background: #edf1f7;
    }

    .student-dashboard .progress-fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #2457d6, #56a0ff);
        transition: width .3s;
    }

    .student-dashboard .score-description {
        margin-top: 8px;
        color: #8390a3;
        font-size: 12px;
    }

    .student-dashboard .profile-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px 0;
        border-bottom: 1px solid #eef1f6;
    }

    .student-dashboard .profile-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .student-dashboard .profile-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #edf3ff;
        color: #3268d6;
        font-size: 19px;
    }

    .student-dashboard .profile-value {
        margin-top: 4px;
        color: #24344d;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .student-dashboard .empty-notice {
        padding: 14px;
        border-radius: 10px;
        background: #f6f8fc;
        color: #7b8799;
        font-size: 13px;
        line-height: 1.7;
    }

    @media (max-width: 900px) {
        .student-dashboard .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .student-dashboard .bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .student-dashboard .welcome-card {
            padding: 23px;
        }

        .student-dashboard .welcome-card h2 {
            font-size: 23px;
        }

        .student-dashboard .stats-grid {
            gap: 12px;
        }

        .student-dashboard .stat-card {
            padding: 16px;
        }

        .student-dashboard .stat-number {
            font-size: 29px;
        }

        .student-dashboard .attendance-score {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<div class="student-dashboard">

    {{-- Sambutan --}}
    <section class="welcome-card">
        <div class="welcome-label">DASHBOARD PESERTA DIDIK</div>

        <h2>
            Selamat datang{{ $peserta ? ', ' . $peserta->nama : '' }}! 👋
        </h2>

        <p>
            Senang melihatmu kembali. Pantau kehadiran dan
            aktivitas belajarmu di sini agar tetap semangat
            mencapai hasil belajar terbaik.
        </p>

        @if ($peserta && $peserta->program_paket)
            <div class="welcome-badge">
                ✦ Program Paket {{ $peserta->program_paket }}
            </div>
        @endif
    </section>

    {{-- Statistik Absensi --}}
    <div class="section-heading">
        <h3>Ringkasan Kehadiran</h3>
        <p>Rekap seluruh catatan absensi milikmu.</p>
    </div>

    <div class="stats-grid">

        <div class="stat-card hadir">
            <div class="stat-top">
                <div class="stat-label">Hadir</div>
                <div class="stat-icon">✓</div>
            </div>
            <div class="stat-number">{{ $statistik['Hadir'] }}</div>
            <div class="stat-caption">Kehadiran tercatat</div>
        </div>

        <div class="stat-card alpa">
            <div class="stat-top">
                <div class="stat-label">Alpa</div>
                <div class="stat-icon">!</div>
            </div>
            <div class="stat-number">{{ $statistik['Alpa'] }}</div>
            <div class="stat-caption">Tanpa keterangan</div>
        </div>

        <div class="stat-card izin">
            <div class="stat-top">
                <div class="stat-label">Izin</div>
                <div class="stat-icon">✉</div>
            </div>
            <div class="stat-number">{{ $statistik['Izin'] }}</div>
            <div class="stat-caption">Izin tercatat</div>
        </div>

        <div class="stat-card sakit">
            <div class="stat-top">
                <div class="stat-label">Sakit</div>
                <div class="stat-icon">+</div>
            </div>
            <div class="stat-number">{{ $statistik['Sakit'] }}</div>
            <div class="stat-caption">Sakit tercatat</div>
        </div>

    </div>

    {{-- Informasi Tambahan --}}
    <div class="bottom-grid">

        <section class="info-card">
            <h3>Persentase Kehadiran</h3>
            <p class="muted">
                Persentase kehadiran berdasarkan seluruh catatan absensi.
            </p>

            <div class="attendance-score">
                <div class="score-number">{{ $persentaseHadir }}%</div>

                <div style="flex: 1; width: 100%;">
                    <div class="progress-track">
                        <div class="progress-fill"
                             style="width: {{ $persentaseHadir }}%;">
                        </div>
                    </div>

                    <div class="score-description">
                        {{ $statistik['Hadir'] }} dari
                        {{ $totalAbsensi }} catatan absensi berstatus Hadir
                    </div>
                </div>
            </div>

            @if ($totalAbsensi === 0)
                <div class="empty-notice" style="margin-top: 20px;">
                    Belum ada data absensi. Ringkasan akan diperbarui
                    setelah absensi tercatat.
                </div>
            @endif
        </section>

        <section class="info-card">
            <h3>Informasi Peserta</h3>
            <p class="muted">
                Informasi dasar akun peserta didik.
            </p>

            @if ($peserta)
                <div class="profile-row">
                    <div class="profile-icon">♙</div>
                    <div>
                        <div class="muted">Nama Peserta Didik</div>
                        <div class="profile-value">{{ $peserta->nama }}</div>
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-icon">▤</div>
                    <div>
                        <div class="muted">Nomor Induk</div>
                        <div class="profile-value">
                            {{ $peserta->nis ?: 'Belum diisi' }}
                        </div>
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-icon">▦</div>
                    <div>
                        <div class="muted">Program Paket</div>
                        <div class="profile-value">
                            {{ $peserta->program_paket
                                ? 'Paket ' . $peserta->program_paket
                                : 'Belum ditentukan' }}
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-notice">
                    Data peserta didik belum terhubung dengan akun ini.
                    Silakan hubungi Admin.
                </div>
            @endif
        </section>

    </div>
</div>

@endsection

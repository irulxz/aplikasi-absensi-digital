@extends('layouts.dashboard')

@section('title', 'Dashboard Tutor')
@section('page-title', 'Dashboard Tutor')

@section('content')

<style>
    .tutor-welcome {
        position: relative;
        overflow: hidden;
        padding: 30px;
        margin-bottom: 28px;
        border-radius: 16px;
        background: linear-gradient(120deg, #1e3a8a, #2563eb);
        color: #fff;
    }

    .tutor-welcome::after {
        content: '';
        position: absolute;
        width: 190px;
        height: 190px;
        right: -45px;
        top: -75px;
        border: 30px solid rgba(255, 255, 255, .08);
        border-radius: 50%;
        pointer-events: none;
    }

    .tutor-welcome-label {
        margin: 0 0 10px;
        color: #dbeafe;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .tutor-welcome h2 {
        position: relative;
        z-index: 1;
        margin: 0 0 10px;
        font-size: 27px;
        line-height: 1.4;
    }

    .tutor-welcome p {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: 0;
        color: #dbeafe;
        font-size: 14px;
        line-height: 1.8;
    }

    .tutor-section-title {
        margin: 0 0 17px;
        color: #17243b;
        font-size: 19px;
        font-weight: 800;
    }

    .tutor-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .tutor-summary-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 23px;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .025);
    }

    .tutor-summary-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 23px;
    }

    .tutor-summary-label {
        margin: 2px 0 8px;
        color: #64748b;
        font-size: 13px;
    }

    .tutor-summary-number {
        margin: 0;
        color: #17243b;
        font-size: 27px;
        font-weight: 800;
    }

    .tutor-summary-description {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    .tutor-status {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .tutor-status-active {
        background: #dcfce7;
        color: #166534;
    }

    .tutor-status-warning {
        background: #fff7ed;
        color: #c2410c;
    }

    .tutor-schedule-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 14px;
    }

    .tutor-schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 22px 24px;
        border-bottom: 1px solid #e8edf4;
    }

    .tutor-schedule-header h3 {
        margin: 0 0 5px;
        color: #17243b;
        font-size: 16px;
    }

    .tutor-schedule-header p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
    }

    .tutor-schedule-count {
        padding: 7px 11px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .tutor-schedule-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        padding: 22px;
    }

    .tutor-schedule-item {
        padding: 20px;
        border: 1px solid #e8edf4;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s;
    }

    .tutor-schedule-item:hover {
        border-color: #bfdbfe;
        box-shadow: 0 4px 14px rgba(37, 99, 235, .06);
    }

    .tutor-subject-label {
        display: inline-block;
        padding: 6px 9px;
        margin-bottom: 12px;
        border-radius: 7px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }

    .tutor-schedule-item h4 {
        margin: 0 0 17px;
        color: #17243b;
        font-size: 16px;
        line-height: 1.5;
    }

    .tutor-schedule-detail {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding-top: 12px;
        margin-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .tutor-detail-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 33px;
        height: 33px;
        border-radius: 9px;
        background: #f8fafc;
        font-size: 15px;
    }

    .tutor-detail-label {
        margin: 0 0 4px;
        color: #64748b;
        font-size: 11px;
    }

    .tutor-detail-value {
        margin: 0;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
    }

    .tutor-empty-state {
        padding: 42px 22px;
        text-align: center;
    }

    .tutor-empty-icon {
        display: grid;
        place-items: center;
        width: 60px;
        height: 60px;
        margin: 0 auto 16px;
        border-radius: 16px;
        background: #eff6ff;
        font-size: 27px;
    }

    .tutor-empty-state h4 {
        margin: 0 0 8px;
        color: #17243b;
        font-size: 16px;
    }

    .tutor-empty-state p {
        max-width: 400px;
        margin: 0 auto;
        color: #64748b;
        font-size: 13px;
        line-height: 1.8;
    }

    @media (max-width: 800px) {
        .tutor-summary-grid,
        .tutor-schedule-list {
            grid-template-columns: 1fr;
        }

        .tutor-welcome {
            padding: 24px;
        }

        .tutor-welcome h2 {
            font-size: 23px;
        }
    }

    @media (max-width: 480px) {
        .tutor-summary-card {
            padding: 18px;
        }

        .tutor-schedule-list {
            padding: 14px;
        }

        .tutor-schedule-header {
            padding: 18px;
        }
    }
</style>

<section class="tutor-welcome">
    <p class="tutor-welcome-label">Dashboard Pengajar</p>

    <h2>Selamat datang, {{ $user->nama }}!</h2>

    <p>
        Selamat datang di Sistem Absensi Digital.
        Pantau jadwal mengajar dan informasi akun tutor kamu
        melalui halaman ini.
    </p>
</section>

<h2 class="tutor-section-title">Ringkasan</h2>

<div class="tutor-summary-grid">
    <section class="tutor-summary-card">
        <div class="tutor-summary-icon">📅</div>

        <div>
            <p class="tutor-summary-label">Total Jadwal Mengajar</p>

            <p class="tutor-summary-number">
                {{ $jadwals->count() }}
            </p>

            <p class="tutor-summary-description">
                Jadwal yang terhubung dengan akun tutor kamu.
            </p>
        </div>
    </section>

    <section class="tutor-summary-card">
        <div class="tutor-summary-icon">👤</div>

        <div>
            <p class="tutor-summary-label">Status Akun Tutor</p>

            @if ($tutor)
                <span class="tutor-status tutor-status-active">
                    Akun Terhubung
                </span>

                <p class="tutor-summary-description">
                    Data tutor berhasil terhubung dengan akun kamu.
                </p>
            @else
                <span class="tutor-status tutor-status-warning">
                    Belum Terhubung
                </span>

                <p class="tutor-summary-description">
                    Silakan hubungi Admin untuk memeriksa data tutor.
                </p>
            @endif
        </div>
    </section>
</div>

<h2 class="tutor-section-title">Jadwal Mengajar Saya</h2>

<section class="tutor-schedule-card">
    <div class="tutor-schedule-header">
        <div>
            <h3>Daftar Jadwal Pembelajaran</h3>
            <p>Informasi mata pelajaran dan waktu mengajar kamu.</p>
        </div>

        <span class="tutor-schedule-count">
            {{ $jadwals->count() }} Jadwal
        </span>
    </div>

    @if ($jadwals->isEmpty())
        <div class="tutor-empty-state">
            <div class="tutor-empty-icon">📚</div>

            <h4>Belum Ada Jadwal Mengajar</h4>

            <p>
                Saat ini belum ada jadwal pembelajaran yang terhubung
                dengan akun kamu. Silakan hubungi Admin untuk informasi
                jadwal berikutnya.
            </p>
        </div>
    @else
        <div class="tutor-schedule-list">
            @foreach ($jadwals as $jadwal)
                <article class="tutor-schedule-item">
                    <span class="tutor-subject-label">
                        Mata Pelajaran
                    </span>

                    <h4>
                        {{ $jadwal->mataPelajaran->nama ?? 'Mata pelajaran belum tersedia' }}
                    </h4>

                    <div class="tutor-schedule-detail">
                        <div class="tutor-detail-icon">📆</div>

                        <div>
                            <p class="tutor-detail-label">Hari</p>
                            <p class="tutor-detail-value">
                                {{ $jadwal->hari ?: '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="tutor-schedule-detail">
                        <div class="tutor-detail-icon">🕒</div>

                        <div>
                            <p class="tutor-detail-label">Waktu Mengajar</p>
                            <p class="tutor-detail-value">
                                {{ $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '-' }}
                                –
                                {{ $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    @if (!empty($jadwal->program_paket))
                        <div class="tutor-schedule-detail">
                            <div class="tutor-detail-icon">🎓</div>

                            <div>
                                <p class="tutor-detail-label">Program Paket</p>
                                <p class="tutor-detail-value">
                                    Paket {{ $jadwal->program_paket }}
                                </p>
                            </div>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</section>

@endsection
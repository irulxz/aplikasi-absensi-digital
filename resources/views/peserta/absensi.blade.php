@extends('layouts.dashboard')

@section('title', 'Absensi Saya')
@section('page-title', 'Absensi Saya')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 absensi-page">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="page-eyebrow mb-2">ABSENSI DIGITAL / PESERTA DIDIK</div>
            <h2 class="page-title mb-2">Absensi Saya</h2>
            <p class="text-muted mb-0">
                Catat kehadiran sesuai jadwal belajar yang sedang berlangsung.
            </p>
        </div>

        <div class="student-card">
            <div class="student-avatar">
                {{ strtoupper(substr($peserta->nama ?? 'P', 0, 1)) }}
            </div>
            <div>
                <div class="small text-muted">Peserta Didik</div>
                <div class="fw-bold text-dark">
                    {{ $peserta->nama ?? 'Peserta Didik' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
             role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm"
             role="alert">
            <strong>Perhatian!</strong> {{ session('error') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-blue">📅</div>
                <div>
                    <div class="small text-muted">Jadwal Hari Ini</div>
                    <div class="summary-number">{{ $jadwals->count() }}</div>
                    <div class="small text-muted">Sesuai program paket Anda</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-green">✅</div>
                <div>
                    <div class="small text-muted">Sudah Absen</div>
                    <div class="summary-number">
                        {{ $jadwals->filter(fn ($jadwal) => $absensiHariIni->has($jadwal->id))->count() }}
                    </div>
                    <div class="small text-muted">Kehadiran sudah tercatat</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-purple">⏰</div>
                <div>
                    <div class="small text-muted">Belum Absen</div>
                    <div class="summary-number">
                        {{ $jadwals->filter(fn ($jadwal) => !$absensiHariIni->has($jadwal->id))->count() }}
                    </div>
                    <div class="small text-muted">Jadwal belum tercatat</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar jadwal absensi --}}
    <div class="card absensi-card border-0 shadow-sm">
        <div class="card-header bg-white border-0 absensi-header">
            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Jadwal Absensi</h5>
                    <p class="text-muted small mb-0">
                        Tombol absensi tersedia sesuai waktu jadwal.
                    </p>
                </div>

                @if ($jadwals->isNotEmpty())
                    <div class="search-box">
                        <span aria-hidden="true">🔎</span>
                        <input type="search" id="searchAbsensi"
                               class="form-control"
                               placeholder="Cari pelajaran, hari, tutor..."
                               autocomplete="off"
                               aria-label="Cari jadwal absensi">
                        <button type="button" id="resetSearchAbsensi"
                                class="btn-reset" aria-label="Hapus pencarian"
                                title="Hapus pencarian">✕</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            @if ($jadwals->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h5 class="fw-bold mt-3">Belum Ada Jadwal Hari Ini</h5>
                    <p class="text-muted mb-0">
                        Tidak ada jadwal belajar untuk program paketmu hari ini.
                    </p>
                </div>
            @else
                <div class="row g-3" id="absensiList">
                    @foreach ($jadwals as $jadwal)
                        @php
                            $catatan = $absensiHariIni->get($jadwal->id);

                            $jamMulai = substr((string) $jadwal->jam_mulai, 0, 8);
                            $jamSelesai = substr((string) $jadwal->jam_selesai, 0, 8);

                            $waktuValid = $sekarang >= $jamMulai
                                && $sekarang <= $jamSelesai;
                        @endphp

                        <div class="col-12 col-lg-6 absensi-item">
                            <div class="schedule-item h-100">
                                <div class="schedule-item-top">
                                    <div class="subject-icon">📘</div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="small text-muted mb-1">
                                            MATA PELAJARAN
                                        </div>
                                        <h5 class="subject-title mb-0">
                                            {{ $jadwal->mataPelajaran->nama ?? 'Mata pelajaran' }}
                                        </h5>
                                    </div>

                                    @if ($catatan)
                                        <span class="status-badge status-done">
                                            <span>✓</span> Tercatat
                                        </span>
                                    @elseif ($waktuValid)
                                        <span class="status-badge status-active">
                                            Berlangsung
                                        </span>
                                    @else
                                        <span class="status-badge status-wait">
                                            Menunggu
                                        </span>
                                    @endif
                                </div>

                                <div class="schedule-details">
                                    <div class="detail-row">
                                        <span class="detail-icon">📆</span>
                                        <span class="detail-label">Hari</span>
                                        <span class="detail-value">
                                            {{ $jadwal->hari ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="detail-row">
                                        <span class="detail-icon">🕒</span>
                                        <span class="detail-label">Waktu</span>
                                        <span class="detail-value">
                                            {{ substr((string) $jadwal->jam_mulai, 0, 5) }}
                                            – {{ substr((string) $jadwal->jam_selesai, 0, 5) }}
                                        </span>
                                    </div>

                                    <div class="detail-row">
                                        <span class="detail-icon">👨‍🏫</span>
                                        <span class="detail-label">Tutor</span>
                                        <span class="detail-value">
                                            {{ $jadwal->tutor->nama ?? 'Belum ditentukan' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="schedule-action">
                                    @if ($catatan)
                                        <div class="attendance-result">
                                            <div class="result-check">✓</div>
                                            <div>
                                                <div class="fw-bold">Absensi sudah tercatat</div>
                                                <div class="small text-muted">
                                                    Status:
                                                    <span class="text-success fw-semibold">
                                                        {{ ucfirst($catatan->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($waktuValid)
                                        <form method="POST"
                                              action="{{ route('peserta.absensi.store', $jadwal->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-absen w-100">
                                                <span>✓</span> Absen Hadir
                                            </button>
                                        </form>
                                        <div class="action-hint">
                                            Pastikan Anda siap mengikuti pembelajaran.
                                        </div>
                                    @else
                                        <button type="button"
                                                class="btn btn-disabled w-100" disabled>
                                            <span>⏰</span> Di Luar Jam Absensi
                                        </button>
                                        <div class="action-hint">
                                            Absensi dapat dilakukan saat jadwal berlangsung.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pesan pencarian kosong --}}
                <div id="emptySearchAbsensi" class="empty-state" hidden>
                    <div class="empty-icon">🔍</div>
                    <h5 class="fw-bold mt-3">Jadwal Tidak Ditemukan</h5>
                    <p class="text-muted mb-3">
                        Coba kata kunci lain atau hapus pencarian.
                    </p>
                    <button type="button" id="resetEmptyAbsensi"
                            class="btn btn-outline-primary btn-sm">
                        Tampilkan Semua Jadwal
                    </button>
                </div>

                <div class="list-footer mt-4 pt-3 border-top">
                    <span class="small text-muted" id="jumlahAbsensi" aria-live="polite">
                        Menampilkan {{ $jadwals->count() }} dari
                        {{ $jadwals->count() }} jadwal
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .absensi-page {
        color: #172b4d;
    }

    .page-eyebrow {
        color: #3267c8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .page-title {
        color: #172b4d;
        font-size: clamp(24px, 3vw, 30px);
        font-weight: 800;
    }

    .student-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 210px;
        padding: 12px 16px;
        background: #fff;
        border: 1px solid #e5edf7;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(25, 55, 100, .04);
    }

    .student-avatar {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: #eaf2ff;
        color: #285ac4;
        font-size: 19px;
        font-weight: 800;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        padding: 21px;
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(24, 55, 100, .04);
    }

    .summary-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 51px;
        height: 51px;
        border-radius: 14px;
        font-size: 22px;
    }

    .icon-blue { background: #e8f1ff; }
    .icon-green { background: #e4f8ed; }
    .icon-purple { background: #f0eaff; }

    .summary-number {
        margin: 2px 0;
        color: #172b4d;
        font-size: 27px;
        font-weight: 800;
        line-height: 1.3;
    }

    .absensi-card {
        overflow: hidden;
        border-radius: 16px;
    }

    .absensi-header {
        padding: 22px 24px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        max-width: 360px;
        padding: 4px 10px 4px 13px;
        background: #f8faff;
        border: 1px solid #dfe7f1;
        border-radius: 11px;
    }

    .search-box:focus-within {
        background: #fff;
        border-color: #6b9df4;
        box-shadow: 0 0 0 3px rgba(50, 103, 200, .10);
    }

    .search-box .form-control {
        min-width: 0;
        padding: 8px 0;
        border: 0;
        background: transparent;
        box-shadow: none;
        font-size: 13px;
    }

    .search-box input::-webkit-search-cancel-button {
        display: none;
    }

    .btn-reset {
        display: none;
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        padding: 0;
        border: 0;
        border-radius: 7px;
        background: #eaf0fa;
        color: #536780;
        cursor: pointer;
    }

    .btn-reset.visible {
        display: inline-block;
    }

    .schedule-item {
        padding: 21px;
        border: 1px solid #e5edf7;
        border-radius: 15px;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease,
                    transform .2s ease;
    }

    .schedule-item:hover {
        border-color: #cbdcf7;
        box-shadow: 0 8px 24px rgba(32, 73, 140, .07);
        transform: translateY(-2px);
    }

    .schedule-item-top {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf1f7;
    }

    .subject-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        background: #edf4ff;
        font-size: 22px;
    }

    .subject-title {
        color: #172b4d;
        font-size: 16px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .status-badge {
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-done {
        color: #167647;
        background: #e5f8ed;
    }

    .status-active {
        color: #245bc0;
        background: #eaf2ff;
    }

    .status-wait {
        color: #8a6508;
        background: #fff5d9;
    }

    .schedule-details {
        display: grid;
        gap: 14px;
        padding: 18px 0;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        font-size: 13px;
    }

    .detail-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #f3f6fb;
    }

    .detail-label {
        width: 62px;
        flex-shrink: 0;
        color: #718096;
    }

    .detail-value {
        margin-left: auto;
        color: #263b58;
        font-weight: 650;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .schedule-action {
        padding-top: 16px;
        border-top: 1px solid #edf1f7;
    }

    .btn-absen {
        padding: 11px 16px;
        border: 0;
        border-radius: 10px;
        background: #2864cc;
        color: #fff;
        font-size: 13px;
        font-weight: 750;
        transition: background .2s ease, transform .2s ease;
    }

    .btn-absen:hover {
        background: #1f50aa;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-absen span {
        margin-right: 6px;
    }

    .btn-disabled {
        padding: 11px 16px;
        border: 1px solid #e1e7ef;
        border-radius: 10px;
        background: #f2f5f9;
        color: #7b8798;
        font-size: 13px;
        font-weight: 700;
        opacity: 1;
    }

    .action-hint {
        margin-top: 9px;
        color: #8491a4;
        font-size: 11px;
        text-align: center;
    }

    .attendance-result {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px;
        border: 1px solid #d7f0e1;
        border-radius: 11px;
        background: #f1fbf5;
        color: #176c43;
        font-size: 13px;
    }

    .result-check {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #d9f5e5;
        font-size: 18px;
        font-weight: 800;
    }

    .empty-state {
        padding: 45px 20px;
        text-align: center;
    }

    .empty-icon {
        display: inline-grid;
        place-items: center;
        width: 76px;
        height: 76px;
        border-radius: 22px;
        background: #edf4ff;
        font-size: 34px;
    }

    .empty-state p {
        max-width: 440px;
        margin: 0 auto;
        font-size: 13px;
        line-height: 1.7;
    }

    #emptySearchAbsensi[hidden] {
        display: none !important;
    }

    .list-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 576px) {
        .student-card {
            min-width: 0;
        }

        .summary-card {
            padding: 17px;
        }

        .absensi-header {
            padding: 19px 16px;
        }

        .search-box {
            max-width: 100%;
        }

        .schedule-item {
            padding: 17px;
        }

        .schedule-item-top {
            flex-wrap: wrap;
        }

        .subject-title {
            font-size: 15px;
        }

        .status-badge {
            margin-left: auto;
        }

        .detail-label {
            width: 48px;
        }

        .list-footer {
            align-items: flex-start;
        }
    }
</style>

@if ($jadwals->isNotEmpty())
<script>
(function () {
    function initAbsensiSearch() {
        const searchInput = document.getElementById('searchAbsensi');
        const list = document.getElementById('absensiList');
        const emptyState = document.getElementById('emptySearchAbsensi');
        const countLabel = document.getElementById('jumlahAbsensi');
        const resetButton = document.getElementById('resetSearchAbsensi');
        const resetEmptyButton = document.getElementById('resetEmptyAbsensi');

        if (!searchInput || !list || !emptyState || !countLabel) return;

        const items = Array.from(list.querySelectorAll('.absensi-item'));
        const total = items.length;

        function filterAbsensi() {
            const keyword = searchInput.value
                .toLocaleLowerCase('id')
                .trim();

            let visible = 0;

            items.forEach(function (item) {
                const text = (item.textContent || '').toLocaleLowerCase('id');
                const cocok = text.includes(keyword);

                item.hidden = !cocok;

                if (cocok) visible++;
            });

            emptyState.hidden = !(keyword !== '' && visible === 0);

            if (resetButton) {
                resetButton.classList.toggle('visible', keyword !== '');
            }

            countLabel.textContent = keyword === ''
                ? 'Menampilkan ' + total + ' dari ' + total + ' jadwal'
                : 'Menampilkan ' + visible + ' dari ' + total + ' jadwal';
        }

        function resetSearch() {
            searchInput.value = '';
            filterAbsensi();
            searchInput.focus();
        }

        searchInput.addEventListener('input', filterAbsensi);

        if (resetButton) {
            resetButton.addEventListener('click', resetSearch);
        }

        if (resetEmptyButton) {
            resetEmptyButton.addEventListener('click', resetSearch);
        }

        filterAbsensi();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAbsensiSearch);
    } else {
        initAbsensiSearch();
    }
})();
</script>
@endif
@endsection
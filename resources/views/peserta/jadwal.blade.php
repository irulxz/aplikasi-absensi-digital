@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 jadwal-page">

    {{-- Header --}}
    <div class="page-header d-flex flex-column flex-md-row
                justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="page-eyebrow mb-2">ABSENSI DIGITAL / PESERTA DIDIK</div>
            <h2 class="page-title mb-2">Jadwal Belajar</h2>
            <p class="text-muted mb-0">
                Lihat jadwal pembelajaran, waktu belajar, dan informasi tutor Anda.
            </p>
        </div>

        <div class="program-card">
            <div class="program-icon">📚</div>
            <div>
                <div class="small text-muted">Program Paket</div>
                <div class="fw-bold text-dark">
                    {{ $peserta->program_paket ?? '-' }}
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

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-blue">📅</div>
                <div>
                    <div class="text-muted small">Total Jadwal</div>
                    <div class="summary-number">{{ $jadwals->count() }}</div>
                    <div class="small text-muted">Jadwal pembelajaran Anda</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-green">📘</div>
                <div>
                    <div class="text-muted small">Mata Pelajaran</div>
                    <div class="summary-number">
                        {{ $jadwals->pluck('mata_pelajaran_id')->filter()->unique()->count() }}
                    </div>
                    <div class="small text-muted">Mata pelajaran terjadwal</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="summary-card">
                <div class="summary-icon icon-purple">👨‍🏫</div>
                <div>
                    <div class="text-muted small">Tutor</div>
                    <div class="summary-number">
                        {{ $jadwals->pluck('tutor_id')->filter()->unique()->count() }}
                    </div>
                    <div class="small text-muted">Tutor pada jadwal Anda</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Jadwal --}}
    <div class="card schedule-card border-0 shadow-sm">
        <div class="card-header schedule-header bg-white border-0">
            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Jadwal</h5>
                    <p class="text-muted small mb-0">
                        Jadwal pembelajaran sesuai program paket Anda.
                    </p>
                </div>

                @if ($jadwals->isNotEmpty())
                    <div class="search-box">
                        <span aria-hidden="true">🔎</span>
                        <input
                            type="search"
                            id="searchJadwal"
                            class="form-control"
                            placeholder="Cari hari, pelajaran, tutor..."
                            autocomplete="off"
                            aria-label="Cari jadwal belajar"
                        >
                        <button type="button" id="resetSearch"
                                class="btn-reset" aria-label="Hapus pencarian"
                                title="Hapus pencarian">
                            ✕
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            @if ($jadwals->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📅</div>
                    <h5 class="fw-bold mt-3">Belum Ada Jadwal</h5>
                    <p class="text-muted mb-0">
                        Belum ada jadwal belajar untuk program paket Anda.
                        Silakan hubungi admin jika jadwal seharusnya sudah tersedia.
                    </p>
                </div>
            @else
                <div class="table-responsive" id="tableWrapper">
                    <table class="table schedule-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No.</th>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Waktu</th>
                                <th>Tutor</th>
                            </tr>
                        </thead>

                        <tbody id="jadwalTableBody">
                            @foreach ($jadwals as $jadwal)
                                <tr class="jadwal-row">
                                    <td class="ps-4 text-muted nomor-jadwal">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <span class="day-badge">
                                            {{ $jadwal->hari ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ $jadwal->mataPelajaran->nama ?? 'Belum ditentukan' }}
                                        </div>
                                        <div class="small text-muted">
                                            Pembelajaran
                                        </div>
                                    </td>

                                    <td>
                                        <div class="time-info">
                                            <span aria-hidden="true">🕒</span>
                                            <span>
                                                {{ substr((string) $jadwal->jam_mulai, 0, 5) }}
                                                –
                                                {{ substr((string) $jadwal->jam_selesai, 0, 5) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="tutor-info">
                                            <div class="tutor-avatar">
                                                {{ strtoupper(substr($jadwal->tutor->nama ?? 'T', 0, 1)) }}
                                            </div>
                                            <span class="fw-medium">
                                                {{ $jadwal->tutor->nama ?? 'Belum ditentukan' }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Muncul hanya jika pencarian tidak menemukan hasil --}}
                <div id="emptySearch" class="empty-search" hidden>
                    <div class="empty-search-icon">🔍</div>
                    <h6 class="fw-bold mt-3 mb-1">Jadwal tidak ditemukan</h6>
                    <p class="text-muted small mb-3">
                        Coba kata kunci lain atau hapus pencarian.
                    </p>
                    <button type="button" id="resetEmptySearch"
                            class="btn btn-outline-primary btn-sm">
                        Tampilkan semua jadwal
                    </button>
                </div>

                {{-- Footer --}}
                <div class="schedule-footer d-flex flex-column flex-sm-row
                            justify-content-between align-items-sm-center gap-2">
                    <span class="small text-muted" id="jumlahJadwal"
                          aria-live="polite">
                        Menampilkan {{ $jadwals->count() }} dari
                        {{ $jadwals->count() }} jadwal
                    </span>
                    <span class="small text-muted">
                        Total data: {{ $jadwals->count() }} jadwal
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .jadwal-page {
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

    .program-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
        padding: 13px 18px;
        background: #fff;
        border: 1px solid #e5edf7;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(25, 55, 100, .04);
    }

    .program-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eaf2ff;
        font-size: 21px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        padding: 22px;
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(24, 55, 100, .04);
    }

    .summary-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        font-size: 23px;
    }

    .icon-blue { background: #e8f1ff; }
    .icon-green { background: #e4f8ed; }
    .icon-purple { background: #f0eaff; }

    .summary-number {
        margin: 2px 0;
        color: #172b4d;
        font-size: 27px;
        line-height: 1.3;
        font-weight: 800;
    }

    .schedule-card {
        overflow: hidden;
        border-radius: 16px;
    }

    .schedule-header {
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
        outline: 0;
        background: transparent;
        box-shadow: none;
        font-size: 13px;
    }

    .search-box .form-control::-webkit-search-cancel-button {
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

    .schedule-table {
        min-width: 650px;
    }

    .schedule-table thead th {
        padding: 15px 14px;
        background: #f4f7fc;
        color: #63738a;
        border-top: 1px solid #e8eef6;
        border-bottom: 1px solid #e8eef6;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .45px;
        white-space: nowrap;
    }

    .schedule-table tbody td {
        padding: 17px 14px;
        border-bottom: 1px solid #edf1f7;
        font-size: 13px;
    }

    .schedule-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .schedule-table tbody tr:hover {
        background: #f8fbff;
    }

    .schedule-table tbody tr[hidden] {
        display: none !important;
    }

    .day-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 8px;
        background: #eaf2ff;
        color: #245bc0;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .time-info,
    .tutor-info {
        display: flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
    }

    .time-info {
        color: #40546e;
        font-size: 12px;
    }

    .tutor-avatar {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e9efff;
        color: #285ac4;
        font-weight: 800;
    }

    .empty-state,
    .empty-search {
        padding: 46px 20px;
        text-align: center;
    }

    .empty-state p {
        max-width: 420px;
        margin: 0 auto;
        font-size: 13px;
        line-height: 1.7;
    }

    .empty-icon,
    .empty-search-icon {
        display: inline-grid;
        place-items: center;
        width: 76px;
        height: 76px;
        border-radius: 22px;
        background: #edf4ff;
        font-size: 34px;
    }

    /* Atribut hidden tetap menyembunyikan panel meskipun ada CSS lain. */
    #emptySearch[hidden] {
        display: none !important;
    }

    .schedule-footer {
        padding: 15px 24px;
        border-top: 1px solid #edf1f7;
        background: #fff;
    }

    @media (max-width: 576px) {
        .schedule-header {
            padding: 19px 16px;
        }

        .summary-card {
            padding: 17px;
        }

        .schedule-table tbody td {
            padding: 14px 12px;
        }

        .schedule-table thead th {
            padding: 13px 12px;
        }

        .search-box {
            max-width: 100%;
        }

        .schedule-footer {
            padding: 14px 16px;
        }
    }
</style>

@if ($jadwals->isNotEmpty())
<script>
(function () {
    function initJadwalSearch() {
        const searchInput = document.getElementById('searchJadwal');
        const tableBody = document.getElementById('jadwalTableBody');
        const emptySearch = document.getElementById('emptySearch');
        const jumlahJadwal = document.getElementById('jumlahJadwal');
        const resetButton = document.getElementById('resetSearch');
        const resetEmptyButton = document.getElementById('resetEmptySearch');

        if (!searchInput || !tableBody || !emptySearch || !jumlahJadwal) {
            return;
        }

        const rows = Array.from(tableBody.querySelectorAll('.jadwal-row'));
        const total = rows.length;

        function filterJadwal() {
            const keyword = searchInput.value
                .toLocaleLowerCase('id')
                .trim();

            let visible = 0;

            rows.forEach(function (row, index) {
                const text = (row.textContent || '').toLocaleLowerCase('id');
                const cocok = text.includes(keyword);

                row.hidden = !cocok;

                if (cocok) {
                    visible++;
                    const nomor = row.querySelector('.nomor-jadwal');
                    if (nomor) nomor.textContent = visible;
                }
            });

            // hidden adalah atribut HTML, tidak bergantung pada class Bootstrap.
            emptySearch.hidden = !(keyword !== '' && visible === 0);

            if (resetButton) {
                resetButton.classList.toggle('visible', keyword !== '');
            }

            jumlahJadwal.textContent = keyword === ''
                ? 'Menampilkan ' + total + ' dari ' + total + ' jadwal'
                : 'Menampilkan ' + visible + ' dari ' + total + ' jadwal';
        }

        function resetPencarian() {
            searchInput.value = '';
            filterJadwal();
            searchInput.focus();
        }

        searchInput.addEventListener('input', filterJadwal);

        if (resetButton) {
            resetButton.addEventListener('click', resetPencarian);
        }

        if (resetEmptyButton) {
            resetEmptyButton.addEventListener('click', resetPencarian);
        }

        // Pastikan kondisi awal bersih saat halaman dibuka.
        filterJadwal();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initJadwalSearch);
    } else {
        initJadwalSearch();
    }
})();
</script>
@endif
@endsection
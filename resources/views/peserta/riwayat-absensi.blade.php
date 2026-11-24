@extends('layouts.dashboard')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 riwayat-page">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="page-eyebrow mb-2">ABSENSI DIGITAL / PESERTA DIDIK</div>
            <h2 class="page-title mb-2">Riwayat Absensi</h2>
            <p class="text-muted mb-0">
                Pantau catatan kehadiran dan riwayat pembelajaran Anda.
            </p>
        </div>

        <div class="history-info">
            <div class="history-icon">📋</div>
            <div>
                <div class="small text-muted">Total Catatan</div>
                <div class="fw-bold text-dark">
                    {{ $absensis->total() }} absensi
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    @php
        $totalHadir = $absensis->getCollection()
            ->where('status', 'Hadir')->count();

        $totalIzin = $absensis->getCollection()
            ->where('status', 'Izin')->count();

        $totalSakit = $absensis->getCollection()
            ->where('status', 'Sakit')->count();

        $totalAlpa = $absensis->getCollection()
            ->where('status', 'Alpa')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-green">✓</div>
                <div>
                    <div class="small text-muted">Hadir</div>
                    <div class="summary-number">{{ $totalHadir }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-yellow">📝</div>
                <div>
                    <div class="small text-muted">Izin</div>
                    <div class="summary-number">{{ $totalIzin }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-blue">＋</div>
                <div>
                    <div class="small text-muted">Sakit</div>
                    <div class="summary-number">{{ $totalSakit }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-red">!</div>
                <div>
                    <div class="small text-muted">Alpa</div>
                    <div class="summary-number">{{ $totalAlpa }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card history-card border-0 shadow-sm">
        <div class="card-header bg-white border-0 history-header">
            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Riwayat</h5>
                    <p class="text-muted small mb-0">
                        Informasi tanggal, pelajaran, tutor, dan status kehadiran.
                    </p>
                </div>

                @if ($absensis->isNotEmpty())
                    <div class="search-box">
                        <span aria-hidden="true">🔎</span>
                        <input type="search" id="searchRiwayat"
                               class="form-control"
                               placeholder="Cari pelajaran, tutor, status..."
                               autocomplete="off"
                               aria-label="Cari riwayat absensi">
                        <button type="button" id="resetRiwayat"
                                class="btn-reset"
                                aria-label="Hapus pencarian"
                                title="Hapus pencarian">✕</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            @if ($absensis->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h5 class="fw-bold mt-3">Belum Ada Riwayat</h5>
                    <p class="text-muted mb-0">
                        Catatan absensi Anda akan muncul di sini setelah tersedia.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table history-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No.</th>
                                <th>Tanggal</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Tutor</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody id="riwayatTableBody">
                            @foreach ($absensis as $absensi)
                                @php
                                    $status = strtolower(trim((string) $absensi->status));

                                    $statusClass = match ($status) {
                                        'hadir' => 'status-hadir',
                                        'izin' => 'status-izin',
                                        'sakit' => 'status-sakit',
                                        'alpa' => 'status-alpa',
                                        default => 'status-lainnya',
                                    };
                                @endphp

                                <tr class="riwayat-row">
                                    <td class="ps-4 text-muted nomor-riwayat">
                                        {{ $absensis->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div class="date-value">
                                            {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="subject-cell">
                                            <div class="subject-icon">📘</div>
                                            <span class="fw-semibold text-dark">
                                                {{ $absensi->jadwal->mataPelajaran->nama ?? 'Tidak tersedia' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="day-badge">
                                            {{ $absensi->jadwal->hari ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="tutor-cell">
                                            <div class="tutor-avatar">
                                                {{ strtoupper(substr($absensi->jadwal->tutor->nama ?? 'T', 0, 1)) }}
                                            </div>
                                            <span>
                                                {{ $absensi->jadwal->tutor->nama ?? 'Tidak tersedia' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            <span class="status-dot"></span>
                                            {{ $absensi->status ?: 'Tidak diketahui' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Hasil pencarian kosong --}}
                <div id="emptyRiwayat" class="empty-state" hidden>
                    <div class="empty-icon">🔍</div>
                    <h5 class="fw-bold mt-3">Riwayat Tidak Ditemukan</h5>
                    <p class="text-muted mb-3">
                        Coba kata kunci lain atau hapus pencarian.
                    </p>
                    <button type="button" id="resetEmptyRiwayat"
                            class="btn btn-outline-primary btn-sm">
                        Tampilkan Semua Riwayat
                    </button>
                </div>

                {{-- Footer dan pagination --}}
                <div class="history-footer">
                    <span class="small text-muted" id="jumlahRiwayat"
                          aria-live="polite">
                        Menampilkan {{ $absensis->count() }} data pada halaman ini
                    </span>

                    <div id="paginationRiwayat"
                         class="pagination-wrap">
                        {{ $absensis->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <p class="small text-muted mt-3 mb-0">
        Catatan: pencarian hanya memfilter data pada halaman pagination yang sedang dibuka.
    </p>
</div>

<style>
    .riwayat-page {
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

    .history-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
        padding: 13px 17px;
        background: #fff;
        border: 1px solid #e5edf7;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(25, 55, 100, .04);
    }

    .history-icon {
        display: grid;
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
        gap: 13px;
        height: 100%;
        padding: 19px;
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 15px;
        box-shadow: 0 5px 18px rgba(24, 55, 100, .04);
    }

    .summary-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        font-size: 21px;
        font-weight: 800;
    }

    .icon-green { background: #e4f8ed; color: #16834b; }
    .icon-yellow { background: #fff4d8; color: #946b00; }
    .icon-blue { background: #e8f1ff; color: #285ac4; }
    .icon-red { background: #ffe9e9; color: #c93636; }

    .summary-number {
        margin-top: 2px;
        color: #172b4d;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.3;
    }

    .history-card {
        overflow: hidden;
        border-radius: 16px;
    }

    .history-header {
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

    .history-table {
        min-width: 850px;
    }

    .history-table thead th {
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

    .history-table tbody td {
        padding: 16px 14px;
        border-bottom: 1px solid #edf1f7;
        font-size: 13px;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .history-table tbody tr:hover {
        background: #f8fbff;
    }

    .history-table tbody tr[hidden] {
        display: none !important;
    }

    .date-value {
        color: #40546e;
        font-weight: 650;
        white-space: nowrap;
    }

    .subject-cell,
    .tutor-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 150px;
    }

    .subject-icon {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: #edf4ff;
        font-size: 17px;
    }

    .tutor-avatar {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 33px;
        height: 33px;
        border-radius: 50%;
        background: #e9efff;
        color: #285ac4;
        font-weight: 800;
    }

    .day-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        background: #eaf2ff;
        color: #245bc0;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-hadir { background: #e4f8ed; color: #167647; }
    .status-izin { background: #fff4d8; color: #8a6508; }
    .status-sakit { background: #e4f4ff; color: #1769aa; }
    .status-alpa { background: #ffe9e9; color: #bd3030; }
    .status-lainnya { background: #edf0f5; color: #5d6879; }

    .history-footer {
        padding: 18px 24px;
        border-top: 1px solid #edf1f7;
        background: #fff;
    }

    .pagination-wrap {
        margin-top: 14px;
    }

    .pagination-wrap nav p {
        margin-bottom: 10px;
        color: #718096;
        font-size: 12px;
    }

    .pagination-wrap .pagination {
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 0;
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

    #emptyRiwayat[hidden] {
        display: none !important;
    }

    @media (max-width: 576px) {
        .history-header {
            padding: 19px 16px;
        }

        .summary-card {
            gap: 10px;
            padding: 14px;
        }

        .summary-icon {
            width: 39px;
            height: 39px;
            font-size: 18px;
        }

        .summary-number {
            font-size: 23px;
        }

        .search-box {
            max-width: 100%;
        }

        .history-footer {
            padding: 16px;
        }
    }
</style>

@if ($absensis->isNotEmpty())
<script>
(function () {
    function initRiwayatSearch() {
        const input = document.getElementById('searchRiwayat');
        const tbody = document.getElementById('riwayatTableBody');
        const emptyState = document.getElementById('emptyRiwayat');
        const countLabel = document.getElementById('jumlahRiwayat');
        const resetButton = document.getElementById('resetRiwayat');
        const resetEmptyButton = document.getElementById('resetEmptyRiwayat');
        const pagination = document.getElementById('paginationRiwayat');

        if (!input || !tbody || !emptyState || !countLabel) return;

        const rows = Array.from(tbody.querySelectorAll('.riwayat-row'));
        const total = rows.length;

        function filterRiwayat() {
            const keyword = input.value.toLocaleLowerCase('id').trim();
            let visible = 0;

            rows.forEach(function (row) {
                const cocok = (row.textContent || '')
                    .toLocaleLowerCase('id')
                    .includes(keyword);

                row.hidden = !cocok;

                if (cocok) {
                    visible++;
                    const nomor = row.querySelector('.nomor-riwayat');
                    if (nomor) nomor.textContent = visible;
                }
            });

            emptyState.hidden = !(keyword !== '' && visible === 0);

            if (resetButton) {
                resetButton.classList.toggle('visible', keyword !== '');
            }

            // Sembunyikan pagination selama pencarian lokal aktif agar
            // pengguna tidak mengira hasil pencarian mencakup semua halaman.
            if (pagination) {
                pagination.hidden = keyword !== '';
            }

            countLabel.textContent = keyword === ''
                ? 'Menampilkan ' + total + ' data pada halaman ini'
                : 'Menampilkan ' + visible + ' dari ' + total + ' data pada halaman ini';
        }

        function resetSearch() {
            input.value = '';
            filterRiwayat();
            input.focus();
        }

        input.addEventListener('input', filterRiwayat);

        if (resetButton) {
            resetButton.addEventListener('click', resetSearch);
        }

        if (resetEmptyButton) {
            resetEmptyButton.addEventListener('click', resetSearch);
        }

        filterRiwayat();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRiwayatSearch);
    } else {
        initRiwayatSearch();
    }
})();
</script>
@endif
@endsection
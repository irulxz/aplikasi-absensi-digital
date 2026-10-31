@extends('layouts.dashboard')

@section('title', 'Rekap Absensi')

@section('content')
<style>
    .rekap-page {
        max-width: 1500px;
        margin: 0 auto;
        color: #17243b;
    }

    .rekap-page * {
        box-sizing: border-box;
    }

    .rekap-page .page-heading {
        margin-bottom: 26px;
    }

    .rekap-page .eyebrow {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .rekap-page .page-heading h1 {
        margin: 0 0 8px;
        font-size: 28px;
        line-height: 1.3;
        font-weight: 750;
    }

    .rekap-page .page-heading p {
        color: #64748b;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }

    .rekap-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .rekap-page .summary-card {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(23, 36, 59, .04);
        position: relative;
        overflow: hidden;
    }

    .rekap-page .summary-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: #3978ed;
    }

    .rekap-page .summary-card.hadir::before {
        background: #16a36a;
    }

    .rekap-page .summary-card.terlambat::before {
        background: #e5a11a;
    }

    .rekap-page .summary-card.tidak-hadir::before {
        background: #e04d5b;
    }

    .rekap-page .summary-label {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 13px;
    }

    .rekap-page .summary-value {
        font-size: 30px;
        line-height: 1.2;
        font-weight: 750;
    }

    .rekap-page .summary-card.hadir .summary-value {
        color: #128454;
    }

    .rekap-page .summary-card.terlambat .summary-value {
        color: #b77908;
    }

    .rekap-page .summary-card.tidak-hadir .summary-value {
        color: #d53c4b;
    }

    .rekap-page .panel {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(23, 36, 59, .04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .rekap-page .panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
    }

    .rekap-page .panel-header h2,
    .rekap-page .table-heading h2 {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 700;
    }

    .rekap-page .panel-header p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    .rekap-page .filter-form {
        padding: 22px 24px;
        display: grid;
        grid-template-columns: minmax(180px, 1.5fr)
            repeat(2, minmax(145px, 1fr)) auto;
        gap: 15px;
        align-items: end;
    }

    .rekap-page .field label {
        display: block;
        font-size: 13px;
        font-weight: 650;
        color: #334155;
        margin-bottom: 8px;
    }

    .rekap-page .field input {
        display: block;
        width: 100%;
        min-width: 0;
        height: 43px;
        padding: 0 12px;
        border: 1px solid #d9e1ec;
        border-radius: 8px;
        background: #fff;
        color: #17243b;
        font: inherit;
        font-size: 13px;
        outline: none;
    }

    .rekap-page .field input:focus {
        border-color: #3978ed;
        box-shadow: 0 0 0 3px rgba(57, 120, 237, .12);
    }

    .rekap-page .filter-actions {
        display: flex;
        gap: 8px;
    }

    .rekap-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-height: 43px;
        padding: 0 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        font: inherit;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .rekap-page .btn-primary {
        background: #3978ed;
        color: #fff;
    }

    .rekap-page .btn-primary:hover {
        background: #2864d3;
    }

    .rekap-page .btn-light {
        background: #fff;
        color: #475569;
        border-color: #d9e1ec;
    }

    .rekap-page .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 21px 24px;
    }

    .rekap-page .table-heading h2 {
        margin-bottom: 0;
    }

    .rekap-page .table-heading-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .rekap-page .table-description {
        padding: 0 24px 16px;
        color: #64748b;
        font-size: 13px;
    }

    .rekap-page .data-count {
        background: #eef4ff;
        color: #2c65c8;
        border-radius: 20px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .rekap-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border-top: 1px solid #edf0f5;
    }

    .rekap-page table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }

    .rekap-page thead {
        background: #f7f9fc;
    }

    .rekap-page th {
        padding: 15px 16px;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 750;
        white-space: nowrap;
    }

    .rekap-page td {
        padding: 16px;
        border-top: 1px solid #edf0f5;
        color: #334155;
        vertical-align: middle;
    }

    .rekap-page tbody tr:hover {
        background: #f8faff;
    }

    .rekap-page .student-name {
        color: #17243b;
        font-weight: 650;
        min-width: 150px;
    }

    .rekap-page .number-cell {
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .rekap-page .percentage {
        min-width: 125px;
    }

    .rekap-page .percentage-value {
        display: block;
        font-weight: 750;
        margin-bottom: 7px;
        color: #17243b;
    }

    .rekap-page .progress-track {
        width: 100%;
        height: 6px;
        background: #e9eef5;
        border-radius: 20px;
        overflow: hidden;
    }

    .rekap-page .progress-bar {
        height: 100%;
        background: #16a36a;
        border-radius: 20px;
    }

    .rekap-page .empty-state {
        padding: 48px 20px;
        text-align: center;
        color: #64748b;
    }

    .rekap-page .empty-state strong {
        display: block;
        color: #334155;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .rekap-page .pagination-wrap {
        padding: 18px 24px;
        border-top: 1px solid #edf0f5;
        overflow-x: auto;
    }

    .rekap-page .status-note {
        padding: 15px 24px;
        background: #f8faff;
        border-top: 1px solid #edf0f5;
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    @media (max-width: 1200px) {
        .rekap-page .filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .rekap-page .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .rekap-page .page-heading h1 {
            font-size: 23px;
        }

        .rekap-page .summary-grid {
            gap: 10px;
        }

        .rekap-page .summary-card {
            padding: 17px;
        }

        .rekap-page .summary-value {
            font-size: 25px;
        }

        .rekap-page .filter-form {
            grid-template-columns: 1fr;
            padding: 18px;
        }

        .rekap-page .filter-actions .btn {
            flex: 1;
        }

        .rekap-page .panel-header,
        .rekap-page .table-heading {
            padding: 18px;
        }

        .rekap-page .table-heading {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .rekap-page .table-heading-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>

<div class="rekap-page">
    <div class="page-heading">
        <div class="eyebrow">Admin / Laporan Kehadiran</div>
        <h1>Rekap Absensi</h1>
        <p>
            Ringkasan kehadiran setiap peserta didik berdasarkan periode
            yang dipilih.
        </p>
    </div>

    {{-- Ringkasan seluruh catatan sesuai periode dan pencarian --}}
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Catatan Absensi</div>
            <div class="summary-value">{{ $totalAbsensi }}</div>
        </div>

        <div class="summary-card hadir">
            <div class="summary-label">Hadir</div>
            <div class="summary-value">{{ $totalHadir }}</div>
        </div>

        <div class="summary-card terlambat">
            <div class="summary-label">Terlambat</div>
            <div class="summary-value">{{ $totalTerlambat }}</div>
        </div>

        <div class="summary-card tidak-hadir">
            <div class="summary-label">Tidak Hadir</div>
            <div class="summary-value">{{ $totalTidakHadir }}</div>
        </div>
    </div>

    {{-- Filter laporan --}}
    <section class="panel">
        <div class="panel-header">
            <h2>Filter Laporan</h2>
            <p>
                Pilih rentang tanggal atau cari peserta didik tertentu.
            </p>
        </div>

        <form class="filter-form" method="GET"
              action="{{ route('admin.rekap.index') }}">

            <div class="field">
                <label for="nama">Nama atau NIS Peserta Didik</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ request('nama') }}"
                    placeholder="Masukkan nama atau NIS"
                >
            </div>

            <div class="field">
                <label for="tanggal_mulai">Dari Tanggal</label>
                <input
                    type="date"
                    id="tanggal_mulai"
                    name="tanggal_mulai"
                    value="{{ request('tanggal_mulai') }}"
                >
            </div>

            <div class="field">
                <label for="tanggal_selesai">Sampai Tanggal</label>
                <input
                    type="date"
                    id="tanggal_selesai"
                    name="tanggal_selesai"
                    value="{{ request('tanggal_selesai') }}"
                    min="{{ request('tanggal_mulai') }}"
                >
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    Terapkan
                </button>

                <a href="{{ route('admin.rekap.index') }}"
                   class="btn btn-light">
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- Rekap per peserta didik --}}
    <section class="panel">
        <div class="table-heading">
            <h2>Rekap per Peserta Didik</h2>

            <div class="table-heading-actions">
                <span class="data-count">
                    {{ $rekap->total() }} peserta
                </span>

                <a href="{{ route('admin.rekap.export', request()->query()) }}"
                   class="btn btn-primary">
                    Ekspor ke Excel
                </a>
            </div>
        </div>

        <div class="table-description">
            Setiap peserta didik ditampilkan satu kali dengan jumlah
            kehadiran yang dihitung dari catatan dalam periode terpilih.
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Peserta Didik</th>
                        <th>NIS</th>
                        <th>Hadir</th>
                        <th>Terlambat</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Total</th>
                        <th>Persentase Hadir</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rekap as $baris)
                        @php
                            $total = (int) $baris->total_absensi;
                            $hadir = (int) $baris->total_hadir;

                            $persentase = $total > 0
                                ? round(($hadir / $total) * 100, 1)
                                : 0;
                        @endphp

                        <tr>
                            <td>
                                {{ $rekap->firstItem() + $loop->index }}
                            </td>

                            <td class="student-name">
                                {{ $baris->nama }}
                            </td>

                            <td>{{ $baris->nis ?? '-' }}</td>

                            <td class="number-cell">{{ $hadir }}</td>

                            <td class="number-cell">
                                {{ $baris->total_terlambat }}
                            </td>

                            <td class="number-cell">
                                {{ $baris->total_izin }}
                            </td>

                            <td class="number-cell">
                                {{ $baris->total_sakit }}
                            </td>

                            <td class="number-cell">
                                {{ $baris->total_alpa }}
                            </td>

                            <td class="number-cell">
                                <strong>{{ $total }}</strong>
                            </td>

                            <td class="percentage">
                                <span class="percentage-value">
                                    {{ number_format($persentase, 1, ',', '.') }}%
                                </span>

                                <div class="progress-track">
                                    <div
                                        class="progress-bar"
                                        style="width: {{ min(100, max(0, $persentase)) }}%"
                                    ></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <strong>Belum ada data rekap</strong>
                                    Coba ubah nama peserta didik atau
                                    rentang tanggal laporan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="status-note">
            Persentase hadir = jumlah status Hadir dibagi total catatan
            absensi peserta didik, lalu dikali 100%. Status Terlambat
            dihitung terpisah dari Hadir.
        </div>

        @if ($rekap->hasPages())
            <div class="pagination-wrap">
                {{ $rekap->links() }}
            </div>
        @endif
    </section>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Manajemen Absensi')

@section('content')
<style>
    .absensi-page {
        color: #17243b;
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        font-family: inherit;
    }

    .absensi-page * {
        box-sizing: border-box;
    }

    .absensi-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 26px;
    }

    .absensi-page .eyebrow {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .absensi-page .page-heading h1 {
        font-size: 28px;
        line-height: 1.3;
        font-weight: 750;
        margin: 0 0 8px;
        color: #17243b;
    }

    .absensi-page .page-heading p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .absensi-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .absensi-page .summary-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 14px rgba(23, 36, 59, .04);
    }

    .absensi-page .summary-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: #3978ed;
    }

    .absensi-page .summary-card.hadir::before {
        background: #16a36a;
    }

    .absensi-page .summary-card.terlambat::before {
        background: #e5a11a;
    }

    .absensi-page .summary-card.tidak-hadir::before {
        background: #e04d5b;
    }

    .absensi-page .summary-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 13px;
    }

    .absensi-page .summary-value {
        font-size: 30px;
        line-height: 1.2;
        font-weight: 750;
        color: #17243b;
    }

    .absensi-page .summary-card.hadir .summary-value {
        color: #128454;
    }

    .absensi-page .summary-card.terlambat .summary-value {
        color: #b77908;
    }

    .absensi-page .summary-card.tidak-hadir .summary-value {
        color: #d53c4b;
    }

    .absensi-page .panel {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(23, 36, 59, .04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .absensi-page .panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
    }

    .absensi-page .panel-header h2 {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 700;
        color: #17243b;
    }

    .absensi-page .panel-header p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    .absensi-page .filter-form {
        padding: 22px 24px;
        display: grid;
        grid-template-columns: minmax(200px, 2fr) minmax(155px, 1fr)
            minmax(150px, 1fr) auto;
        gap: 16px;
        align-items: end;
    }

    .absensi-page .field label {
        display: block;
        font-size: 13px;
        font-weight: 650;
        color: #334155;
        margin-bottom: 8px;
    }

    .absensi-page .field input,
    .absensi-page .field select {
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

    .absensi-page .field input:focus,
    .absensi-page .field select:focus {
        border-color: #3978ed;
        box-shadow: 0 0 0 3px rgba(57, 120, 237, .12);
    }

    .absensi-page .filter-actions {
        display: flex;
        gap: 8px;
    }

    .absensi-page .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-height: 43px;
        padding: 0 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font: inherit;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: .15s ease;
    }

    .absensi-page .btn-primary {
        background: #3978ed;
        color: #fff;
    }

    .absensi-page .btn-primary:hover {
        background: #2864d3;
    }

    .absensi-page .btn-light {
        background: #fff;
        color: #475569;
        border-color: #d9e1ec;
    }

    .absensi-page .btn-light:hover {
        background: #f5f7fb;
    }

    .absensi-page .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 21px 24px;
    }

    .absensi-page .table-header h2 {
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .absensi-page .data-count {
        display: inline-flex;
        align-items: center;
        background: #eef4ff;
        color: #2c65c8;
        border-radius: 20px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .absensi-page .table-wrap {
        width: 100%;
        overflow-x: auto;
        border-top: 1px solid #edf0f5;
    }

    .absensi-page table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }

    .absensi-page thead {
        background: #f7f9fc;
    }

    .absensi-page th {
        padding: 15px 18px;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .45px;
        font-weight: 750;
        white-space: nowrap;
    }

    .absensi-page td {
        padding: 17px 18px;
        border-top: 1px solid #edf0f5;
        color: #334155;
        vertical-align: middle;
    }

    .absensi-page tbody tr:hover {
        background: #f8faff;
    }

    .absensi-page .number-cell {
        color: #64748b;
        width: 55px;
    }

    .absensi-page .student-name {
        font-weight: 650;
        color: #17243b;
        min-width: 150px;
    }

    .absensi-page .nis-cell {
        font-variant-numeric: tabular-nums;
    }

    .absensi-page .date-cell {
        white-space: nowrap;
    }

    .absensi-page .status-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .absensi-page .status-hadir {
        color: #087849;
        background: #dcf8e9;
    }

    .absensi-page .status-terlambat {
        color: #986208;
        background: #fff1c9;
    }

    .absensi-page .status-izin {
        color: #1760b5;
        background: #e1efff;
    }

    .absensi-page .status-sakit {
        color: #6941c6;
        background: #eee7ff;
    }

    .absensi-page .status-alpa {
        color: #bf3442;
        background: #ffe4e7;
    }

    .absensi-page .status-default {
        color: #475569;
        background: #edf0f5;
    }

    .absensi-page .empty-state {
        padding: 48px 20px;
        text-align: center;
        color: #64748b;
    }

    .absensi-page .empty-state strong {
        display: block;
        color: #334155;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .absensi-page .pagination-wrap {
        padding: 18px 24px;
        border-top: 1px solid #edf0f5;
        overflow-x: auto;
    }

    .absensi-page .pagination-wrap nav p {
        color: #64748b;
        font-size: 13px;
    }

    .absensi-page .pagination-wrap nav a,
    .absensi-page .pagination-wrap nav span {
        text-decoration: none;
    }

    @media (max-width: 1100px) {
        .absensi-page .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .absensi-page .filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .absensi-page .filter-actions {
            align-self: end;
        }
    }

    @media (max-width: 600px) {
        .absensi-page .page-heading h1 {
            font-size: 23px;
        }

        .absensi-page .summary-grid {
            gap: 10px;
        }

        .absensi-page .summary-card {
            padding: 17px;
        }

        .absensi-page .summary-value {
            font-size: 25px;
        }

        .absensi-page .filter-form {
            grid-template-columns: 1fr;
            padding: 18px;
        }

        .absensi-page .panel-header,
        .absensi-page .table-header {
            padding: 18px;
        }

        .absensi-page .filter-actions .btn {
            flex: 1;
        }
    }
</style>

<div class="absensi-page">

    <div class="page-heading">
        <div>
            <div class="eyebrow">Admin / Manajemen Absensi</div>
            <h1>Manajemen Absensi</h1>
            <p>Pantau dan telusuri catatan kehadiran seluruh peserta didik.</p>
        </div>
    </div>

    {{-- Ringkasan --}}
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

    {{-- Filter pencarian --}}
    <section class="panel">
        <div class="panel-header">
            <h2>Filter Absensi</h2>
            <p>Gunakan filter untuk menemukan catatan kehadiran tertentu.</p>
        </div>

        <form class="filter-form" method="GET"
              action="{{ route('admin.absensi.index') }}">

            <div class="field">
                <label for="nama">Nama atau NIS Peserta Didik</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ request('nama') }}"
                    placeholder="Contoh: Budi atau 123456"
                >
            </div>

            <div class="field">
                <label for="tanggal">Tanggal Absensi</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                >
            </div>

            <div class="field">
                <label for="status">Status Kehadiran</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    @foreach (['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa'] as $pilihanStatus)
                        <option
                            value="{{ $pilihanStatus }}"
                            @selected(request('status') === $pilihanStatus)
                        >
                            {{ $pilihanStatus }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="{{ route('admin.absensi.index') }}"
                   class="btn btn-light">Reset</a>
            </div>
        </form>
    </section>

    {{-- Tabel data --}}
    <section class="panel">
        <div class="table-header">
            <h2>Daftar Absensi</h2>
            <span class="data-count">{{ $absensis->total() }} data</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Peserta Didik</th>
                        <th>NIS</th>
                        <th>Mata Pelajaran</th>
                        <th>Tutor</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($absensis as $absensi)
                        @php
                            $status = strtolower(trim($absensi->status ?? ''));

                            $kelasStatus = match ($status) {
                                'hadir' => 'status-hadir',
                                'terlambat' => 'status-terlambat',
                                'izin' => 'status-izin',
                                'sakit' => 'status-sakit',
                                'alpa', 'tidak hadir' => 'status-alpa',
                                default => 'status-default',
                            };

                            $mataPelajaran = $absensi->jadwal?->mataPelajaran;
                            $tutor = $absensi->jadwal?->tutor;
                        @endphp

                        <tr>
                            <td class="number-cell">
                                {{ $absensis->firstItem() + $loop->index }}
                            </td>

                            <td class="student-name">
                                {{ $absensi->pesertaDidik->nama ?? 'Peserta tidak tersedia' }}
                            </td>

                            <td class="nis-cell">
                                {{ $absensi->pesertaDidik->nis ?? '-' }}
                            </td>

                            <td>
                                {{ $mataPelajaran->nama
                                    ?? $mataPelajaran->nama_mata_pelajaran
                                    ?? '-' }}
                            </td>

                            <td>
                                {{ $tutor->nama
                                    ?? $tutor->user->name
                                    ?? '-' }}
                            </td>

                            <td class="date-cell">
                                {{ $absensi->tanggal
                                    ? \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')
                                    : '-' }}
                            </td>

                            <td>
                                <span class="status-badge {{ $kelasStatus }}">
                                    {{ $absensi->status ?: 'Belum diisi' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <strong>Data absensi tidak ditemukan</strong>
                                    Coba ubah kata kunci atau filter pencarian.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($absensis->hasPages())
            <div class="pagination-wrap">
                {{ $absensis->links() }}
            </div>
        @endif
    </section>

</div>
@endsection

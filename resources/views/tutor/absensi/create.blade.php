@extends('layouts.dashboard')

@section('title', 'Form Input Absensi')
@section('page-title', 'Input Absensi')

@section('content')

<style>
    .input-absensi-page {
        width: 100%;
    }

    /* Notifikasi */
    .input-absensi-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 9px;
        font-size: 13px;
    }

    .input-absensi-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .input-absensi-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .input-absensi-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    /* Informasi jadwal */
    .input-absensi-info {
        padding: 22px;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, .035);
    }

    .input-absensi-info-header {
        margin-bottom: 18px;
    }

    .input-absensi-info-header h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .input-absensi-info-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .input-absensi-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px 22px;
    }

    .input-absensi-info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .input-absensi-info-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    .input-absensi-info-value {
        color: #1e293b;
        font-size: 13px;
        font-weight: 700;
    }

    /* Kartu tabel */
    .input-absensi-table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 13px;
    }

    .input-absensi-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 22px;
        border-bottom: 1px solid #e8edf4;
    }

    .input-absensi-table-header h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 800;
    }

    .input-absensi-table-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    /* Tabel */
    .input-absensi-table-card table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
    }

    .input-absensi-table-card thead th {
        padding: 14px 18px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
        border-bottom: 1px solid #e8edf4;
    }

    .input-absensi-table-card tbody td {
        padding: 15px 18px;
        color: #334155;
        font-size: 13px;
        vertical-align: middle;
        border-bottom: 1px solid #edf1f7;
    }

    .input-absensi-table-card tbody tr:last-child td {
        border-bottom: none;
    }

    .input-absensi-table-card tbody tr:hover {
        background: #f8fafc;
    }

    .input-absensi-number {
        color: #94a3b8 !important;
        font-weight: 700;
    }

    .input-absensi-nis {
        color: #64748b !important;
        font-family: monospace;
        font-size: 12px !important;
    }

    .input-absensi-name {
        color: #1e293b !important;
        font-weight: 700;
    }

    /* Dropdown status */
    .input-absensi-status {
        min-width: 125px;
        padding: 9px 30px 9px 12px;
        border: 1px solid #dbe3ee;
        border-radius: 8px;
        background-color: #fff;
        color: #334155;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .input-absensi-status:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    /* Warna status */
    .input-absensi-status.status-hadir {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
        color: #15803d;
    }

    .input-absensi-status.status-izin {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .input-absensi-status.status-sakit {
        background-color: #fff7ed;
        border-color: #fed7aa;
        color: #c2410c;
    }

    .input-absensi-status.status-alpa {
        background-color: #fff1f2;
        border-color: #fecdd3;
        color: #be123c;
    }

    /* Footer tombol */
    .input-absensi-footer {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        padding: 18px 22px;
        border-top: 1px solid #e8edf4;
    }

    .input-absensi-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 39px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
    }

    .input-absensi-button-primary {
        background: #3b82f6;
        color: #fff;
    }

    .input-absensi-button-primary:hover {
        background: #2563eb;
        box-shadow: 0 3px 8px rgba(37, 99, 235, .2);
    }

    .input-absensi-button-secondary {
        background: #fff;
        border-color: #dbe3ee;
        color: #475569;
    }

    .input-absensi-button-secondary:hover {
        background: #f8fafc;
    }

    /* Data kosong */
    .input-absensi-empty {
        padding: 35px 20px !important;
        text-align: center;
        color: #64748b !important;
        line-height: 1.7;
    }

    /* Responsif */
    @media (max-width: 650px) {
        .input-absensi-info {
            padding: 18px;
        }

        .input-absensi-info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .input-absensi-table-header {
            padding: 16px;
        }

        .input-absensi-footer {
            padding: 15px 16px;
        }
    }
</style>

<div class="input-absensi-page">

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="input-absensi-alert input-absensi-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi kesalahan --}}
    @if (session('error'))
        <div class="input-absensi-alert input-absensi-error" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validasi --}}
    @if ($errors->any())
        <div class="input-absensi-alert input-absensi-error" role="alert">
            <strong>Absensi belum dapat disimpan.</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi jadwal --}}
    <div class="input-absensi-info">

        <div class="input-absensi-info-header">
            <h3>Informasi Jadwal Pembelajaran</h3>
            <p>
                Pastikan informasi kelas sesuai sebelum
                melakukan pencatatan kehadiran.
            </p>
        </div>

        <div class="input-absensi-info-grid">

            <div class="input-absensi-info-item">
                <span class="input-absensi-info-label">
                    Mata Pelajaran
                </span>

                <span class="input-absensi-info-value">
                    {{ $jadwal->mataPelajaran->nama ?? '-' }}
                </span>
            </div>

            <div class="input-absensi-info-item">
                <span class="input-absensi-info-label">
                    Program Paket
                </span>

                <span class="input-absensi-info-value">
                    Paket {{ $jadwal->program_paket }}
                </span>
            </div>

            <div class="input-absensi-info-item">
                <span class="input-absensi-info-label">
                    Hari Pembelajaran
                </span>

                <span class="input-absensi-info-value">
                    {{ $jadwal->hari }}
                </span>
            </div>

            <div class="input-absensi-info-item">
                <span class="input-absensi-info-label">
                    Waktu Pembelajaran
                </span>

                <span class="input-absensi-info-value">
                    {{ substr($jadwal->jam_mulai, 0, 5) }}
                    -
                    {{ substr($jadwal->jam_selesai, 0, 5) }}
                </span>
            </div>

        </div>
    </div>

    {{-- Tabel input kehadiran --}}
    <div class="input-absensi-table-card">

        <div class="input-absensi-table-header">
            <div>
                <h3>Daftar Peserta Didik</h3>
                <p>
                    Pilih status kehadiran setiap peserta didik.
                </p>
            </div>
        </div>

        <form
            action="{{ route('tutor.absensi.store', $jadwal->id) }}"
            method="POST"
        >
            @csrf

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIS</th>
                            <th>Nama Peserta Didik</th>
                            <th>Status Kehadiran</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesertaDidiks as $peserta)

                            @php
                                $statusTersimpan = optional(
                                    $absensis->get($peserta->id)
                                )->status;

                                $statusSaatIni = old(
                                    'absensi.' . $peserta->id,
                                    $statusTersimpan ?? 'Hadir'
                                );
                            @endphp

                            <tr>
                                <td class="input-absensi-number">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="input-absensi-nis">
                                    {{ $peserta->nis }}
                                </td>

                                <td class="input-absensi-name">
                                    {{ $peserta->nama }}
                                </td>

                                <td>
                                    <select
                                        name="absensi[{{ $peserta->id }}]"
                                        class="input-absensi-status"
                                        required
                                    >
                                        <option
                                            value="Hadir"
                                            {{ $statusSaatIni === 'Hadir' ? 'selected' : '' }}
                                        >
                                            Hadir
                                        </option>

                                        <option
                                            value="Izin"
                                            {{ $statusSaatIni === 'Izin' ? 'selected' : '' }}
                                        >
                                            Izin
                                        </option>

                                        <option
                                            value="Sakit"
                                            {{ $statusSaatIni === 'Sakit' ? 'selected' : '' }}
                                        >
                                            Sakit
                                        </option>

                                        <option
                                            value="Alpa"
                                            {{ $statusSaatIni === 'Alpa' ? 'selected' : '' }}
                                        >
                                            Alpa
                                        </option>
                                    </select>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="input-absensi-empty">
                                    <strong>Belum ada peserta didik.</strong>
                                    <br>
                                    Belum ada peserta didik untuk Paket
                                    {{ $jadwal->program_paket }}.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tombol aksi --}}
            <div class="input-absensi-footer">

                <a
                    href="{{ route('tutor.absensi.index') }}"
                    class="input-absensi-button input-absensi-button-secondary"
                >
                    Kembali
                </a>

                @if ($pesertaDidiks->isNotEmpty())
                    <button
                        type="submit"
                        class="input-absensi-button input-absensi-button-primary"
                    >
                        Simpan Absensi
                    </button>
                @endif

            </div>

        </form>
    </div>

</div>

{{-- Warna dropdown mengikuti status yang dipilih --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dropdownStatus = document.querySelectorAll(
        '.input-absensi-status'
    );

    function perbaruiWarna(select) {
        select.classList.remove(
            'status-hadir',
            'status-izin',
            'status-sakit',
            'status-alpa'
        );

        const status = select.value.toLowerCase();

        if (status === 'hadir') {
            select.classList.add('status-hadir');
        } else if (status === 'izin') {
            select.classList.add('status-izin');
        } else if (status === 'sakit') {
            select.classList.add('status-sakit');
        } else if (status === 'alpa') {
            select.classList.add('status-alpa');
        }
    }

    dropdownStatus.forEach(function (select) {
        perbaruiWarna(select);

        select.addEventListener('change', function () {
            perbaruiWarna(select);
        });
    });

});
</script>

@endsection

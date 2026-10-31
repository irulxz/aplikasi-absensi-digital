@extends('layouts.dashboard')

@section('title', 'Input Absensi')
@section('page-title', 'Input Absensi')

@section('content')

<style>
    .absensi-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .absensi-intro {
        padding: 25px;
        border-radius: 16px;
        color: #fff;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 8px 24px rgba(37, 99, 235, .15);
    }

    .absensi-intro h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .absensi-intro p {
        margin: 0;
        color: #eff6ff;
        line-height: 1.7;
    }

    .absensi-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .absensi-summary-card {
        padding: 22px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
    }

    .absensi-summary-card p {
        margin: 0;
    }

    .absensi-summary-label {
        margin-bottom: 10px !important;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .absensi-summary-value {
        color: #1d4ed8;
        font-size: 28px;
        font-weight: 800;
    }

    .absensi-table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .04);
    }

    .absensi-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .absensi-table-header h3 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .absensi-table-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .absensi-search {
        width: 100%;
        max-width: 300px;
        padding: 11px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        background: #f8fafc;
        color: #1e293b;
        font-size: 14px;
    }

    .absensi-search:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .absensi-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .absensi-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .absensi-table thead {
        background: #f8fafc;
    }

    .absensi-table th {
        padding: 15px 18px;
        color: #475569;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .absensi-table td {
        padding: 16px 18px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .absensi-table tbody tr:hover {
        background: #f8fafc;
    }

    .absensi-number {
        display: inline-flex;
        width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }

    .absensi-day-badge,
    .absensi-level-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .absensi-day-badge {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .absensi-level-badge {
        background: #f1f5f9;
        color: #475569;
    }

    .absensi-subject {
        color: #0f172a;
        font-weight: 600;
    }

    .absensi-time {
        color: #334155;
        font-weight: 600;
        white-space: nowrap;
    }

    .absensi-status {
        display: inline-block;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .absensi-status-aktif {
        background: #dcfce7;
        color: #166534;
    }

    .absensi-status-tutup {
        background: #fee2e2;
        color: #991b1b;
    }

    .absensi-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 9px;
        background: #2563eb;
        color: #fff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        white-space: nowrap;
        transition: background .2s, transform .2s;
    }

    .absensi-action:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .absensi-empty {
        padding: 42px 24px;
        text-align: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
    }

    .absensi-empty-icon {
        display: inline-flex;
        width: 58px;
        height: 58px;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 26px;
    }

    .absensi-empty h3 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 18px;
    }

    .absensi-empty p {
        max-width: 450px;
        margin: 0 auto;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .absensi-no-result {
        display: none;
        padding: 24px;
        color: #64748b;
        text-align: center;
        font-size: 14px;
    }

    @media (max-width: 640px) {
        .absensi-summary {
            grid-template-columns: 1fr;
        }

        .absensi-intro {
            padding: 20px;
        }

        .absensi-intro h2 {
            font-size: 19px;
        }

        .absensi-table-header {
            align-items: stretch;
            padding: 18px;
        }

        .absensi-search {
            max-width: none;
        }
    }
</style>

<div class="absensi-page">

    {{-- Pengantar --}}
    <section class="absensi-intro">
        <h2>Input Absensi Peserta Didik</h2>
        <p>
            Pilih jadwal mengajar untuk melihat peserta didik
            yang perlu diabsenkan. Input absensi tersedia sesuai
            status jam pelajaran yang ditentukan oleh sistem.
        </p>
    </section>

    {{-- Tutor tidak ditemukan --}}
    @if (!$tutor)

        <section class="absensi-empty">
            <div class="absensi-empty-icon">!</div>
            <h3>Data Tutor Belum Ditemukan</h3>
            <p>
                Akun Anda belum terhubung dengan data tutor.
                Silakan hubungi Admin untuk memeriksa akun Anda.
            </p>
        </section>

    {{-- Jadwal belum tersedia --}}
    @elseif ($jadwals->isEmpty())

        <div class="absensi-summary">
            <section class="absensi-summary-card">
                <p class="absensi-summary-label">Total Jadwal Mengajar</p>
                <p class="absensi-summary-value">0</p>
            </section>

            <section class="absensi-summary-card">
                <p class="absensi-summary-label">Jadwal Bisa Diabsen</p>
                <p class="absensi-summary-value">0</p>
            </section>
        </div>

        <section class="absensi-empty">
            <div class="absensi-empty-icon">📅</div>
            <h3>Belum Ada Jadwal Mengajar</h3>
            <p>
                Belum ada jadwal yang ditugaskan kepada akun Anda.
                Silakan hubungi Admin jika jadwal seharusnya sudah tersedia.
            </p>
        </section>

    {{-- Jadwal tersedia --}}
    @else

        <div class="absensi-summary">
            <section class="absensi-summary-card">
                <p class="absensi-summary-label">Total Jadwal Mengajar</p>
                <p class="absensi-summary-value">{{ $jadwals->count() }}</p>
            </section>

            <section class="absensi-summary-card">
                <p class="absensi-summary-label">Jadwal Bisa Diabsen</p>
                <p class="absensi-summary-value">
                    {{ $jadwals->where('absensi_aktif', true)->count() }}
                </p>
            </section>
        </div>

        <section class="absensi-table-card">

            <div class="absensi-table-header">
                <div>
                    <h3>Daftar Jadwal Mengajar</h3>
                    <p>
                        Pilih tombol Input Absensi saat jadwal berstatus aktif.
                    </p>
                </div>

                <input
                    type="text"
                    id="cariJadwalAbsensi"
                    class="absensi-search"
                    placeholder="Cari hari, mapel, atau tingkat..."
                    aria-label="Cari jadwal absensi"
                >
            </div>

            <div class="absensi-table-wrapper">
                <table class="absensi-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Tingkat</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($jadwals as $jadwal)
                            <tr class="baris-jadwal-absensi">
                                <td>
                                    <span class="absensi-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <span class="absensi-day-badge">
                                        {{ $jadwal->hari }}
                                    </span>
                                </td>

                                <td>
                                    <span class="absensi-subject">
                                        {{ $jadwal->mataPelajaran->nama
                                            ?? 'Belum tersedia' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="absensi-level-badge">
                                        {{ $jadwal->mataPelajaran->tingkat
                                            ?? '-' }}
                                    </span>
                                </td>

                                <td class="absensi-time">
                                    {{ \Carbon\Carbon::parse(
                                        $jadwal->jam_mulai
                                    )->format('H:i') }}
                                </td>

                                <td class="absensi-time">
                                    {{ \Carbon\Carbon::parse(
                                        $jadwal->jam_selesai
                                    )->format('H:i') }}
                                </td>

                                <td>
                                    @if ($jadwal->absensi_aktif)
                                        <span class="absensi-status absensi-status-aktif">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="absensi-status absensi-status-tutup">
                                            Di luar jam pelajaran
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($jadwal->absensi_aktif)
                                        <a
                                            href="{{ route('tutor.absensi.create', $jadwal->id) }}"
                                            class="absensi-action"
                                        >
                                            Input Absensi →
                                        </a>
                                    @else
                                        <span style="color: #94a3b8; font-size: 13px;">
                                            Belum tersedia
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="absensiTidakDitemukan" class="absensi-no-result">
                Jadwal yang cocok dengan pencarian tidak ditemukan.
            </div>

        </section>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputCari = document.getElementById('cariJadwalAbsensi');
        const barisJadwal = document.querySelectorAll('.baris-jadwal-absensi');
        const pesanKosong = document.getElementById('absensiTidakDitemukan');

        if (!inputCari) {
            return;
        }

        inputCari.addEventListener('input', function () {
            const kataKunci = this.value.toLowerCase().trim();
            let jumlahTampil = 0;

            barisJadwal.forEach(function (baris) {
                const cocok = baris.textContent
                    .toLowerCase()
                    .includes(kataKunci);

                baris.style.display = cocok ? '' : 'none';

                if (cocok) {
                    jumlahTampil++;
                }
            });

            if (pesanKosong) {
                pesanKosong.style.display =
                    jumlahTampil === 0 ? 'block' : 'none';
            }
        });
    });
</script>

@endsection
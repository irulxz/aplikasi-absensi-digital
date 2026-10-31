@extends('layouts.dashboard')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')

@section('content')

<style>
    .jadwal-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .jadwal-intro {
        padding: 25px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        color: #fff;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
    }

    .jadwal-intro h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .jadwal-intro p {
        margin: 0;
        line-height: 1.7;
        color: #eff6ff;
    }

    .jadwal-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .jadwal-summary-card {
        padding: 22px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }

    .jadwal-summary-card .summary-label {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .jadwal-summary-card .summary-value {
        margin: 0;
        color: #1d4ed8;
        font-size: 28px;
        font-weight: 800;
    }

    .jadwal-table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }

    .jadwal-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .jadwal-table-header h3 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .jadwal-table-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .jadwal-search {
        width: 100%;
        max-width: 300px;
        padding: 11px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        background: #f8fafc;
        color: #1e293b;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }

    .jadwal-search:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .jadwal-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .jadwal-table {
        width: 100%;
        min-width: 700px;
        border-collapse: collapse;
    }

    .jadwal-table thead {
        background: #f8fafc;
    }

    .jadwal-table th {
        padding: 15px 18px;
        color: #475569;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .jadwal-table td {
        padding: 16px 18px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .jadwal-table tbody tr {
        transition: background .2s;
    }

    .jadwal-table tbody tr:hover {
        background: #f8fafc;
    }

    .jadwal-number {
        display: inline-flex;
        width: 30px;
        height: 30px;
        justify-content: center;
        align-items: center;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }

    .jadwal-day-badge,
    .jadwal-level-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .jadwal-day-badge {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .jadwal-level-badge {
        background: #f1f5f9;
        color: #475569;
    }

    .jadwal-subject {
        color: #0f172a;
        font-weight: 600;
    }

    .jadwal-time {
        color: #334155;
        font-weight: 600;
        white-space: nowrap;
    }

    .jadwal-empty {
        padding: 42px 24px;
        text-align: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
    }

    .jadwal-empty-icon {
        display: inline-flex;
        width: 58px;
        height: 58px;
        justify-content: center;
        align-items: center;
        margin-bottom: 14px;
        border-radius: 16px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 26px;
    }

    .jadwal-empty h3 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 18px;
    }

    .jadwal-empty p {
        max-width: 450px;
        margin: 0 auto;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .jadwal-no-result {
        display: none;
        padding: 24px;
        color: #64748b;
        text-align: center;
        font-size: 14px;
    }

    @media (max-width: 640px) {
        .jadwal-summary {
            grid-template-columns: 1fr;
        }

        .jadwal-intro {
            padding: 20px;
        }

        .jadwal-intro h2 {
            font-size: 19px;
        }

        .jadwal-table-header {
            align-items: stretch;
            padding: 18px;
        }

        .jadwal-search {
            max-width: none;
        }
    }
</style>

<div class="jadwal-page">

    {{-- Bagian pengantar --}}
    <section class="jadwal-intro">
        <h2>Jadwal Mengajar Saya</h2>
        <p>
            Lihat jadwal pembelajaran yang menjadi tanggung jawab Anda.
            Gunakan kolom pencarian untuk menemukan jadwal dengan cepat.
        </p>
    </section>

    {{-- Data tutor belum ditemukan --}}
    @if (!$tutor)

        <section class="jadwal-empty">
            <div class="jadwal-empty-icon">!</div>
            <h3>Data Tutor Belum Ditemukan</h3>
            <p>
                Akun Anda belum terhubung dengan data tutor.
                Silakan hubungi Admin agar data akun dapat diperiksa.
            </p>
        </section>

    {{-- Tutor belum memiliki jadwal --}}
    @elseif ($jadwals->isEmpty())

        <div class="jadwal-summary">
            <section class="jadwal-summary-card">
                <p class="summary-label">Total Jadwal Mengajar</p>
                <p class="summary-value">0</p>
            </section>

            <section class="jadwal-summary-card">
                <p class="summary-label">Status Jadwal</p>
                <p class="summary-value" style="font-size: 20px;">
                    Belum Ada
                </p>
            </section>
        </div>

        <section class="jadwal-empty">
            <div class="jadwal-empty-icon">📅</div>
            <h3>Belum Ada Jadwal Mengajar</h3>
            <p>
                Saat ini belum ada jadwal mengajar yang terhubung
                dengan akun Anda. Silakan periksa kembali nanti
                atau hubungi Admin.
            </p>
        </section>

    {{-- Jadwal tersedia --}}
    @else

        <div class="jadwal-summary">
            <section class="jadwal-summary-card">
                <p class="summary-label">Total Jadwal Mengajar</p>
                <p class="summary-value">{{ $jadwals->count() }}</p>
            </section>

            <section class="jadwal-summary-card">
                <p class="summary-label">Status Jadwal</p>
                <p class="summary-value" style="font-size: 20px;">
                    Tersedia
                </p>
            </section>
        </div>

        <section class="jadwal-table-card">

            <div class="jadwal-table-header">
                <div>
                    <h3>Daftar Jadwal Mengajar</h3>
                    <p>Jadwal pembelajaran yang terhubung dengan akun Anda.</p>
                </div>

                <input
                    type="text"
                    id="cariJadwalTutor"
                    class="jadwal-search"
                    placeholder="Cari hari, mapel, atau tingkat..."
                    aria-label="Cari jadwal mengajar"
                >
            </div>

            <div class="jadwal-table-wrapper">
                <table class="jadwal-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Tingkat</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                        </tr>
                    </thead>

                    <tbody id="daftarJadwalTutor">
                        @foreach ($jadwals as $jadwal)
                            <tr class="baris-jadwal-tutor">
                                <td>
                                    <span class="jadwal-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <span class="jadwal-day-badge">
                                        {{ $jadwal->hari }}
                                    </span>
                                </td>

                                <td>
                                    <span class="jadwal-subject">
                                        {{ $jadwal->mataPelajaran->nama
                                            ?? 'Belum tersedia' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="jadwal-level-badge">
                                        {{ $jadwal->mataPelajaran->tingkat
                                            ?? '-' }}
                                    </span>
                                </td>

                                <td class="jadwal-time">
                                    {{ \Carbon\Carbon::parse(
                                        $jadwal->jam_mulai
                                    )->format('H:i') }}
                                </td>

                                <td class="jadwal-time">
                                    {{ \Carbon\Carbon::parse(
                                        $jadwal->jam_selesai
                                    )->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="jadwalTutorTidakDitemukan" class="jadwal-no-result">
                Jadwal yang cocok dengan pencarian tidak ditemukan.
            </div>

        </section>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputCari = document.getElementById('cariJadwalTutor');
        const barisJadwal = document.querySelectorAll('.baris-jadwal-tutor');
        const pesanKosong = document.getElementById(
            'jadwalTutorTidakDitemukan'
        );

        if (!inputCari) {
            return;
        }

        inputCari.addEventListener('input', function () {
            const kataKunci = this.value.toLowerCase().trim();
            let jumlahTampil = 0;

            barisJadwal.forEach(function (baris) {
                const isiBaris = baris.textContent.toLowerCase();
                const cocok = isiBaris.includes(kataKunci);

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
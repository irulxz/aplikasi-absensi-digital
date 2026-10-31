@extends('layouts.dashboard')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi')

@section('content')

<style>
    .rekap-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .rekap-intro {
        padding: 25px;
        border-radius: 16px;
        color: #fff;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        box-shadow: 0 8px 24px rgba(37, 99, 235, .15);
    }

    .rekap-intro h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 700;
    }

    .rekap-intro p {
        margin: 0;
        color: #eff6ff;
        line-height: 1.7;
    }

    .rekap-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .rekap-summary-card {
        padding: 22px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
    }

    .rekap-summary-label {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .rekap-summary-value {
        margin: 0;
        color: #1d4ed8;
        font-size: 28px;
        font-weight: 800;
    }

    .rekap-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 22px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
    }

    .rekap-toolbar h3 {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 700;
    }

    .rekap-toolbar p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .rekap-search {
        width: 100%;
        max-width: 320px;
        padding: 11px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        outline: none;
        background: #f8fafc;
        color: #1e293b;
        font-size: 14px;
    }

    .rekap-search:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .rekap-group {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .03);
        transition: box-shadow .2s;
    }

    .rekap-group:hover {
        box-shadow: 0 6px 18px rgba(15, 23, 42, .06);
    }

    .rekap-group-header {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        border: none;
        background: #fff;
        text-align: left;
        cursor: pointer;
        color: #172943;
    }

    .rekap-group-header:hover {
        background: #f8fafc;
    }

    .rekap-group-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
        flex-shrink: 0;
    }

    .rekap-group-info {
        flex: 1;
        min-width: 0;
    }

    .rekap-group-title {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .rekap-group-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .rekap-count {
        display: inline-block;
        margin-left: 5px;
        padding: 4px 8px;
        border-radius: 7px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .rekap-chevron {
        color: #64748b;
        font-size: 18px;
        transition: transform .2s ease;
        flex-shrink: 0;
    }

    .rekap-group-header[aria-expanded="true"] .rekap-chevron {
        transform: rotate(180deg);
    }

    .rekap-group-body {
        padding: 20px;
        border-top: 1px solid #edf0f5;
    }

    .rekap-group-body[hidden] {
        display: none;
    }

    .rekap-detail-title {
        margin: 0 0 16px;
        color: #172943;
        font-size: 15px;
        font-weight: 700;
    }

    .rekap-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .rekap-table {
        width: 100%;
        min-width: 580px;
        border-collapse: collapse;
    }

    .rekap-table thead {
        background: #f8fafc;
    }

    .rekap-table th {
        padding: 14px 16px;
        color: #475569;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .rekap-table td {
        padding: 15px 16px;
        border-top: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .rekap-table tbody tr:hover {
        background: #f8fafc;
    }

    .rekap-number {
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

    .rekap-date {
        white-space: nowrap;
        font-weight: 500;
    }

    .rekap-student {
        color: #0f172a;
        font-weight: 600;
    }

    .rekap-status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .rekap-status-hadir {
        background: #dcfce7;
        color: #166534;
    }

    .rekap-status-izin {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .rekap-status-sakit {
        background: #fef3c7;
        color: #92400e;
    }

    .rekap-status-alpa {
        background: #fee2e2;
        color: #991b1b;
    }

    .rekap-status-lain {
        background: #f1f5f9;
        color: #475569;
    }

    .rekap-empty {
        padding: 30px 20px;
        color: #64748b;
        text-align: center;
        font-size: 14px;
    }

    .rekap-empty-card {
        padding: 42px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        text-align: center;
    }

    .rekap-empty-icon {
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

    .rekap-empty-card h3 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 18px;
    }

    .rekap-empty-card p {
        max-width: 450px;
        margin: 0 auto;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .rekap-no-result {
        display: none;
        padding: 28px 20px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        color: #64748b;
        background: #fff;
        text-align: center;
    }

    @media (max-width: 640px) {
        .rekap-summary {
            grid-template-columns: 1fr;
        }

        .rekap-intro {
            padding: 20px;
        }

        .rekap-intro h2 {
            font-size: 19px;
        }

        .rekap-toolbar {
            align-items: stretch;
            padding: 18px;
        }

        .rekap-search {
            max-width: none;
        }

        .rekap-group-header {
            gap: 10px;
            padding: 15px;
        }

        .rekap-group-icon {
            width: 40px;
            height: 40px;
        }

        .rekap-group-body {
            padding: 15px;
        }

        .rekap-count {
            margin: 6px 0 0;
        }
    }
</style>

<div class="rekap-page">

    {{-- Pengantar --}}
    <section class="rekap-intro">
        <h2>Rekap Absensi Peserta Didik</h2>
        <p>
            Pantau riwayat kehadiran peserta didik berdasarkan mata
            pelajaran dan program paket yang menjadi tanggung jawab Anda.
        </p>
    </section>

    @if (!$tutor)

        <section class="rekap-empty-card">
            <div class="rekap-empty-icon">!</div>
            <h3>Data Tutor Belum Ditemukan</h3>
            <p>
                Akun Anda belum terhubung dengan data tutor.
                Silakan hubungi Admin untuk memeriksa akun Anda.
            </p>
        </section>

    @elseif ($kelompokRekap->isEmpty())

        <section class="rekap-empty-card">
            <div class="rekap-empty-icon">📋</div>
            <h3>Belum Ada Jadwal Mengajar</h3>
            <p>
                Belum ada jadwal yang ditugaskan kepada Anda,
                sehingga rekap absensi belum tersedia.
            </p>
        </section>

    @else

        @php
            $totalCatatan = $kelompokRekap->sum(
                fn ($kelompok) => $kelompok['absensis']->count()
            );
        @endphp

        <div class="rekap-summary">
            <section class="rekap-summary-card">
                <p class="rekap-summary-label">Total Kelompok Jadwal</p>
                <p class="rekap-summary-value">
                    {{ $kelompokRekap->count() }}
                </p>
            </section>

            <section class="rekap-summary-card">
                <p class="rekap-summary-label">Total Catatan Absensi</p>
                <p class="rekap-summary-value">{{ $totalCatatan }}</p>
            </section>
        </div>

        <section class="rekap-toolbar">
            <div>
                <h3>Daftar Rekap Absensi</h3>
                <p>Pilih kelompok untuk melihat rincian kehadiran.</p>
            </div>

            <input
                type="search"
                id="cariRekapAbsensi"
                class="rekap-search"
                placeholder="Cari mapel, paket, nama, status..."
                aria-label="Cari rekap absensi"
            >
        </section>

        <div id="daftarKelompokRekap">
            @foreach ($kelompokRekap as $kelompok)
                @php
                    $panelId = 'rekap-panel-' . $loop->index;
                @endphp

                <section class="rekap-group" data-rekap-group>
                    <button
                        type="button"
                        class="rekap-group-header"
                        data-rekap-toggle
                        aria-expanded="false"
                        aria-controls="{{ $panelId }}"
                    >
                        <span class="rekap-group-icon" aria-hidden="true">
                            <i class="bi bi-book"></i>
                        </span>

                        <span class="rekap-group-info">
                            <span class="rekap-group-title d-block">
                                {{ $kelompok['mata_pelajaran'] }}
                            </span>

                            <span class="rekap-group-subtitle d-block">
                                Program Paket {{ $kelompok['program_paket'] }}
                            </span>

                            <span class="rekap-count">
                                {{ $kelompok['absensis']->count() }}
                                catatan absensi
                            </span>
                        </span>

                        <span class="rekap-chevron" aria-hidden="true">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>

                    <div
                        id="{{ $panelId }}"
                        class="rekap-group-body"
                        hidden
                    >
                        <h4 class="rekap-detail-title">
                            {{ $kelompok['mata_pelajaran'] }}
                            — Paket {{ $kelompok['program_paket'] }}
                        </h4>

                        @if ($kelompok['absensis']->isEmpty())
                            <div class="rekap-empty">
                                Belum ada catatan absensi untuk kelompok ini.
                            </div>
                        @else
                            <div class="rekap-table-wrapper">
                                <table class="rekap-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Nama Peserta Didik</th>
                                            <th>Status Kehadiran</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($kelompok['absensis'] as $absensi)
                                            @php
                                                $statusClass = [
                                                    'Hadir' => 'rekap-status-hadir',
                                                    'Izin' => 'rekap-status-izin',
                                                    'Sakit' => 'rekap-status-sakit',
                                                    'Alpa' => 'rekap-status-alpa',
                                                ][$absensi->status] ?? 'rekap-status-lain';
                                            @endphp

                                            <tr>
                                                <td>
                                                    <span class="rekap-number">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </td>

                                                <td class="rekap-date">
                                                    {{ \Carbon\Carbon::parse(
                                                        $absensi->tanggal
                                                    )->format('d-m-Y') }}
                                                </td>

                                                <td class="rekap-student">
                                                    {{ $absensi->pesertaDidik->nama ?? '-' }}
                                                </td>

                                                <td>
                                                    <span class="rekap-status {{ $statusClass }}">
                                                        {{ $absensi->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>

        <div id="rekapTidakDitemukan" class="rekap-no-result">
            Tidak ada rekap absensi yang cocok dengan pencarian.
        </div>

    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Buka dan tutup kelompok rekap.
        document.querySelectorAll('[data-rekap-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const panelId = button.getAttribute('aria-controls');
                const panel = document.getElementById(panelId);
                const sedangTerbuka =
                    button.getAttribute('aria-expanded') === 'true';

                button.setAttribute('aria-expanded', String(!sedangTerbuka));
                panel.hidden = sedangTerbuka;
            });
        });

        // Pencarian pada kelompok dan rincian yang dirender.
        const inputCari = document.getElementById('cariRekapAbsensi');
        const kelompok = document.querySelectorAll('[data-rekap-group]');
        const pesanKosong = document.getElementById('rekapTidakDitemukan');

        if (!inputCari) {
            return;
        }

        inputCari.addEventListener('input', function () {
            const kataKunci = this.value.toLowerCase().trim();
            let jumlahCocok = 0;

            kelompok.forEach(function (item) {
                const isiKelompok = item.textContent.toLowerCase();
                const cocok = isiKelompok.includes(kataKunci);

                item.style.display = cocok ? '' : 'none';

                if (cocok) {
                    jumlahCocok++;
                }
            });

            if (pesanKosong) {
                pesanKosong.style.display =
                    jumlahCocok === 0 ? 'block' : 'none';
            }
        });
    });
</script>

@endsection
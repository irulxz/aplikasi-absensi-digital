@extends('layouts.dashboard')

@section('title', 'Kelas dan Jadwal')
@section('page-title', 'Kelas dan Jadwal')

@section('content')

<style>
    .jadwal-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .jadwal-description {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.7;
    }

    .jadwal-summary {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 12px;
    }

    .jadwal-summary-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 11px;
        background: #eaf2ff;
        color: #2563eb;
        font-size: 20px;
    }

    .jadwal-summary-label {
        color: #64748b;
        font-size: 12px;
    }

    .jadwal-summary-value {
        margin-top: 4px;
        color: #172033;
        font-size: 22px;
        font-weight: 800;
    }

    .jadwal-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 9px;
        font-size: 13px;
    }

    .jadwal-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .jadwal-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .jadwal-table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 14px;
    }

    .jadwal-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding: 20px 22px;
        border-bottom: 1px solid #e8edf4;
    }

    .jadwal-table-header h2 {
        margin: 0;
        color: #172033;
        font-size: 16px;
    }

    .jadwal-table-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .jadwal-search {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .jadwal-search input {
        width: 250px;
        max-width: 100%;
        padding: 10px 12px;
        border: 1px solid #dbe3ee;
        border-radius: 8px;
        outline: none;
        font: inherit;
        font-size: 13px;
        background: #fff;
    }

    .jadwal-search input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .jadwal-search button,
    .jadwal-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 13px;
        border: 0;
        border-radius: 8px;
        background: #2563eb;
        color: #fff;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .jadwal-reset {
        background: #edf1f7;
        color: #475569;
    }

    .jadwal-table-card table {
        min-width: 760px;
    }

    .jadwal-table-card td {
        vertical-align: middle;
    }

    .jadwal-mapel {
        color: #1e293b;
        font-weight: 700;
    }

    .jadwal-tutor {
        color: #475569;
    }

    .jadwal-day {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
    }

    .jadwal-time {
        color: #475569;
        white-space: nowrap;
    }

    .jadwal-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jadwal-actions form {
        margin: 0;
    }

    .jadwal-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 11px;
        border: 0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .jadwal-edit {
        background: #fff7ed;
        color: #c2410c;
    }

    .jadwal-delete {
        background: #fff1f2;
        color: #be123c;
    }

    .jadwal-empty {
        padding: 35px 20px !important;
        text-align: center !important;
        color: #64748b;
        white-space: normal;
    }

    @media (max-width: 650px) {
        .jadwal-toolbar {
            align-items: stretch;
        }

        .jadwal-toolbar .primary-button {
            align-self: flex-start;
        }

        .jadwal-table-header {
            align-items: stretch;
            padding: 16px;
        }

        .jadwal-search {
            width: 100%;
        }

        .jadwal-search input {
            flex: 1;
            min-width: 0;
            width: 100%;
        }
    }
</style>

<div class="jadwal-toolbar">
    <div>
        <p class="jadwal-description">
            Kelola jadwal kelas, mata pelajaran, tutor, dan waktu belajar.
        </p>
    </div>

    <a href="{{ route('admin.kelas.create') }}" class="primary-button">
        + Tambah Jadwal
    </a>
</div>

@if (session('success'))
    <div class="jadwal-alert jadwal-success" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="jadwal-alert jadwal-error" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="jadwal-alert jadwal-error" role="alert">
        Periksa kembali data yang dimasukkan.
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="jadwal-summary">
    <div class="jadwal-summary-icon">📚</div>

    <div>
        <div class="jadwal-summary-label">Total jadwal ditemukan</div>
        <div class="jadwal-summary-value" id="jumlahJadwal">
            {{ $jadwals->count() }}
        </div>
    </div>
</div>

<div class="jadwal-table-card">
    <div class="jadwal-table-header">
        <div>
            <h2>Daftar Kelas dan Jadwal</h2>
            <p>Informasi mata pelajaran, tutor, dan waktu belajar.</p>
        </div>

        <div class="jadwal-search" role="search">
            <input
                type="search"
                id="searchJadwal"
                placeholder="Cari mata pelajaran, tutor, atau hari..."
                aria-label="Cari mata pelajaran, tutor, atau hari"
                autocomplete="off"
            >

            <button
                type="button"
                id="resetJadwal"
                class="jadwal-reset"
                hidden
            >
                Reset
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tabelJadwal">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Mata Pelajaran</th>
                    <th>Tutor</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="isiTabelJadwal">
                @forelse ($jadwals as $jadwal)
                    <tr data-jadwal-row>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <span class="jadwal-mapel">
                                {{ $jadwal->mataPelajaran?->nama ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="jadwal-tutor">
                                {{ $jadwal->tutor?->nama ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="jadwal-day">
                                {{ $jadwal->hari }}
                            </span>
                        </td>

                        <td>
                            <span class="jadwal-time">
                                {{ \Illuminate\Support\Str::substr($jadwal->jam_mulai, 0, 5) }}
                            </span>
                        </td>

                        <td>
                            <span class="jadwal-time">
                                {{ \Illuminate\Support\Str::substr($jadwal->jam_selesai, 0, 5) }}
                            </span>
                        </td>

                        <td>
                            <div class="jadwal-actions">
                                <a
                                    href="{{ route('admin.kelas.edit', $jadwal) }}"
                                    class="jadwal-action jadwal-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.kelas.destroy', $jadwal) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="jadwal-action jadwal-delete"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="jadwalAwalKosong">
                        <td colspan="7" class="jadwal-empty">
                            Belum ada jadwal yang terdaftar.
                            Silakan tambahkan jadwal baru.
                        </td>
                    </tr>
                @endforelse

                <tr id="jadwalTidakDitemukan" hidden>
                    <td colspan="7" class="jadwal-empty">
                        Jadwal tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchJadwal');
    const reset = document.getElementById('resetJadwal');
    const tbody = document.getElementById('isiTabelJadwal');
    const jumlahJadwal = document.getElementById('jumlahJadwal');
    const pesanTidakDitemukan = document.getElementById(
        'jadwalTidakDitemukan'
    );
    const pesanAwalKosong = document.getElementById('jadwalAwalKosong');

    if (!input || !reset || !tbody) {
        return;
    }

    const barisJadwal = Array.from(
        tbody.querySelectorAll('tr[data-jadwal-row]')
    );

    function cariJadwal() {
        const kataKunci = input.value
            .trim()
            .toLocaleLowerCase('id-ID');

        let jumlahCocok = 0;

        barisJadwal.forEach(function (baris) {
            const kolom = baris.querySelectorAll('td');

            // Kolom 1: mata pelajaran
            // Kolom 2: tutor
            // Kolom 3: hari
            const teksDicari = [
                kolom[1]?.textContent ?? '',
                kolom[2]?.textContent ?? '',
                kolom[3]?.textContent ?? ''
            ]
                .join(' ')
                .trim()
                .toLocaleLowerCase('id-ID');

            const cocok = teksDicari.includes(kataKunci);

            baris.hidden = !cocok;

            if (cocok) {
                jumlahCocok++;
            }
        });

        // Perbarui jumlah jadwal yang sedang ditampilkan.
        if (jumlahJadwal) {
            jumlahJadwal.textContent = jumlahCocok;
        }

        // Tampilkan pesan jika tidak ada jadwal yang cocok.
        if (pesanTidakDitemukan) {
            pesanTidakDitemukan.hidden =
                kataKunci.length === 0 || jumlahCocok > 0;
        }

        // Baris pesan awal hanya ditampilkan jika database kosong.
        if (pesanAwalKosong) {
            pesanAwalKosong.hidden = kataKunci.length > 0;
        }

        // Tombol reset hanya muncul saat pencarian terisi.
        reset.hidden = kataKunci.length === 0;
    }

    // Filter berjalan langsung setiap kali pengguna mengetik.
    input.addEventListener('input', cariJadwal);

    // Kembalikan seluruh jadwal.
    reset.addEventListener('click', function () {
        input.value = '';
        cariJadwal();
        input.focus();
    });

    // Pastikan jumlah awal sesuai dengan baris yang ditampilkan.
    cariJadwal();
});
</script>

@endsection

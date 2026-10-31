@extends('layouts.dashboard')

@section('title', 'Data Peserta Didik')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .peserta-page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin: 24px 0;
    }

    .peserta-page-heading h2 {
        margin: 0 0 8px;
        font-size: 28px;
        color: #17243b;
    }

    .peserta-page-heading p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .peserta-list-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 15px;
    }

    .peserta-list-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        padding: 24px;
    }

    .peserta-list-toolbar h3 {
        margin: 0 0 5px;
        color: #17243b;
        font-size: 16px;
    }

    .peserta-list-toolbar p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .peserta-search {
        width: 100%;
        max-width: 385px;
        padding: 13px 15px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        outline: none;
        color: #1e293b;
        font: inherit;
        font-size: 14px;
        transition: .2s;
    }

    .peserta-search:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .peserta-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .peserta-table {
        width: 100%;
        min-width: 920px;
        border-collapse: collapse;
    }

    .peserta-table th {
        padding: 17px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e8edf4;
        color: #64748b;
        font-size: 11px;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .peserta-table td {
        padding: 17px 16px;
        border-bottom: 1px solid #edf1f7;
        color: #17243b;
        font-size: 13px;
        vertical-align: middle;
    }

    .peserta-table tbody tr:hover {
        background: #f8fbff;
    }

    .peserta-table tbody tr:last-child td {
        border-bottom: none;
    }

    .peserta-identity strong {
        display: block;
        margin-bottom: 5px;
        color: #17243b;
        font-size: 13px;
    }

    .peserta-identity span {
        color: #64748b;
        font-size: 12px;
    }

    .peserta-program {
        display: inline-block;
        padding: 7px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .program-a {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .program-b {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .program-c {
        background: #dcfce7;
        color: #15803d;
    }

    .program-other {
        background: #f1f5f9;
        color: #64748b;
    }

    .peserta-status {
        display: inline-block;
        padding: 7px 10px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .peserta-status-unlinked {
        background: #fff7ed;
        color: #c2410c;
    }

    .peserta-address {
        min-width: 170px;
        max-width: 260px;
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.6;
        color: #64748b;
    }

    .peserta-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .peserta-actions form {
        margin: 0;
    }

    .peserta-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 15px;
        border: 0;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: .2s;
    }

    .peserta-btn-edit {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .peserta-btn-edit:hover {
        background: #dbeafe;
    }

    .peserta-btn-delete {
        background: #fff1f2;
        color: #dc2626;
    }

    .peserta-btn-delete:hover {
        background: #ffe4e6;
    }

    .peserta-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 9px;
        font-size: 13px;
        line-height: 1.7;
    }

    .peserta-alert-success {
        background: #dcfce7;
        color: #166534;
    }

    .peserta-alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    .peserta-alert ul {
        margin-bottom: 0;
    }

    .peserta-empty {
        padding: 35px 20px !important;
        text-align: center;
        color: #64748b !important;
    }

    .peserta-pagination {
        padding: 18px 24px;
        border-top: 1px solid #e8edf4;
    }

    .peserta-no-results {
        display: none;
        padding: 28px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
    }

    .peserta-search-info {
        padding: 0 24px 15px;
        color: #64748b;
        font-size: 12px;
    }

    @media (max-width: 650px) {
        .peserta-page-heading h2 {
            font-size: 24px;
        }

        .peserta-list-toolbar {
            padding: 18px;
        }

        .peserta-search {
            max-width: none;
        }

        .peserta-page-heading .primary-button {
            align-self: flex-start;
        }
    }
</style>

<div class="peserta-page-heading">
    <div>
        <h2>Data Peserta Didik</h2>
        <p>Kelola data peserta didik dan akun akses sistem absensi.</p>
    </div>

    <a href="{{ route('admin.peserta.create') }}" class="primary-button">
        + Tambah Peserta Didik
    </a>
</div>

@if (session('success'))
    <div class="peserta-alert peserta-alert-success" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="peserta-alert peserta-alert-error" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="peserta-alert peserta-alert-error" role="alert">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="peserta-list-card">
    <div class="peserta-list-toolbar">
        <div>
            <h3>Daftar Peserta Didik</h3>
            <p>Total {{ $pesertaDidik->total() }} peserta didik</p>
        </div>

        <input
            type="search"
            id="pesertaSearch"
            class="peserta-search"
            placeholder="Cari NIS, nama, atau email..."
            aria-label="Cari peserta didik"
        >
    </div>

    <div class="peserta-search-info" id="pesertaSearchInfo" aria-live="polite">
        Menampilkan data pada halaman ini.
    </div>

    <div class="peserta-table-wrap">
        <table class="peserta-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Identitas Peserta Didik</th>
                    <th>Program Paket</th>
                    <th>Status Akun</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="pesertaTableBody">
                @forelse ($pesertaDidik as $index => $peserta)
                    <tr class="peserta-row">
                        <td>{{ $pesertaDidik->firstItem() + $index }}</td>

                        <td>
                            <div class="peserta-identity">
                                <strong>{{ $peserta->nama }}</strong>
                                <span>
                                    NIS: {{ $peserta->nis }}
                                    ·
                                    {{ $peserta->user?->email ?? 'Email tidak tersedia' }}
                                </span>
                            </div>
                        </td>

                        <td>
                            @if ($peserta->program_paket === 'A')
                                <span class="peserta-program program-a">
                                    Paket A (Setara SD)
                                </span>
                            @elseif ($peserta->program_paket === 'B')
                                <span class="peserta-program program-b">
                                    Paket B (Setara SMP)
                                </span>
                            @elseif ($peserta->program_paket === 'C')
                                <span class="peserta-program program-c">
                                    Paket C (Setara SMA)
                                </span>
                            @else
                                <span class="peserta-program program-other">
                                    Belum dipilih
                                </span>
                            @endif
                        </td>

                        <td>
                            @if ($peserta->user)
                                <span class="peserta-status">Terdaftar</span>
                            @else
                                <span class="peserta-status peserta-status-unlinked">
                                    Akun tidak terhubung
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="peserta-address">
                                {{ $peserta->alamat ?: '-' }}
                            </div>
                        </td>

                        <td>
                            <div class="peserta-actions">
                                <a
                                    href="{{ route('admin.peserta.edit', $peserta->id) }}"
                                    class="peserta-btn peserta-btn-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.peserta.destroy', $peserta->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus peserta didik ini beserta akun loginnya?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="peserta-btn peserta-btn-delete"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="peserta-empty">
                            Belum ada data peserta didik.
                            <br><br>
                            <a
                                href="{{ route('admin.peserta.create') }}"
                                class="primary-button"
                            >
                                + Tambah Peserta Didik Pertama
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div id="pesertaNoResults" class="peserta-no-results">
            Tidak ada peserta didik yang cocok dengan pencarian.
        </div>
    </div>

    <div class="peserta-pagination" id="pesertaPagination">
        {{ $pesertaDidik->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('pesertaSearch');
        const rows = Array.from(document.querySelectorAll('.peserta-row'));
        const noResults = document.getElementById('pesertaNoResults');
        const pagination = document.getElementById('pesertaPagination');
        const searchInfo = document.getElementById('pesertaSearchInfo');

        if (!searchInput) return;

        searchInput.addEventListener('input', function () {
            const keyword = this.value.trim().toLocaleLowerCase('id');
            let visibleCount = 0;

            rows.forEach(function (row) {
                const matches = row.textContent
                    .toLocaleLowerCase('id')
                    .includes(keyword);

                row.style.display = matches ? '' : 'none';

                if (matches) visibleCount++;
            });

            noResults.style.display =
                keyword !== '' && visibleCount === 0 ? 'block' : 'none';

            if (keyword !== '') {
                searchInfo.textContent =
                    'Ditemukan ' + visibleCount +
                    ' peserta pada halaman ini.';

                pagination.style.display = 'none';
            } else {
                searchInfo.textContent =
                    'Menampilkan data pada halaman ini.';

                pagination.style.display = '';
            }
        });
    });
</script>

@endsection
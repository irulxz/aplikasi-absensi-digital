@extends('layouts.dashboard')

@section('content')
<style>
    .tutor-page {
        color: #172b4d;
    }

    .tutor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .tutor-header h1 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 700;
    }

    .tutor-header p {
        margin: 0;
        color: #718096;
        font-size: 14px;
    }

    .tutor-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border: 0;
        border-radius: 9px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .tutor-btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .tutor-btn-primary:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .tutor-btn-edit {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .tutor-btn-delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .tutor-panel {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .tutor-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .tutor-search {
        width: 100%;
        max-width: 340px;
        padding: 11px 13px;
        border: 1px solid #dce3ed;
        border-radius: 9px;
        outline: none;
        font-size: 14px;
    }

    .tutor-search:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .tutor-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .tutor-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 680px;
    }

    .tutor-table th {
        background: #f8fafc;
        color: #64748b;
        text-align: left;
        padding: 14px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #e8edf5;
    }

    .tutor-table td {
        padding: 15px 14px;
        border-bottom: 1px solid #edf1f7;
        font-size: 14px;
        vertical-align: middle;
    }

    .tutor-table tbody tr:hover {
        background: #fafcff;
    }

    .tutor-name {
        font-weight: 600;
        color: #1e293b;
    }

    .tutor-email {
        color: #64748b;
        margin-top: 4px;
        font-size: 12px;
    }

    .tutor-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
    }

    .tutor-actions {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .tutor-alert {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 18px;
        font-size: 14px;
    }

    .tutor-alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }

    .tutor-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .tutor-empty {
        text-align: center;
        padding: 45px 20px !important;
        color: #64748b;
    }

    .tutor-count {
        color: #64748b;
        font-size: 13px;
    }

    @media (max-width: 640px) {
        .tutor-panel {
            padding: 15px;
        }

        .tutor-header h1 {
            font-size: 22px;
        }

        .tutor-search {
            max-width: none;
        }
    }
</style>

<div class="tutor-page">
    <div class="tutor-header">
        <div>
            <h1>Data Tutor</h1>
            <p>Kelola data tutor dan akun akses sistem absensi.</p>
        </div>

        <a href="{{ route('admin.tutor.create') }}"
           class="tutor-btn tutor-btn-primary">
            <span>＋</span> Tambah Tutor
        </a>
    </div>

    @if (session('success'))
        <div class="tutor-alert tutor-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="tutor-alert tutor-alert-error">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tutor-panel">
        <div class="tutor-toolbar">
            <div>
                <strong>Daftar Tutor</strong>
                <div class="tutor-count">
                    Total {{ $tutors->count() }} tutor
                </div>
            </div>

            <input type="search"
                   id="tutorSearch"
                   class="tutor-search"
                   placeholder="Cari NIP, nama, atau email...">
        </div>

        <div class="tutor-table-wrap">
            <table class="tutor-table" id="tutorTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Identitas Tutor</th>
                        <th>NIP</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tutors as $tutor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="tutor-name">
                                    {{ $tutor->nama }}
                                </div>
                                <div class="tutor-email">
                                    {{ $tutor->user->email ?? 'Email tidak tersedia' }}
                                </div>
                            </td>

                            <td>{{ $tutor->nip }}</td>

                            <td>
                                <span class="tutor-badge">
                                    {{ $tutor->user ? 'Terdaftar' : 'Tanpa akun' }}
                                </span>
                            </td>

                            <td>
                                <div class="tutor-actions">
                                    <a href="{{ route('admin.tutor.edit', $tutor->id) }}"
                                       class="tutor-btn tutor-btn-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.tutor.destroy', $tutor->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tutor ini beserta akun loginnya?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="tutor-btn tutor-btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="tutor-empty">
                                <div style="font-size: 32px; margin-bottom: 10px;">
                                    👨‍🏫
                                </div>
                                <strong>Belum ada data tutor</strong>
                                <p style="margin: 8px 0 16px;">
                                    Tambahkan tutor pertama untuk mulai mengelola data.
                                </p>
                                <a href="{{ route('admin.tutor.create') }}"
                                   class="tutor-btn tutor-btn-primary">
                                    Tambah Tutor
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="tutorNoSearchResult"
                 class="tutor-empty"
                 style="display: none;">
                Data yang kamu cari tidak ditemukan.
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('tutorSearch');
        const table = document.getElementById('tutorTable');
        const rows = table.querySelectorAll('tbody tr');
        const noResult = document.getElementById('tutorNoSearchResult');

        search.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(function (row) {
                const matches = row.textContent.toLowerCase().includes(keyword);

                row.style.display = matches ? '' : 'none';

                if (matches) {
                    visibleCount++;
                }
            });

            noResult.style.display =
                keyword !== '' && visibleCount === 0 ? 'block' : 'none';
        });
    });
</script>
@endsection

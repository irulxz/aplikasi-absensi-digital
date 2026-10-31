 @extends('layouts.dashboard')

@section('title', 'Management Pengguna')
@section('page-title', 'Management Pengguna')

@section('content')

<style>
    .users-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .users-description {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.7;
    }

    .users-summary {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 12px;
    }

    .users-summary-icon {
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

    .users-summary-label {
        color: #64748b;
        font-size: 12px;
    }

    .users-summary-value {
        margin-top: 4px;
        color: #172033;
        font-size: 22px;
        font-weight: 800;
    }

    .users-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 9px;
        font-size: 13px;
        line-height: 1.6;
    }

    .users-alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .users-alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .users-table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 14px;
    }

    .users-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 22px;
        border-bottom: 1px solid #e8edf4;
    }

    .users-table-header h2 {
        margin: 0;
        color: #172033;
        font-size: 16px;
    }

    .users-table-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .users-table-card table {
        min-width: 760px;
    }

    .users-table-card td {
        vertical-align: middle;
    }

    .user-name {
        color: #1e293b;
        font-weight: 700;
    }

    .user-email {
        color: #64748b;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .role-admin {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .role-tutor {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .role-peserta {
        background: #dcfce7;
        color: #15803d;
    }

    .user-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-actions form {
        margin: 0;
    }

    .user-action-button {
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
        transition: .2s;
    }

    .user-edit-button {
        background: #fff7ed;
        color: #c2410c;
    }

    .user-edit-button:hover {
        background: #ffedd5;
    }

    .user-delete-button {
        background: #fff1f2;
        color: #be123c;
    }

    .user-delete-button:hover {
        background: #ffe4e6;
    }

    .users-empty {
        padding: 35px 20px !important;
        text-align: center !important;
        color: #64748b;
        white-space: normal;
    }

    @media (max-width: 600px) {
        .users-toolbar {
            align-items: stretch;
        }

        .users-toolbar .primary-button {
            align-self: flex-start;
        }

        .users-table-header {
            padding: 16px;
        }
    }
</style>

<div class="users-toolbar">
    <div>
        <p class="users-description">
            Kelola akun admin, tutor, dan peserta didik yang terdaftar
            dalam sistem Absensi Digital.
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}" class="primary-button">
        + Tambah Pengguna
    </a>
</div>

@if (session('success'))
    <div class="users-alert users-alert-success" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="users-alert users-alert-error" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="users-summary">
    <div class="users-summary-icon">♙</div>
    <div>
        <div class="users-summary-label">Total pengguna terdaftar</div>
        <div class="users-summary-value">{{ $users->count() }}</div>
    </div>
</div>

<div class="users-table-card">
    <div class="users-table-header">
        <div>
            <h2>Daftar Pengguna</h2>
            <p>Informasi akun dan pengaturan pengguna sistem.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <span class="user-name">
                                {{ $user->nama }}
                            </span>
                        </td>

                        <td>
                            <span class="user-email">
                                {{ $user->email }}
                            </span>
                        </td>

                        <td>
                            @php
                                $roleClass = match ($user->role) {
                                    'admin' => 'role-admin',
                                    'tutor' => 'role-tutor',
                                    'peserta_didik' => 'role-peserta',
                                    default => '',
                                };

                                $roleLabel = [
                                    'admin' => 'Admin',
                                    'tutor' => 'Tutor',
                                    'peserta_didik' => 'Peserta Didik',
                                ][$user->role] ?? $user->role;
                            @endphp

                            <span class="role-badge {{ $roleClass }}">
                                {{ $roleLabel }}
                            </span>
                        </td>

                        <td>
                            {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                        </td>

                        <td>
                            <div class="user-actions">
                                <a
                                    href="{{ route('admin.users.edit', $user->id) }}"
                                    class="user-action-button user-edit-button"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.users.destroy', $user->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="user-action-button user-delete-button"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="users-empty">
                            Belum ada pengguna yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
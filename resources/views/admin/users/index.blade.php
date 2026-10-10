<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Pengguna</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            color: #1e293b;
        }

        header {
            background: #1d4ed8;
            color: white;
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        header h2 {
            margin: 0;
        }

        main {
            padding: 32px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .description {
            color: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 700px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            background: #eff6ff;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .role {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
            font-size: 13px;
        }

        a {
            color: white;
            text-decoration: none;
        }

        .button {
            background: #2563eb;
            color: white;
            padding: 10px 14px;
            border-radius: 6px;
            display: inline-block;
            margin: 10px 0;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .edit-button {
            background: #d97706;
            color: white;
            padding: 7px 12px;
            border-radius: 6px;
            display: inline-block;
            font-size: 14px;
            border: none;
        }

        .edit-button:hover {
            background: #b45309;
        }

        .delete-button {
            background: #dc2626;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-button:hover {
            background: #b91c1c;
        }

        .logout {
            background: white;
            color: #1d4ed8;
            border: 0;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .logout:hover {
            background: #dbeafe;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 16px 0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 16px 0;
        }

        .total {
            margin-top: 18px;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-buttons form {
            margin: 0;
        }

        @media (max-width: 600px) {
            header {
                padding: 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            main {
                padding: 16px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <header>
        <h2>Management Pengguna</h2>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout">
                Logout
            </button>
        </form>
    </header>

    <main>
        <div class="card">
            <h2>Daftar Pengguna</h2>

            <p class="description">
                Berikut seluruh pengguna yang terdaftar di sistem.
            </p>

            {{-- Pesan berhasil --}}
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan gagal --}}
            @if (session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tombol tambah pengguna --}}
            <a
                href="{{ route('admin.users.create') }}"
                class="button"
            >
                + Tambah Pengguna
            </a>

            {{-- Total pengguna --}}
            <p class="total">
                Total pengguna:
                <strong>{{ $users->count() }}</strong>
            </p>

            {{-- Tabel pengguna --}}
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $user->nama }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="role">
                                    {{ $user->role }}
                                </span>
                            </td>

                            <td>
                                {{ $user->created_at?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td>
                                <div class="action-buttons">

                                    {{-- Tombol edit --}}
                                    <a
                                        href="{{ route('admin.users.edit', $user->id) }}"
                                        class="edit-button"
                                    >
                                        Edit
                                    </a>

                                    {{-- Form hapus --}}
                                    <form
                                        action="{{ route('admin.users.destroy', $user->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-button"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Belum ada pengguna yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
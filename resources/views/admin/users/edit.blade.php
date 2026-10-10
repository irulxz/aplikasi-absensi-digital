<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>

    <style>
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
        }

        main {
            max-width: 700px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
        }

        label {
            display: block;
            margin: 16px 0 8px;
            font-weight: bold;
        }

        input, select {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        button, .back {
            display: inline-block;
            margin-top: 22px;
            padding: 11px 16px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .back {
            background: #64748b;
            margin-left: 8px;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
        }

        .hint {
            color: #64748b;
            font-size: 13px;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <header>
        <h2>Edit Pengguna</h2>
    </header>

    <main>
        <div class="card">
            <h2>Ubah Data Pengguna</h2>

            @if ($errors->any())
                <div class="alert">
                    <strong>Periksa kembali input kamu:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <label for="nama">Nama</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $user->nama) }}"
                    required
                >

                @error('nama')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="password">Password Baru (Opsional)</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                >

                <p class="hint">
                    Kosongkan jika tidak ingin mengubah password.
                </p>

                <label for="password_confirmation">
                    Konfirmasi Password Baru
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    minlength="8"
                    autocomplete="new-password"
                >

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin"
                        @selected(old('role', $user->role) === 'admin')>
                        Admin
                    </option>

                    <option value="tutor"
                        @selected(old('role', $user->role) === 'tutor')>
                        Tutor
                    </option>

                    <option value="peserta_didik"
                        @selected(old('role', $user->role) === 'peserta_didik')>
                        Peserta Didik
                    </option>
                </select>

                @error('role')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit">Simpan Perubahan</button>

                <a href="{{ route('admin.users.index') }}" class="back">
                    Kembali
                </a>
            </form>
        </div>
    </main>
</body>
</html>
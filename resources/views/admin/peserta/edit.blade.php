@extends('layouts.dashboard')

@section('title', 'Edit Peserta Didik')
@section('page-title', 'Edit Peserta Didik')

@section('content')
    <div class="content-card" style="max-width:800px;">
        <h3>Edit Data Peserta Didik</h3>

        <p>Perbarui identitas dan informasi akun peserta didik.</p>

        @if ($errors->any())
            <div style="padding:14px; margin-bottom:20px; background:#fee2e2; color:#991b1b; border-radius:9px;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.peserta.update', $pesertaDidik->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Lengkap --}}
            <div style="margin-bottom:18px;">
                <label for="nama" style="display:block; margin-bottom:8px; font-weight:700;">
                    Nama Lengkap
                </label>

                <input type="text"
                       id="nama"
                       name="nama"
                       value="{{ old('nama', $pesertaDidik->nama) }}"
                       required
                       maxlength="255"
                       style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">
            </div>

            {{-- NIS --}}
            <div style="margin-bottom:18px;">
                <label for="nis" style="display:block; margin-bottom:8px; font-weight:700;">
                    NIS
                </label>

                <input type="text"
                       id="nis"
                       name="nis"
                       value="{{ old('nis', $pesertaDidik->nis) }}"
                       required
                       maxlength="255"
                       style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">
            </div>

            {{-- Alamat --}}
            <div style="margin-bottom:18px;">
                <label for="alamat" style="display:block; margin-bottom:8px; font-weight:700;">
                    Alamat
                </label>

                <textarea id="alamat"
                          name="alamat"
                          rows="3"
                          required
                          style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">{{ old('alamat', $pesertaDidik->alamat) }}</textarea>
            </div>

            {{-- Program Paket --}}
            <div style="margin-bottom:18px;">
                <label for="program_paket" style="display:block; margin-bottom:8px; font-weight:700;">
                    Program Paket
                </label>

                <select id="program_paket"
                        name="program_paket"
                        required
                        style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px; background:#fff;">

                    <option value="">-- Pilih Program Paket --</option>

                    <option value="A"
                        {{ old('program_paket', $pesertaDidik->program_paket) == 'A' ? 'selected' : '' }}>
                        Paket A (Setara SD)
                    </option>

                    <option value="B"
                        {{ old('program_paket', $pesertaDidik->program_paket) == 'B' ? 'selected' : '' }}>
                        Paket B (Setara SMP)
                    </option>

                    <option value="C"
                        {{ old('program_paket', $pesertaDidik->program_paket) == 'C' ? 'selected' : '' }}>
                        Paket C (Setara SMA)
                    </option>
                </select>

                <small style="display:block; margin-top:6px; color:#64748b;">
                    Pilih program kesetaraan yang diikuti peserta didik.
                </small>
            </div>

            <hr style="border:0; border-top:1px solid #e8edf4; margin:25px 0;">

            {{-- Informasi Akun Login --}}
            <h3>Informasi Akun Login</h3>

            {{-- Email --}}
            <div style="margin-bottom:18px;">
                <label for="email" style="display:block; margin-bottom:8px; font-weight:700;">
                    Email
                </label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $pesertaDidik->user?->email) }}"
                       required
                       maxlength="255"
                       style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">
            </div>

            {{-- Password Baru --}}
            <div style="margin-bottom:18px;">
                <label for="password" style="display:block; margin-bottom:8px; font-weight:700;">
                    Password Baru (Opsional)
                </label>

                <input type="password"
                       id="password"
                       name="password"
                       minlength="8"
                       autocomplete="new-password"
                       style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">

                <small style="display:block; margin-top:6px; color:#64748b;">
                    Kosongkan jika password tidak ingin diubah.
                </small>
            </div>

            {{-- Konfirmasi Password --}}
            <div style="margin-bottom:25px;">
                <label for="password_confirmation" style="display:block; margin-bottom:8px; font-weight:700;">
                    Konfirmasi Password Baru
                </label>

                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       minlength="8"
                       autocomplete="new-password"
                       style="width:100%; padding:12px; border:1px solid #dbe2ea; border-radius:8px;">
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="primary-button">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.peserta.index') }}"
                   class="secondary-button">
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
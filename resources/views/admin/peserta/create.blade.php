@extends('layouts.dashboard')

@section('title', 'Tambah Peserta Didik')
@section('page-title', 'Tambah Peserta Didik')

@section('content')
<div class="content-card" style="max-width:800px; margin:0 auto;">

    <h3>Formulir Peserta Didik</h3>
    <p style="color:#64748b; margin-bottom:24px;">
        Pilih akun peserta yang sudah dibuat melalui Manajemen Pengguna,
        kemudian lengkapi informasi peserta didik.
    </p>

    @if ($errors->any())
        <div style="padding:14px; margin-bottom:20px; background:#fee2e2; color:#991b1b; border-radius:9px;">
            <strong>Periksa kembali isian berikut:</strong>
            <ul style="margin:8px 0 0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.peserta.store') }}" method="POST">
        @csrf

        <h3 style="margin-bottom:6px;">Akun Peserta Didik</h3>
        <p style="color:#64748b; font-size:13px; margin-bottom:18px;">
            Hanya akun ber-role Peserta Didik yang belum memiliki data peserta
            yang ditampilkan.
        </p>

        {{-- Pilih akun yang sudah ada --}}
        <div style="margin-bottom:22px;">
            <label for="user_id" style="display:block; margin-bottom:8px; font-weight:700;">
                Pilih Akun Peserta Didik <span style="color:#dc2626;">*</span>
            </label>

            <select
                id="user_id"
                name="user_id"
                required
                {{ $users->isEmpty() ? 'disabled' : '' }}
                style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #dbe2ea; border-radius:8px; background:#fff;"
            >
                <option value="">-- Pilih akun peserta --</option>

                @foreach ($users as $user)
                    <option
                        value="{{ $user->id }}"
                        {{ old('user_id') == $user->id ? 'selected' : '' }}
                    >
                        {{ $user->nama }} — {{ $user->email }}
                    </option>
                @endforeach
            </select>

            @error('user_id')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror

            @if ($users->isEmpty())
                <small style="display:block; margin-top:8px; color:#64748b;">
                    Belum ada akun peserta yang tersedia. Buat akun dengan role
                    Peserta Didik melalui menu Manajemen Pengguna terlebih dahulu.
                </small>
            @endif
        </div>

        <hr style="border:0; border-top:1px solid #e8edf4; margin:25px 0;">

        <h3 style="margin-bottom:6px;">Informasi Peserta Didik</h3>
        <p style="color:#64748b; font-size:13px; margin-bottom:18px;">
            Lengkapi identitas peserta yang dipilih.
        </p>

        {{-- NIS --}}
        <div style="margin-bottom:18px;">
            <label for="nis" style="display:block; margin-bottom:8px; font-weight:700;">
                NIS <span style="color:#dc2626;">*</span>
            </label>

            <input
                type="text"
                id="nis"
                name="nis"
                value="{{ old('nis') }}"
                required
                maxlength="255"
                placeholder="Masukkan NIS"
                style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #dbe2ea; border-radius:8px;"
            >

            @error('nis')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        {{-- Alamat --}}
        <div style="margin-bottom:18px;">
            <label for="alamat" style="display:block; margin-bottom:8px; font-weight:700;">
                Alamat <span style="color:#dc2626;">*</span>
            </label>

            <textarea
                id="alamat"
                name="alamat"
                rows="3"
                required
                placeholder="Masukkan alamat peserta didik"
                style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #dbe2ea; border-radius:8px;"
            >{{ old('alamat') }}</textarea>

            @error('alamat')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        {{-- Program Paket --}}
        <div style="margin-bottom:25px;">
            <label for="program_paket" style="display:block; margin-bottom:8px; font-weight:700;">
                Program Paket <span style="color:#dc2626;">*</span>
            </label>

            <select
                id="program_paket"
                name="program_paket"
                required
                style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #dbe2ea; border-radius:8px; background:#fff;"
            >
                <option value="">-- Pilih Program Paket --</option>

                <option value="A" {{ old('program_paket') == 'A' ? 'selected' : '' }}>
                    Paket A (Setara SD)
                </option>

                <option value="B" {{ old('program_paket') == 'B' ? 'selected' : '' }}>
                    Paket B (Setara SMP)
                </option>

                <option value="C" {{ old('program_paket') == 'C' ? 'selected' : '' }}>
                    Paket C (Setara SMA)
                </option>
            </select>

            @error('program_paket')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror

            <small style="display:block; margin-top:7px; color:#64748b;">
                Pilih program kesetaraan yang diikuti peserta didik.
            </small>
        </div>

        {{-- Tombol --}}
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button
                type="submit"
                class="primary-button"
                {{ $users->isEmpty() ? 'disabled' : '' }}
            >
                Simpan Peserta Didik
            </button>

            <a
                href="{{ route('admin.peserta.index') }}"
                class="secondary-button"
            >
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'Tambah Tutor')
@section('page-title', 'Tambah Tutor')

@section('content')
<style>
    .tutor-form-page {
        max-width: 850px;
        margin: 0 auto;
        color: #172b4d;
    }

    .tutor-form-heading {
        margin-bottom: 22px;
    }

    .tutor-form-heading h1 {
        margin: 0 0 7px;
        font-size: 26px;
        font-weight: 700;
    }

    .tutor-form-heading p {
        margin: 0;
        color: #718096;
        font-size: 14px;
    }

    .tutor-form-card {
        padding: 28px;
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .tutor-form-section {
        margin-bottom: 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf1f7;
    }

    .tutor-form-section h3 {
        margin: 0 0 5px;
        font-size: 16px;
    }

    .tutor-form-section p {
        margin: 0;
        font-size: 13px;
        color: #718096;
    }

    .tutor-field {
        margin-bottom: 20px;
    }

    .tutor-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .tutor-field input,
    .tutor-field select {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 13px;
        border: 1px solid #dce3ed;
        border-radius: 9px;
        outline: none;
        font-size: 14px;
        background: #fff;
    }

    .tutor-field input:focus,
    .tutor-field select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .tutor-field .is-invalid {
        border-color: #ef4444;
    }

    .tutor-field small {
        display: block;
        margin-top: 7px;
        color: #718096;
        font-size: 12px;
    }

    .tutor-field-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .tutor-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 26px;
    }

    .tutor-form-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 18px;
        border-radius: 9px;
        border: 0;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .tutor-form-btn-primary {
        color: #fff;
        background: #2563eb;
    }

    .tutor-form-btn-primary:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .tutor-form-btn-secondary {
        color: #475569;
        background: #f1f5f9;
    }

    @media (max-width: 600px) {
        .tutor-form-card {
            padding: 18px;
        }

        .tutor-form-heading h1 {
            font-size: 22px;
        }
    }
</style>

<div class="tutor-form-page">
    <div class="tutor-form-heading">
        <h1>Tambah Tutor</h1>
        <p>Pilih akun yang sudah dibuat melalui Manajemen Pengguna, lalu lengkapi data tutornya.</p>
    </div>

    <div class="tutor-form-card">
        @if ($errors->any())
            <div style="background:#fef2f2;color:#b91c1c;padding:14px;border-radius:9px;margin-bottom:22px;">
                <strong>Periksa kembali isian berikut:</strong>
                <ul style="margin:8px 0 0;padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tutor.store') }}" method="POST">
            @csrf

            <div class="tutor-form-section">
                <h3>Akun Tutor</h3>
                <p>Hanya akun ber-role Tutor yang belum memiliki data tutor yang ditampilkan.</p>
            </div>

            <div class="tutor-field">
                <label for="user_id">
                    Pilih Akun Tutor <span style="color:#dc2626">*</span>
                </label>

                <select
                    id="user_id"
                    name="user_id"
                    class="{{ $errors->has('user_id') ? 'is-invalid' : '' }}"
                    required
                    {{ $users->isEmpty() ? 'disabled' : '' }}
                >
                    <option value="">-- Pilih akun Tutor --</option>

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
                    <div class="tutor-field-error">{{ $message }}</div>
                @enderror

                @if ($users->isEmpty())
                    <small>
                        Belum ada akun Tutor yang tersedia. Buat akun dengan role Tutor
                        melalui menu Manajemen Pengguna terlebih dahulu.
                    </small>
                @endif
            </div>

            <div class="tutor-form-section">
                <h3>Informasi Tutor</h3>
                <p>Lengkapi identitas tutor yang dipilih.</p>
            </div>

            <div class="tutor-field">
                <label for="nip">
                    NIP <span style="color:#dc2626">*</span>
                </label>

                <input
                    type="text"
                    id="nip"
                    name="nip"
                    value="{{ old('nip') }}"
                    class="{{ $errors->has('nip') ? 'is-invalid' : '' }}"
                    placeholder="Masukkan NIP"
                    required
                >

                @error('nip')
                    <div class="tutor-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="tutor-form-actions">
                <a
                    href="{{ route('admin.tutor.index') }}"
                    class="tutor-form-btn tutor-form-btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="tutor-form-btn tutor-form-btn-primary"
                    {{ $users->isEmpty() ? 'disabled' : '' }}
                >
                    Simpan Tutor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

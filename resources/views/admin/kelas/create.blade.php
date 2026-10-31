@extends('layouts.dashboard')

@section('title', 'Tambah Kelas dan Jadwal')
@section('page-title', 'Kelas dan Jadwal')

@section('content')

<style>
    .jadwal-form-page {
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }

    .jadwal-form-heading {
        margin-bottom: 22px;
    }

    .jadwal-form-heading h2 {
        margin: 0;
        color: #172033;
        font-size: 24px;
        font-weight: 800;
    }

    .jadwal-form-heading p {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.7;
    }

    .jadwal-form-alert {
        padding: 13px 16px;
        margin-bottom: 18px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 13px;
    }

    .jadwal-form-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .jadwal-form-card {
        padding: 25px;
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, .035);
    }

    .jadwal-form-card h3 {
        margin: 0 0 20px;
        color: #172033;
        font-size: 15px;
        font-weight: 800;
    }

    .jadwal-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 19px 20px;
    }

    .jadwal-form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .jadwal-form-group.full-width {
        grid-column: 1 / -1;
    }

    .jadwal-form-group label {
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-form-group input,
    .jadwal-form-group select {
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #dbe3ee;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #334155;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
        transition: border-color .2s, box-shadow .2s;
    }

    .jadwal-form-group input:focus,
    .jadwal-form-group select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .jadwal-form-group input.is-invalid,
    .jadwal-form-group select.is-invalid {
        border-color: #ef4444;
    }

    .jadwal-field-error {
        color: #dc2626;
        font-size: 11px;
    }

    .jadwal-form-footer {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 24px;
        padding-top: 19px;
        border-top: 1px solid #edf1f7;
    }

    .jadwal-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 39px;
        padding: 10px 17px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
    }

    .jadwal-btn-primary {
        background: #3b82f6;
        color: #fff;
    }

    .jadwal-btn-primary:hover {
        background: #2563eb;
    }

    .jadwal-btn-secondary {
        border-color: #dbe3ee;
        background: #fff;
        color: #475569;
    }

    .jadwal-btn-secondary:hover {
        background: #f8fafc;
    }

    @media (max-width: 650px) {
        .jadwal-form-heading h2 {
            font-size: 21px;
        }

        .jadwal-form-card {
            padding: 18px;
        }

        .jadwal-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .jadwal-form-group.full-width {
            grid-column: auto;
        }
    }
</style>

<div class="jadwal-form-page">

    <div class="jadwal-form-heading">
        <h2>Tambah Kelas dan Jadwal</h2>
        <p>
            Masukkan informasi mata pelajaran, tutor pengajar,
            dan waktu pembelajaran.
        </p>
    </div>

    @if ($errors->any())
        <div class="jadwal-form-alert" role="alert">
            <strong>Jadwal belum dapat disimpan.</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="jadwal-form-alert" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="jadwal-form-card">
        <h3>Informasi Jadwal</h3>

        <form
            action="{{ route('admin.kelas.store') }}"
            method="POST"
        >
            @csrf

            <div class="jadwal-form-grid">

                <div class="jadwal-form-group">
                    <label for="mata_pelajaran_id">
                        Mata Pelajaran
                    </label>

                    <select
                        name="mata_pelajaran_id"
                        id="mata_pelajaran_id"
                        class="{{ $errors->has('mata_pelajaran_id') ? 'is-invalid' : '' }}"
                        required
                    >
                        <option value="">Pilih mata pelajaran</option>

                        @foreach ($mataPelajarans as $mataPelajaran)
                            <option
                                value="{{ $mataPelajaran->id }}"
                                @selected(old('mata_pelajaran_id') == $mataPelajaran->id)
                            >
                                {{ $mataPelajaran->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('mata_pelajaran_id')
                        <span class="jadwal-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="jadwal-form-group">
                    <label for="tutor_id">Tutor Pengajar</label>

                    <select
                        name="tutor_id"
                        id="tutor_id"
                        class="{{ $errors->has('tutor_id') ? 'is-invalid' : '' }}"
                        required
                    >
                        <option value="">Pilih tutor</option>

                        @foreach ($tutors as $tutor)
                            <option
                                value="{{ $tutor->id }}"
                                @selected(old('tutor_id') == $tutor->id)
                            >
                                {{ $tutor->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('tutor_id')
                        <span class="jadwal-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="jadwal-form-group full-width">
                    <label for="hari">Hari Pembelajaran</label>

                    <select
                        name="hari"
                        id="hari"
                        class="{{ $errors->has('hari') ? 'is-invalid' : '' }}"
                        required
                    >
                        <option value="">Pilih hari</option>

                        @foreach ([
                            'Senin',
                            'Selasa',
                            'Rabu',
                            'Kamis',
                            'Jumat',
                            'Sabtu',
                            'Minggu'
                        ] as $hari)
                            <option
                                value="{{ $hari }}"
                                @selected(old('hari') == $hari)
                            >
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>

                    @error('hari')
                        <span class="jadwal-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="jadwal-form-group">
                    <label for="jam_mulai">Jam Mulai</label>

                    <input
                        type="time"
                        name="jam_mulai"
                        id="jam_mulai"
                        value="{{ old('jam_mulai') }}"
                        class="{{ $errors->has('jam_mulai') ? 'is-invalid' : '' }}"
                        required
                    >

                    @error('jam_mulai')
                        <span class="jadwal-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="jadwal-form-group">
                    <label for="jam_selesai">Jam Selesai</label>

                    <input
                        type="time"
                        name="jam_selesai"
                        id="jam_selesai"
                        value="{{ old('jam_selesai') }}"
                        class="{{ $errors->has('jam_selesai') ? 'is-invalid' : '' }}"
                        required
                    >

                    @error('jam_selesai')
                        <span class="jadwal-field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="jadwal-form-footer">
                <button type="submit" class="jadwal-btn jadwal-btn-primary">
                    Simpan Jadwal
                </button>

                <a
                    href="{{ route('admin.kelas.index') }}"
                    class="jadwal-btn jadwal-btn-secondary"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>

</div>

@endsection

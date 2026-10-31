@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3">Edit Kelas dan Jadwal</h1>
        <p class="text-muted">Perbarui informasi jadwal pembelajaran.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.kelas.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="mata_pelajaran_id" class="form-label">
                        Mata Pelajaran
                    </label>

                    <select
                        name="mata_pelajaran_id"
                        id="mata_pelajaran_id"
                        class="form-select @error('mata_pelajaran_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>

                        @foreach ($mataPelajarans as $mataPelajaran)
                            <option
                                value="{{ $mataPelajaran->id }}"
                                @selected(old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $mataPelajaran->id)
                            >
                                {{ $mataPelajaran->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('mata_pelajaran_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tutor_id" class="form-label">Tutor</label>

                    <select
                        name="tutor_id"
                        id="tutor_id"
                        class="form-select @error('tutor_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Tutor --</option>

                        @foreach ($tutors as $tutor)
                            <option
                                value="{{ $tutor->id }}"
                                @selected(old('tutor_id', $jadwal->tutor_id) == $tutor->id)
                            >
                                {{ $tutor->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('tutor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>

                    <select
                        name="hari"
                        id="hari"
                        class="form-select @error('hari') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Hari --</option>

                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                            <option
                                value="{{ $hari }}"
                                @selected(old('hari', $jadwal->hari) == $hari)
                            >
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>

                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>

                        <input
                            type="time"
                            name="jam_mulai"
                            id="jam_mulai"
                            value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}"
                            class="form-control @error('jam_mulai') is-invalid @enderror"
                            required
                        >

                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>

                        <input
                            type="time"
                            name="jam_selesai"
                            id="jam_selesai"
                            value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}"
                            class="form-control @error('jam_selesai') is-invalid @enderror"
                            required
                        >

                        @error('jam_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
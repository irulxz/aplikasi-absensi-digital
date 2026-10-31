@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    {{-- Sambutan Admin --}}
    <section class="welcome-card">
        <h2>Selamat datang, {{ auth()->user()->nama }}!</h2>

        <p>
            Anda login sebagai <strong>Administrator</strong>.
            Kelola data dan pantau aktivitas sistem Absensi Digital
            melalui menu yang tersedia di bawah ini.
        </p>
    </section>

    {{-- Menu Pengelolaan --}}
    <h2 class="section-title">Menu Pengelolaan</h2>

    <div class="card-grid">

        {{-- Manajemen Pengguna --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">👥</span>
                <h3>Manajemen Pengguna</h3>
            </div>

            <p>
                Kelola akun pengguna, perbarui informasi,
                atur hak akses, dan pantau akun yang terdaftar.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.users.index') }}"
                   class="primary-button">
                    Buka Manajemen Pengguna
                </a>
            </div>
        </div>

        {{-- Data Peserta Didik --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">🎓</span>
                <h3>Data Peserta Didik</h3>
            </div>

            <p>
                Lihat dan kelola data peserta didik
                yang terdaftar dalam sistem.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.peserta.index') }}"
                   class="primary-button">
                    Buka Data Peserta Didik
                </a>
            </div>
        </div>

        {{-- Data Tutor --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">👨‍🏫</span>
                <h3>Data Tutor</h3>
            </div>

            <p>
                Lihat dan kelola data tutor yang
                terdaftar dalam sistem.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.tutor.index') }}"
                   class="primary-button">
                    Buka Data Tutor
                </a>
            </div>
        </div>

        
        {{-- Kelas dan Jadwal --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">📚</span>
                <h3>Kelas dan Jadwal</h3>
            </div>

            <p>
                Kelola kelas dan jadwal pembelajaran
                sebagai dasar pelaksanaan absensi.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.kelas.index') }}"
                class="primary-button">
                    Buka Kelas dan Jadwal
                </a>
            </div>
        </div>

        {{-- Manajemen Absensi --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">📝</span>
                <h3>Manajemen Absensi</h3>
            </div>

            <p>
                Pantau data kehadiran peserta didik
                dan periksa catatan absensi yang tersimpan.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.absensi.index') }}"
                   class="primary-button">
                    Buka Manajemen Absensi
                </a>
            </div>
        </div>

        {{-- Rekap Absensi --}}
        <div class="content-card">
            <div class="card-heading">
                <span class="card-icon">📊</span>
                <h3>Rekap Absensi</h3>
            </div>

            <p>
                Akses ringkasan kehadiran untuk membantu
                pemantauan dan pelaporan absensi.
            </p>

            <div class="card-action">
                <a href="{{ route('admin.rekap.index') }}"
                   class="primary-button">
                    Buka Rekap Absensi
                </a>
            </div>
        </div>

    </div>
@endsection
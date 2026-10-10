@extends('layouts.dashboard')

@section('title', 'Dashboard Tutor')
@section('page-title', 'Dashboard Tutor')

@section('content')
    <section class="welcome-card">
        <h2>Selamat datang, {{ auth()->user()->nama }}!</h2>

        <p>
            Anda login sebagai <strong>Tutor</strong>.
            Dashboard ini disiapkan untuk mendukung aktivitas
            tutor dalam aplikasi Absensi Digital.
        </p>
    </section>

    <h2 class="section-title">Menu Aktivitas</h2>

    <div class="card-grid">
        <div class="content-card">
            <h3>Dashboard Tutor</h3>

            <p>
                Anda berhasil masuk menggunakan akun tutor.
                Menu dan fitur khusus tutor dapat dikembangkan
                sesuai kebutuhan aplikasi.
            </p>

            <span class="secondary-button">
                Halaman aktif
            </span>
        </div>

        <div class="content-card">
            <h3>Pengelolaan Absensi</h3>

            <p>
                Fitur untuk melihat atau mengelola absensi
                belum tersedia pada tahap ini.
            </p>

            <span class="secondary-button">
                Belum tersedia
            </span>
        </div>
    </div>
@endsection
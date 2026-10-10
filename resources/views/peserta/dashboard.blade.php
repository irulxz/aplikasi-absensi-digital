@extends('layouts.dashboard')

@section('title', 'Dashboard Peserta Didik')
@section('page-title', 'Dashboard Peserta Didik')

@section('content')
    <section class="welcome-card">
        <h2>Selamat datang, {{ auth()->user()->nama }}!</h2>

        <p>
            Anda login sebagai <strong>Peserta Didik</strong>.
            Dashboard ini disiapkan untuk mendukung aktivitas
            peserta didik dalam aplikasi Absensi Digital.
        </p>
    </section>

    <h2 class="section-title">Menu Aktivitas</h2>

    <div class="card-grid">
        <div class="content-card">
            <h3>Dashboard Peserta Didik</h3>

            <p>
                Anda berhasil masuk menggunakan akun peserta didik.
                Fitur khusus peserta didik dapat dikembangkan
                sesuai kebutuhan aplikasi.
            </p>

            <span class="secondary-button">
                Halaman aktif
            </span>
        </div>

        <div class="content-card">
            <h3>Informasi Absensi</h3>

            <p>
                Fitur untuk melihat riwayat dan informasi absensi
                belum tersedia pada tahap ini.
            </p>

            <span class="secondary-button">
                Belum tersedia
            </span>
        </div>
    </div>
@endsection
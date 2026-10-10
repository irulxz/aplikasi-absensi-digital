@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <section class="welcome-card">
        <h2>Selamat datang, {{ auth()->user()->nama }}!</h2>

        <p>
            Anda login sebagai <strong>Administrator</strong>.
            Kelola pengguna dan sistem absensi digital melalui
            menu yang tersedia pada dashboard ini.
        </p>
    </section>

    <h2 class="section-title">Menu Pengelolaan</h2>

    <div class="card-grid">
        <div class="content-card">
            <h3>Management Pengguna</h3>

            <p>
                Lihat daftar pengguna, tambahkan akun baru,
                ubah informasi pengguna, atur role, dan hapus
                akun yang tidak diperlukan.
            </p>

            <a href="{{ route('admin.users.index') }}"
               class="primary-button">
                Buka Management Pengguna
            </a>
        </div>

        <div class="content-card">
            <h3>Management Absensi</h3>

            <p>
                Menu pengelolaan dan pemantauan data absensi
                akan dikembangkan pada tahap berikutnya.
            </p>

            <span class="secondary-button">
                Belum tersedia
            </span>
        </div>
    </div>
@endsection
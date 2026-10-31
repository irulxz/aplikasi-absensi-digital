<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaDidikController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\TutorDashboardController;
use App\Http\Controllers\TutorJadwalController;
use App\Http\Controllers\TutorAbsensiController;
use App\Http\Controllers\PesertaDashboardController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('role:admin')->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard Tutor
|--------------------------------------------------------------------------
*/

Route::get('/tutor/dashboard', [
    TutorDashboardController::class,
    'index'
])->middleware('role:tutor')->name('tutor.dashboard');

/*
|--------------------------------------------------------------------------
| Dashboard Peserta Didik
|--------------------------------------------------------------------------
*/

Route::get('/peserta/dashboard', function () {
    return view('peserta.dashboard');
})->middleware('role:peserta_didik')->name('peserta.dashboard');

/*
|--------------------------------------------------------------------------
| Menu Admin
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')
    ->prefix('admin')
    ->group(function () {

        // Manajemen Pengguna
        Route::get('/users', [UserController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('admin.users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('admin.users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('admin.users.destroy');

        // Data Peserta Didik
        Route::resource(
            '/peserta-didik',
            PesertaDidikController::class
        )->parameters([
            'peserta-didik' => 'pesertaDidik',
        ])->names([
            'index' => 'admin.peserta.index',
            'create' => 'admin.peserta.create',
            'store' => 'admin.peserta.store',
            'show' => 'admin.peserta.show',
            'edit' => 'admin.peserta.edit',
            'update' => 'admin.peserta.update',
            'destroy' => 'admin.peserta.destroy',
        ]);

        // Data Tutor
        Route::resource('/tutor', TutorController::class)
            ->except(['show'])
            ->names([
                'index' => 'admin.tutor.index',
                'create' => 'admin.tutor.create',
                'store' => 'admin.tutor.store',
                'edit' => 'admin.tutor.edit',
                'update' => 'admin.tutor.update',
                'destroy' => 'admin.tutor.destroy',
            ]);

        // Kelas dan Jadwal
        Route::resource('/kelas', JadwalController::class)
            ->except(['show'])
            ->parameters([
                'kelas' => 'jadwal',
            ])
            ->names([
                'index' => 'admin.kelas.index',
                'create' => 'admin.kelas.create',
                'store' => 'admin.kelas.store',
                'edit' => 'admin.kelas.edit',
                'update' => 'admin.kelas.update',
                'destroy' => 'admin.kelas.destroy',
            ]);

        // Manajemen Absensi
        Route::get('/absensi', [AbsensiController::class, 'index'])
            ->name('admin.absensi.index');

        // Rekap Absensi Admin
        Route::get('/rekap-absensi/export', [
            AbsensiController::class,
            'export'
        ])->name('admin.rekap.export');

        Route::get('/rekap-absensi', [
            AbsensiController::class,
            'rekap'
        ])->name('admin.rekap.index');
    });

/*
|--------------------------------------------------------------------------
| Menu Tutor
|--------------------------------------------------------------------------
*/

Route::middleware('role:tutor')
    ->prefix('tutor')
    ->group(function () {

        // Jadwal Mengajar
        Route::get('/jadwal', [
            TutorJadwalController::class,
            'index'
        ])->name('tutor.jadwal.index');

        // Daftar Jadwal untuk Input Absensi
        Route::get('/absensi', [
            TutorAbsensiController::class,
            'index'
        ])->name('tutor.absensi.index');

        // Form Input Absensi
        Route::get('/absensi/{jadwal}/input', [
            TutorAbsensiController::class,
            'create'
        ])->name('tutor.absensi.create');

        // Simpan Absensi
        Route::post('/absensi/{jadwal}/simpan', [
            TutorAbsensiController::class,
            'store'
        ])->name('tutor.absensi.store');

        // Rekap Absensi Tutor
        Route::get('/rekap-absensi', [
            TutorAbsensiController::class,
            'rekap'
        ])->name('tutor.rekap.index');
    });

/*
|--------------------------------------------------------------------------
| Menu Peserta Didik
|--------------------------------------------------------------------------
*/

Route::middleware('role:peserta_didik')
    ->prefix('peserta')
    ->group(function () {

        // Profil peserta didik
        Route::view('/profil', 'peserta.menu-placeholder', [
            'judul' => 'Profil Saya',
            'deskripsi' => 'Informasi akun dan data diri peserta didik.',
        ])->name('peserta.profil.index');

        // Dashboard peserta didik
        Route::get('/dashboard', [
            PesertaDashboardController::class,
            'index'
        ])->name('peserta.dashboard');

    });

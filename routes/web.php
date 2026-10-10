
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Dashboard khusus admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('role:admin')->name('admin.dashboard');

// Dashboard khusus tutor
Route::get('/tutor/dashboard', function () {
    return view('tutor.dashboard');
})->middleware('role:tutor')->name('tutor.dashboard');

// Dashboard khusus peserta didik
Route::get('/peserta/dashboard', function () {
    return view('peserta.dashboard');
})->middleware('role:peserta_didik')->name('peserta.dashboard');

// Management pengguna khusus admin
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/users/create', [UserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/admin/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
});

Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
    ->middleware('role:admin')
    ->name('admin.users.destroy');
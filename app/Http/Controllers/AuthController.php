<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna berdasarkan email dan password.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        // Memperbarui session setelah login berhasil.
        $request->session()->regenerate();

        $user = Auth::user();

        // Mengarahkan pengguna sesuai role.
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'tutor':
                return redirect()->route('tutor.dashboard');

            case 'peserta_didik':
                return redirect()->route('peserta.dashboard');

            default:
                // Menolak role yang tidak dikenali.
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Role pengguna tidak dikenali.',
                    ]);
        }
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Kamu berhasil logout.');
    }
}
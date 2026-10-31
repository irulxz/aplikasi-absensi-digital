<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PesertaDidik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PesertaDidikController extends Controller
{
    /**
     * Menampilkan daftar peserta didik.
     */
    public function index()
    {
        $pesertaDidik = PesertaDidik::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.peserta.index', compact('pesertaDidik'));
    }

    /**
     * Menampilkan formulir tambah peserta didik.
     */
    
    public function create()
    {
        $users = User::where('role', 'peserta_didik')
            ->whereDoesntHave('pesertaDidik')
            ->orderBy('nama')
            ->get();

        return view('admin.peserta.create', compact('users'));
    }


    /**
     * Menyimpan peserta didik dan akun pengguna.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'peserta_didik'),
                Rule::unique('peserta_didik', 'user_id'),
            ],
            'nis' => [
                'required',
                'string',
                'max:255',
                'unique:peserta_didik,nis',
            ],
            'alamat' => ['required', 'string'],
            'program_paket' => [
                'required',
                Rule::in(['A', 'B', 'C']),
            ],
        ]);

        $user = User::findOrFail($validated['user_id']);

        PesertaDidik::create([
            'user_id' => $user->id,
            'nis' => $validated['nis'],
            'nama' => $user->nama,
            'alamat' => $validated['alamat'],
            'program_paket' => $validated['program_paket'],
        ]);

        return redirect()
            ->route('admin.peserta.index')
            ->with('success', 'Data peserta didik berhasil ditambahkan.');
    }


    /**
     * Menampilkan formulir edit peserta didik.
     */
    public function edit(PesertaDidik $pesertaDidik)
    {
        $pesertaDidik->load('user');

        return view('admin.peserta.edit', compact('pesertaDidik'));
    }

    /**
     * Memperbarui data peserta didik.
     */
    public function update(Request $request, PesertaDidik $pesertaDidik)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => [
                'required',
                'string',
                'max:255',
                Rule::unique('peserta_didik', 'nis')
                    ->ignore($pesertaDidik->id),
            ],
            'alamat' => ['required', 'string'],
            'program_paket' => [
                'required',
                Rule::in(['A', 'B', 'C']),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($pesertaDidik->user_id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        DB::transaction(function () use ($validated, $pesertaDidik) {
            $pesertaDidik->update([
                'nama' => $validated['nama'],
                'nis' => $validated['nis'],
                'alamat' => $validated['alamat'],
                'program_paket' => $validated['program_paket'],
            ]);

            $user = User::findOrFail($pesertaDidik->user_id);

            $user->nama = $validated['nama'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
        });

        return redirect()
            ->route('admin.peserta.index')
            ->with('success', 'Data peserta didik berhasil diperbarui.');
    }

    /**
     * Menghapus peserta didik beserta akun penggunanya.
     */
    public function destroy(PesertaDidik $pesertaDidik)
    {
        DB::transaction(function () use ($pesertaDidik) {
            $userId = $pesertaDidik->user_id;

            $pesertaDidik->delete();

            User::where('id', $userId)
                ->where('role', 'peserta_didik')
                ->delete();
        });

        return redirect()
            ->route('admin.peserta.index')
            ->with('success', 'Data peserta didik berhasil dihapus.');
    }
}
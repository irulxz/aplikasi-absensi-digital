<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TutorController extends Controller
{
    public function index()
    {
        $tutors = Tutor::with('user')->latest()->get();

        return view('admin.tutor.index', compact('tutors'));
    }

    public function create()
    {
        $users = User::where('role', 'tutor')
            ->whereDoesntHave('tutor')
            ->orderBy('nama')
            ->get();

        return view('admin.tutor.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'tutor'),
                Rule::unique('tutors', 'user_id'),
            ],
            'nip' => ['required', 'string', 'max:255', 'unique:tutors,nip'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        Tutor::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'],
            'nama' => $user->nama,
        ]);

        return redirect()
            ->route('admin.tutor.index')
            ->with('success', 'Data tutor berhasil ditambahkan.');
    }

    public function edit(Tutor $tutor)
    {
        $tutor->load('user');

        return view('admin.tutor.edit', compact('tutor'));
    }

    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'nip' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tutors', 'nip')->ignore($tutor->id),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($tutor->user_id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $tutor) {
            $tutor->update([
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
            ]);

            $userData = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $tutor->user->update($userData);
        });

        return redirect()
            ->route('admin.tutor.index')
            ->with('success', 'Data tutor berhasil diperbarui.');
    }

    public function destroy(Tutor $tutor)
    {
        DB::transaction(function () use ($tutor) {
            $user = $tutor->user;

            $tutor->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.tutor.index')
            ->with('success', 'Data tutor berhasil dihapus.');
    }
}

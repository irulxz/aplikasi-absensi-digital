<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Tutor;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $jadwals = Jadwal::with([
            'mataPelajaran',
            'tutor',
        ])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('hari', 'like', "%{$search}%")
                        ->orWhereHas('mataPelajaran', function (Builder $sub) use ($search) {
                            $sub->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('tutor', function (Builder $sub) use ($search) {
                            $sub->where('nama', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByRaw(
                "FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')"
            )
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.kelas.index', compact('jadwals', 'search'));
    }

    public function create()
    {
        $tutors = Tutor::orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();

        return view('admin.kelas.create', compact(
            'tutors',
            'mataPelajarans'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_pelajaran_id' => [
                'required',
                'exists:mata_pelajarans,id',
            ],
            'tutor_id' => [
                'required',
                'exists:tutors,id',
            ],
            'hari' => [
                'required',
                Rule::in([
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu',
                ]),
            ],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
        ]);

        Jadwal::create($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $tutors = Tutor::orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();

        return view('admin.kelas.edit', compact(
            'jadwal',
            'tutors',
            'mataPelajarans'
        ));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'mata_pelajaran_id' => [
                'required',
                'exists:mata_pelajarans,id',
            ],
            'tutor_id' => [
                'required',
                'exists:tutors,id',
            ],
            'hari' => [
                'required',
                Rule::in([
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu',
                ]),
            ],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
        ]);

        $jadwal->update($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
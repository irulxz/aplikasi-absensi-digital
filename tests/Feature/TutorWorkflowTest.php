<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\PesertaDidik;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TutorWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function seedTutorScenario(): array
    {
        $tutorUser1 = User::factory()->create(['nama' => 'Tutor Satu QA', 'role' => 'tutor']);
        $tutor1 = Tutor::create(['user_id' => $tutorUser1->id, 'nip' => 'T-QA-01', 'nama' => 'Tutor Satu QA']);

        $tutorUser2 = User::factory()->create(['nama' => 'Tutor Dua QA', 'role' => 'tutor']);
        $tutor2 = Tutor::create(['user_id' => $tutorUser2->id, 'nip' => 'T-QA-02', 'nama' => 'Tutor Dua QA']);

        $mapel = MataPelajaran::create(['nama' => 'IPA Terpadu QA', 'tingkat' => 'Paket B']);

        $jadwal1 = new Jadwal([
            'mata_pelajaran_id' => $mapel->id,
            'tutor_id' => $tutor1->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        $jadwal1->program_paket = 'B';
        $jadwal1->save();

        $jadwal2 = new Jadwal([
            'mata_pelajaran_id' => $mapel->id,
            'tutor_id' => $tutor2->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        $jadwal2->program_paket = 'B';
        $jadwal2->save();

        $pesertaUserB = User::factory()->create(['nama' => 'Siswa Paket B', 'role' => 'peserta_didik']);
        $pesertaB = PesertaDidik::create([
            'user_id' => $pesertaUserB->id,
            'nis' => 'NIS-B-01',
            'nama' => 'Siswa Paket B',
            'alamat' => 'Jl. Paket B',
            'program_paket' => 'B',
        ]);

        $pesertaUserC = User::factory()->create(['nama' => 'Siswa Paket C', 'role' => 'peserta_didik']);
        $pesertaC = PesertaDidik::create([
            'user_id' => $pesertaUserC->id,
            'nis' => 'NIS-C-01',
            'nama' => 'Siswa Paket C',
            'alamat' => 'Jl. Paket C',
            'program_paket' => 'C',
        ]);

        return compact('tutorUser1', 'tutor1', 'tutorUser2', 'tutor2', 'jadwal1', 'jadwal2', 'pesertaB', 'pesertaC');
    }

    public function test_tutor_dapat_melihat_dashboard_jadwal_dan_rekap(): void
    {
        $data = $this->seedTutorScenario();

        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.dashboard'))
            ->assertStatus(200)
            ->assertSee('Tutor Satu QA')
            ->assertSee('IPA Terpadu QA');

        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.jadwal.index'))
            ->assertStatus(200)
            ->assertSee('IPA Terpadu QA');

        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.absensi.index'))
            ->assertStatus(200)
            ->assertSee('IPA Terpadu QA');
    }

    public function test_tutor_ditolak_403_saat_mengakses_jadwal_tutor_lain_atau_di_luar_jam(): void
    {
        $data = $this->seedTutorScenario();

        // Di dalam jam belajar, tapi milik tutor lain -> 403
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.absensi.create', $data['jadwal2']))
            ->assertStatus(403);

        $this->actingAs($data['tutorUser1'])
            ->post(route('tutor.absensi.store', $data['jadwal2']), [
                'absensi' => [$data['pesertaB']->id => 'Hadir'],
            ])
            ->assertStatus(403);

        // Jadwal milik sendiri, tapi di luar jam belajar -> 403
        Carbon::setTestNow(Carbon::parse('2026-11-25 10:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.absensi.create', $data['jadwal1']))
            ->assertStatus(403);

        $this->actingAs($data['tutorUser1'])
            ->post(route('tutor.absensi.store', $data['jadwal1']), [
                'absensi' => [$data['pesertaB']->id => 'Hadir'],
            ])
            ->assertStatus(403);
    }

    public function test_tutor_simpan_dan_perbarui_absensi_serta_validasi_paket_dan_status(): void
    {
        $data = $this->seedTutorScenario();
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta'));

        // Form input tampil
        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.absensi.create', $data['jadwal1']))
            ->assertStatus(200)
            ->assertSee('Siswa Paket B')
            ->assertDontSee('Siswa Paket C');

        // Status tidak valid -> validation error
        $this->actingAs($data['tutorUser1'])
            ->from(route('tutor.absensi.create', $data['jadwal1']))
            ->post(route('tutor.absensi.store', $data['jadwal1']), [
                'absensi' => [$data['pesertaB']->id => 'Bolos'],
            ])
            ->assertSessionHasErrors('absensi.' . $data['pesertaB']->id);

        // Peserta berbeda program_paket -> 422
        $this->actingAs($data['tutorUser1'])
            ->post(route('tutor.absensi.store', $data['jadwal1']), [
                'absensi' => [$data['pesertaC']->id => 'Hadir'],
            ])
            ->assertStatus(422);

        // Simpan absensi valid
        $this->actingAs($data['tutorUser1'])
            ->post(route('tutor.absensi.store', $data['jadwal1']), [
                'absensi' => [$data['pesertaB']->id => 'Izin'],
            ])
            ->assertRedirect(route('tutor.absensi.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('absensis', [
            'peserta_didik_id' => $data['pesertaB']->id,
            'jadwal_id' => $data['jadwal1']->id,
            'tanggal' => '2026-11-25',
            'status' => 'Izin',
        ]);

        // UpdateOrCreate pada hari & jadwal sama tidak menduplikasi baris
        $this->actingAs($data['tutorUser1'])
            ->post(route('tutor.absensi.store', $data['jadwal1']), [
                'absensi' => [$data['pesertaB']->id => 'Hadir'],
            ])
            ->assertRedirect(route('tutor.absensi.index'));

        $this->assertSame(1, Absensi::where([
            'peserta_didik_id' => $data['pesertaB']->id,
            'jadwal_id' => $data['jadwal1']->id,
            'tanggal' => '2026-11-25',
        ])->count());

        $this->assertDatabaseHas('absensis', [
            'peserta_didik_id' => $data['pesertaB']->id,
            'jadwal_id' => $data['jadwal1']->id,
            'tanggal' => '2026-11-25',
            'status' => 'Hadir',
        ]);

        // Rekap Tutor menampilkan catatan tersebut
        $this->actingAs($data['tutorUser1'])
            ->get(route('tutor.rekap.index'))
            ->assertStatus(200)
            ->assertSee('Siswa Paket B');
    }
}

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

class PesertaAbsensiAndRiwayatTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function seedPesertaScenario(): array
    {
        $pesertaUserA = User::factory()->create(['nama' => 'Rina Peserta A', 'role' => 'peserta_didik']);
        $pesertaA = PesertaDidik::create([
            'user_id' => $pesertaUserA->id,
            'nis' => 'NIS-QA-A1',
            'nama' => 'Rina Peserta A',
            'alamat' => 'Jl. Merdeka',
            'program_paket' => 'A',
        ]);

        $pesertaUserC = User::factory()->create(['nama' => 'Doni Peserta C', 'role' => 'peserta_didik']);
        $pesertaC = PesertaDidik::create([
            'user_id' => $pesertaUserC->id,
            'nis' => 'NIS-QA-C1',
            'nama' => 'Doni Peserta C',
            'alamat' => 'Jl. Sudirman',
            'program_paket' => 'C',
        ]);

        $tutorUser = User::factory()->create(['nama' => 'Tutor Absensi QA', 'role' => 'tutor']);
        $tutor = Tutor::create(['user_id' => $tutorUser->id, 'nip' => 'NIP-QA-999', 'nama' => 'Tutor Absensi QA']);

        $mapelA = MataPelajaran::create(['nama' => 'PKn Paket A QA', 'tingkat' => 'Paket A']);
        $mapelC = MataPelajaran::create(['nama' => 'Ekonomi Paket C QA', 'tingkat' => 'Paket C']);

        $jadwalA = new Jadwal([
            'mata_pelajaran_id' => $mapelA->id,
            'tutor_id' => $tutor->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        $jadwalA->program_paket = 'A';
        $jadwalA->save();

        $jadwalC = new Jadwal([
            'mata_pelajaran_id' => $mapelC->id,
            'tutor_id' => $tutor->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        $jadwalC->program_paket = 'C';
        $jadwalC->save();

        return compact('pesertaUserA', 'pesertaA', 'pesertaUserC', 'pesertaC', 'tutorUser', 'jadwalA', 'jadwalC');
    }

    public function test_dashboard_dan_jadwal_peserta_menampilkan_data_sesuai_paket(): void
    {
        $data = $this->seedPesertaScenario();

        Absensi::create([
            'peserta_didik_id' => $data['pesertaA']->id,
            'jadwal_id' => $data['jadwalA']->id,
            'tanggal' => '2026-11-18',
            'status' => 'Hadir',
        ]);
        Absensi::create([
            'peserta_didik_id' => $data['pesertaA']->id,
            'jadwal_id' => $data['jadwalA']->id,
            'tanggal' => '2026-11-11',
            'status' => 'Izin',
        ]);

        // Dashboard menghitung 1 Hadir dari 2 catatan = 50%
        $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.dashboard'))
            ->assertStatus(200)
            ->assertSee('Rina Peserta A')
            ->assertSee('50%');

        // Jadwal Belajar hanya menampilkan jadwal Paket A, tidak Paket C
        $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.jadwal.index'))
            ->assertStatus(200)
            ->assertSee('PKn Paket A QA')
            ->assertDontSee('Ekonomi Paket C QA');
    }

    public function test_peserta_absensi_index_menampilkan_jadwal_hari_ini_sesuai_paket(): void
    {
        $data = $this->seedPesertaScenario();
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta')); // Rabu

        $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.absensi.index'))
            ->assertStatus(200)
            ->assertSee('PKn Paket A QA')
            ->assertSee('Absen Hadir')
            ->assertDontSee('Ekonomi Paket C QA');
    }

    public function test_peserta_absensi_store_berhasil_dan_boundary_jam_belajar(): void
    {
        $data = $this->seedPesertaScenario();

        // Tepat jam_mulai (08:00:00) -> berhasil
        Carbon::setTestNow(Carbon::parse('2026-11-25 08:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserA'])
            ->from(route('peserta.absensi.index'))
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertRedirect(route('peserta.absensi.index'))
            ->assertSessionHas('success', 'Absensi hadir berhasil dicatat!');

        $this->assertDatabaseHas('absensis', [
            'peserta_didik_id' => $data['pesertaA']->id,
            'jadwal_id' => $data['jadwalA']->id,
            'tanggal' => '2026-11-25',
            'status' => 'Hadir',
        ]);

        // Tepat jam_selesai (10:00:00) untuk peserta lain Paket C -> berhasil (operator <=)
        Carbon::setTestNow(Carbon::parse('2026-11-25 10:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserC'])
            ->from(route('peserta.absensi.index'))
            ->post(route('peserta.absensi.store', $data['jadwalC']->id))
            ->assertRedirect(route('peserta.absensi.index'))
            ->assertSessionHas('success', 'Absensi hadir berhasil dicatat!');
    }

    public function test_peserta_absensi_store_ditolak_di_luar_hari_jam_atau_lintas_paket(): void
    {
        $data = $this->seedPesertaScenario();

        // Sebelum jam_mulai (07:59:59) -> 403
        Carbon::setTestNow(Carbon::parse('2026-11-25 07:59:59', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserA'])
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertStatus(403);

        // Setelah jam_selesai (10:00:01) -> 403
        Carbon::setTestNow(Carbon::parse('2026-11-25 10:00:01', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserA'])
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertStatus(403);

        // Hari berbeda (Selasa) -> 403
        Carbon::setTestNow(Carbon::parse('2026-11-24 09:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserA'])
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertStatus(403);

        // Lintas program_paket -> 404
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta'));
        $this->actingAs($data['pesertaUserA'])
            ->post(route('peserta.absensi.store', $data['jadwalC']->id))
            ->assertStatus(404);

        // Role Tutor tidak boleh akses endpoint peserta -> 403
        $this->actingAs($data['tutorUser'])
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertStatus(403);
    }

    public function test_peserta_tidak_dapat_absen_ganda_atau_menimpa_status_tutor(): void
    {
        $data = $this->seedPesertaScenario();
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta'));

        // Absensi pertama berhasil
        $this->actingAs($data['pesertaUserA'])
            ->from(route('peserta.absensi.index'))
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertSessionHas('success');

        // Percobaan absensi kedua pada hari & jadwal sama ditolak dengan error flash
        $this->actingAs($data['pesertaUserA'])
            ->from(route('peserta.absensi.index'))
            ->post(route('peserta.absensi.store', $data['jadwalA']->id))
            ->assertRedirect(route('peserta.absensi.index'))
            ->assertSessionHas('error', 'Absensi untuk jadwal ini sudah tercatat hari ini.');

        $this->assertSame(1, Absensi::where([
            'peserta_didik_id' => $data['pesertaA']->id,
            'jadwal_id' => $data['jadwalA']->id,
            'tanggal' => '2026-11-25',
        ])->count());

        // Jika Tutor sudah mencatat status Izin untuk Peserta C, absensi mandiri tidak menimpanya
        Absensi::create([
            'peserta_didik_id' => $data['pesertaC']->id,
            'jadwal_id' => $data['jadwalC']->id,
            'tanggal' => '2026-11-25',
            'status' => 'Izin',
        ]);

        $this->actingAs($data['pesertaUserC'])
            ->from(route('peserta.absensi.index'))
            ->post(route('peserta.absensi.store', $data['jadwalC']->id))
            ->assertRedirect(route('peserta.absensi.index'))
            ->assertSessionHas('error', 'Absensi untuk jadwal ini sudah tercatat hari ini.');

        $this->assertDatabaseHas('absensis', [
            'peserta_didik_id' => $data['pesertaC']->id,
            'jadwal_id' => $data['jadwalC']->id,
            'tanggal' => '2026-11-25',
            'status' => 'Izin',
        ]);
    }

    public function test_riwayat_absensi_peserta_terisolasi_terurut_dan_terpaginasi(): void
    {
        $data = $this->seedPesertaScenario();

        // Riwayat kosong menampilkan empty state
        $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.riwayat.index'))
            ->assertStatus(200)
            ->assertSee('Belum Ada Riwayat');

        // Buat 12 catatan untuk peserta A pada tanggal berbeda
        for ($i = 1; $i <= 12; $i++) {
            Absensi::create([
                'peserta_didik_id' => $data['pesertaA']->id,
                'jadwal_id' => $data['jadwalA']->id,
                'tanggal' => sprintf('2026-10-%02d', $i),
                'status' => $i % 2 === 0 ? 'Hadir' : 'Izin',
            ]);
        }

        // Buat 1 catatan untuk peserta C (tidak boleh bocor ke peserta A)
        Absensi::create([
            'peserta_didik_id' => $data['pesertaC']->id,
            'jadwal_id' => $data['jadwalC']->id,
            'tanggal' => '2026-11-20',
            'status' => 'Sakit',
        ]);

        // Halaman 1: menampilkan 10 data terbaru (tanggal 12..03) dan total 12 absensi
        $page1 = $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.riwayat.index'));

        $page1->assertStatus(200)
            ->assertSee('12 absensi')
            ->assertSee('12-10-2026')
            ->assertSee('03-10-2026')
            ->assertDontSee('02-10-2026')
            ->assertDontSee('Ekonomi Paket C QA');

        // Halaman 2: menampilkan sisa 2 data (02-10-2026 dan 01-10-2026)
        $page2 = $this->actingAs($data['pesertaUserA'])
            ->get(route('peserta.riwayat.index', ['page' => 2]));

        $page2->assertStatus(200)
            ->assertSee('02-10-2026')
            ->assertSee('01-10-2026');
    }
}

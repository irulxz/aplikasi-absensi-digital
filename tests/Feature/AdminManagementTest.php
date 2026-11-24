<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\PesertaDidik;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_admin_crud_pengguna_dan_larangan_hapus_akun_sendiri(): void
    {
        $admin = $this->createAdmin();

        // Index
        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200);

        // Create user baru
        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'nama' => 'Peserta Baru QA',
                'email' => 'peserta.qa.baru@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'peserta_didik',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $created = User::where('email', 'peserta.qa.baru@example.com')->firstOrFail();

        // Update user tanpa mengubah password
        $this->actingAs($admin)
            ->put(route('admin.users.update', $created), [
                'nama' => 'Peserta QA Diperbarui',
                'email' => 'peserta.qa.baru@example.com',
                'role' => 'peserta_didik',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $created->id,
            'nama' => 'Peserta QA Diperbarui',
        ]);

        // Tidak boleh menghapus akun sendiri
        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Hapus user lain
        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $created))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $created->id]);
    }

    public function test_admin_crud_peserta_didik_sinkron_dengan_user(): void
    {
        $admin = $this->createAdmin();
        $userPeserta = User::factory()->create([
            'nama' => 'Siti Aminah QA',
            'role' => 'peserta_didik',
        ]);

        // Store
        $this->actingAs($admin)
            ->post(route('admin.peserta.store'), [
                'user_id' => $userPeserta->id,
                'nis' => 'QA-99001122',
                'alamat' => 'Jl. Pendidikan No. 10',
                'program_paket' => 'B',
            ])
            ->assertRedirect(route('admin.peserta.index'))
            ->assertSessionHas('success');

        $peserta = PesertaDidik::where('nis', 'QA-99001122')->firstOrFail();

        // Update
        $this->actingAs($admin)
            ->put(route('admin.peserta.update', $peserta), [
                'nama' => 'Siti Aminah Edit',
                'nis' => 'QA-99001122',
                'alamat' => 'Jl. Pemuda No. 99',
                'program_paket' => 'C',
                'email' => 'siti.edit.qa@example.com',
                'password' => 'barupass123',
                'password_confirmation' => 'barupass123',
            ])
            ->assertRedirect(route('admin.peserta.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('peserta_didik', [
            'id' => $peserta->id,
            'nama' => 'Siti Aminah Edit',
            'program_paket' => 'C',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $userPeserta->id,
            'email' => 'siti.edit.qa@example.com',
        ]);

        // Destroy menghapus peserta_didik dan user terkait
        $this->actingAs($admin)
            ->delete(route('admin.peserta.destroy', $peserta))
            ->assertRedirect(route('admin.peserta.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('peserta_didik', ['id' => $peserta->id]);
        $this->assertDatabaseMissing('users', ['id' => $userPeserta->id]);
    }

    public function test_admin_crud_tutor_dan_jadwal_serta_validasi_jam(): void
    {
        $admin = $this->createAdmin();
        $userTutor = User::factory()->create([
            'nama' => 'Pak Budi QA',
            'role' => 'tutor',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.tutor.store'), [
                'user_id' => $userTutor->id,
                'nip' => 'NIP-QA-887766',
            ])
            ->assertRedirect(route('admin.tutor.index'))
            ->assertSessionHas('success');

        $tutor = Tutor::where('nip', 'NIP-QA-887766')->firstOrFail();
        $mapel = MataPelajaran::create(['nama' => 'Matematika QA', 'tingkat' => 'Paket C']);

        // Validasi jam_selesai harus setelah jam_mulai
        $this->actingAs($admin)
            ->from(route('admin.kelas.create'))
            ->post(route('admin.kelas.store'), [
                'mata_pelajaran_id' => $mapel->id,
                'tutor_id' => $tutor->id,
                'hari' => 'Senin',
                'jam_mulai' => '10:00',
                'jam_selesai' => '09:00',
            ])
            ->assertRedirect(route('admin.kelas.create'))
            ->assertSessionHasErrors('jam_selesai');

        // Store jadwal valid
        $this->actingAs($admin)
            ->post(route('admin.kelas.store'), [
                'mata_pelajaran_id' => $mapel->id,
                'tutor_id' => $tutor->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
            ])
            ->assertRedirect(route('admin.kelas.index'))
            ->assertSessionHas('success');

        $jadwal = Jadwal::where('tutor_id', $tutor->id)
            ->where('mata_pelajaran_id', $mapel->id)
            ->firstOrFail();

        // Update & search
        $this->actingAs($admin)
            ->put(route('admin.kelas.update', $jadwal), [
                'mata_pelajaran_id' => $mapel->id,
                'tutor_id' => $tutor->id,
                'hari' => 'Selasa',
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
            ])
            ->assertRedirect(route('admin.kelas.index'));

        $this->actingAs($admin)
            ->get(route('admin.kelas.index', ['search' => 'Matematika QA']))
            ->assertStatus(200)
            ->assertSee('Matematika QA');

        // Delete jadwal & tutor
        $this->actingAs($admin)->delete(route('admin.kelas.destroy', $jadwal))
            ->assertRedirect(route('admin.kelas.index'));
        $this->assertDatabaseMissing('jadwals', ['id' => $jadwal->id]);

        $this->actingAs($admin)->delete(route('admin.tutor.destroy', $tutor))
            ->assertRedirect(route('admin.tutor.index'));
        $this->assertDatabaseMissing('tutors', ['id' => $tutor->id]);
    }

    public function test_admin_manajemen_absensi_rekap_dan_export_csv(): void
    {
        $admin = $this->createAdmin();
        $userPeserta = User::factory()->create(['nama' => 'Andi Rekap QA', 'role' => 'peserta_didik']);
        $peserta = PesertaDidik::create([
            'user_id' => $userPeserta->id,
            'nis' => 'REKAP-001',
            'nama' => 'Andi Rekap QA',
            'alamat' => 'Jl. QA',
            'program_paket' => 'A',
        ]);

        $userTutor = User::factory()->create(['role' => 'tutor']);
        $tutor = Tutor::create(['user_id' => $userTutor->id, 'nip' => 'NIP-REKAP-1', 'nama' => 'Tutor Rekap']);
        $mapel = MataPelajaran::create(['nama' => 'Bahasa Indonesia QA', 'tingkat' => 'Paket A']);
        $jadwal = Jadwal::create([
            'mata_pelajaran_id' => $mapel->id,
            'tutor_id' => $tutor->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
        ]);

        Absensi::create([
            'peserta_didik_id' => $peserta->id,
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2026-11-25',
            'status' => 'Hadir',
        ]);

        // Filter Manajemen Absensi
        $this->actingAs($admin)
            ->get(route('admin.absensi.index', ['nama' => 'REKAP-001', 'status' => 'Hadir']))
            ->assertStatus(200)
            ->assertSee('Andi Rekap QA');

        // Validasi tanggal rekap tidak valid
        $this->actingAs($admin)
            ->from(route('admin.rekap.index'))
            ->get(route('admin.rekap.index', [
                'tanggal_mulai' => '2026-11-25',
                'tanggal_selesai' => '2026-11-20',
            ]))
            ->assertSessionHasErrors('tanggal_selesai');

        // Rekap valid
        $this->actingAs($admin)
            ->get(route('admin.rekap.index', [
                'nama' => 'Andi Rekap QA',
                'tanggal_mulai' => '2026-11-01',
                'tanggal_selesai' => '2026-11-30',
            ]))
            ->assertStatus(200)
            ->assertSee('Andi Rekap QA');

        // Export CSV
        $export = $this->actingAs($admin)
            ->get(route('admin.rekap.export', ['nama' => 'Andi Rekap QA']));

        $export->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $export->headers->get('Content-Type'));

        $csv = $export->streamedContent();
        $this->assertStringContainsString('NIS;"Nama Peserta Didik";Hadir;Terlambat;Izin;Sakit;Alpa;"Total Absensi";"Persentase Kehadiran (%)"', $csv);
        $this->assertStringContainsString('REKAP-001;"Andi Rekap QA"', $csv);
    }
}

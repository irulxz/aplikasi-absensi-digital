<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndRoleAccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_login_dapat_diakses(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Absensi Digital');
    }

    public function test_login_berhasil_mengarahkan_sesuai_role(): void
    {
        $admin = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        $tutor = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'tutor',
        ]);
        $peserta = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'peserta_didik',
        ]);

        $this->post(route('login.process'), [
            'email' => $admin->email,
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login.process'), [
            'email' => $tutor->email,
            'password' => 'password123',
        ])->assertRedirect(route('tutor.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login.process'), [
            'email' => $peserta->email,
            'password' => 'password123',
        ])->assertRedirect(route('peserta.dashboard'));
    }

    public function test_login_gagal_dengan_kredensial_salah_dan_input_kosong(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Password salah
        $this->from(route('login'))
            ->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'salah123',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        // Input kosong
        $this->from(route('login'))
            ->post(route('login.process'), [
                'email' => '',
                'password' => '',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_dengan_role_tidak_dikenali_ditolak_dan_logout(): void
    {
        $unknownUser = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'tamu',
        ]);

        $response = $this->post(route('login.process'), [
            'email' => $unknownUser->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_mengakhiri_sesi_pengguna(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Kamu berhasil logout.');
        $this->assertGuest();
    }

    public function test_pembatasan_hak_akses_antar_role_dan_guest(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutor = User::factory()->create(['role' => 'tutor']);
        $peserta = User::factory()->create(['role' => 'peserta_didik']);

        // Guest diarahkan ke login
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('tutor.dashboard'))->assertRedirect(route('login'));
        $this->get(route('peserta.dashboard'))->assertRedirect(route('login'));

        // Cross-role access harus ditolak dengan 403
        $this->actingAs($peserta)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($peserta)->get(route('tutor.dashboard'))->assertStatus(403);

        $this->actingAs($tutor)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($tutor)->get(route('peserta.dashboard'))->assertStatus(403);

        $this->actingAs($admin)->get(route('tutor.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('peserta.dashboard'))->assertStatus(403);
    }
}

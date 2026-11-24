<?php

namespace Tests\Unit;

use App\Http\Controllers\PesertaAbsensiController;
use App\Http\Controllers\TutorAbsensiController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\PesertaDidik;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class WhiteBoxUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_user_model_keamanan_password_hash_dan_atribut_tersembunyi(): void
    {
        $user = new User([
            'nama' => 'Bowie QA',
            'email' => 'bowie.qa@example.com',
            'password' => 'rahasia123',
            'role' => 'peserta_didik',
        ]);

        // NFR-02: password harus di-hash dan disembunyikan dari serialisasi
        $this->assertNotSame('rahasia123', $user->password);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertSame(['password'], $user->getHidden());
        $this->assertSame(['nama', 'email', 'password', 'role'], $user->getFillable());
        $this->assertArrayNotHasKey('password', $user->toArray());
    }

    public function test_konfigurasi_tabel_dan_fillable_seluruh_model(): void
    {
        $peserta = new PesertaDidik();
        $this->assertSame('peserta_didik', $peserta->getTable());
        $this->assertSame(['user_id', 'nis', 'nama', 'alamat', 'program_paket'], $peserta->getFillable());

        $tutor = new Tutor();
        $this->assertSame('tutors', $tutor->getTable());
        $this->assertSame(['user_id', 'nip', 'nama'], $tutor->getFillable());

        $mapel = new MataPelajaran();
        $this->assertSame('mata_pelajarans', $mapel->getTable());
        $this->assertSame(['nama', 'tingkat'], $mapel->getFillable());

        $jadwal = new Jadwal();
        $this->assertSame('jadwals', $jadwal->getTable());
        $this->assertSame(['mata_pelajaran_id', 'tutor_id', 'hari', 'jam_mulai', 'jam_selesai'], $jadwal->getFillable());

        $absensi = new Absensi();
        $this->assertSame('absensis', $absensi->getTable());
        $this->assertSame(['peserta_didik_id', 'jadwal_id', 'tanggal', 'status'], $absensi->getFillable());
    }

    public function test_definisi_relasi_eloquent_seluruh_model(): void
    {
        $user = new User();
        $this->assertInstanceOf(HasOne::class, $user->pesertaDidik());
        $this->assertInstanceOf(HasOne::class, $user->tutor());

        $peserta = new PesertaDidik();
        $this->assertInstanceOf(BelongsTo::class, $peserta->user());
        $this->assertInstanceOf(HasMany::class, $peserta->absensis());

        $tutor = new Tutor();
        $this->assertInstanceOf(BelongsTo::class, $tutor->user());
        $this->assertInstanceOf(HasMany::class, $tutor->jadwals());

        $mapel = new MataPelajaran();
        $this->assertInstanceOf(HasMany::class, $mapel->jadwals());

        $jadwal = new Jadwal();
        $this->assertInstanceOf(BelongsTo::class, $jadwal->mataPelajaran());
        $this->assertInstanceOf(BelongsTo::class, $jadwal->tutor());
        $this->assertInstanceOf(HasMany::class, $jadwal->absensis());

        $absensi = new Absensi();
        $this->assertInstanceOf(BelongsTo::class, $absensi->pesertaDidik());
        $this->assertInstanceOf(BelongsTo::class, $absensi->jadwal());
    }

    public function test_role_middleware_branch_unauthenticated_redirect_ke_login(): void
    {
        $middleware = new RoleMiddleware();
        $request = Request::create('/admin/dashboard', 'GET');
        $request->setUserResolver(fn () => null);

        $response = $middleware->handle(
            $request,
            fn () => new Response('OK', 200),
            'admin'
        );

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('login'), $response->headers->get('Location'));
    }

    public function test_role_middleware_branch_role_tidak_sesuai_melempar_403(): void
    {
        $middleware = new RoleMiddleware();
        $request = Request::create('/admin/dashboard', 'GET');
        $request->setUserResolver(fn () => new User(['role' => 'peserta_didik']));

        $this->expectException(HttpException::class);

        try {
            $middleware->handle(
                $request,
                fn () => new Response('OK', 200),
                'admin'
            );
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
            throw $e;
        }
    }

    public function test_role_middleware_branch_role_sesuai_melanjutkan_request(): void
    {
        $middleware = new RoleMiddleware();
        $request = Request::create('/tutor/dashboard', 'GET');
        $request->setUserResolver(fn () => new User(['role' => 'tutor']));

        $response = $middleware->handle(
            $request,
            fn () => new Response('Akses Diterima', 200),
            'tutor'
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Akses Diterima', $response->getContent());
    }

    public function test_pemetaan_hari_indonesia_pada_peserta_absensi_controller_untuk_semua_hari(): void
    {
        $controller = new PesertaAbsensiController();
        $method = new ReflectionMethod(PesertaAbsensiController::class, 'hariIndonesia');
        $method->setAccessible(true);

        $expected = [
            '2026-11-22 10:00:00' => 'Minggu',
            '2026-11-23 10:00:00' => 'Senin',
            '2026-11-24 10:00:00' => 'Selasa',
            '2026-11-25 10:00:00' => 'Rabu',
            '2026-11-26 10:00:00' => 'Kamis',
            '2026-11-27 10:00:00' => 'Jumat',
            '2026-11-28 10:00:00' => 'Sabtu',
        ];

        foreach ($expected as $datetime => $hari) {
            Carbon::setTestNow(Carbon::parse($datetime, 'Asia/Jakarta'));
            $this->assertSame($hari, $method->invoke($controller), "Gagal untuk tanggal {$datetime}");
        }
    }

    public function test_boundary_dan_branch_jadwal_sedang_berlangsung_pada_tutor_absensi_controller(): void
    {
        $controller = new TutorAbsensiController();
        $method = new ReflectionMethod(TutorAbsensiController::class, 'jadwalSedangBerlangsung');
        $method->setAccessible(true);

        $jadwal = new Jadwal([
            'hari' => 'Rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        // Branch hari berbeda
        Carbon::setTestNow(Carbon::parse('2026-11-24 09:00:00', 'Asia/Jakarta')); // Selasa
        $this->assertFalse($method->invoke($controller, $jadwal));

        // BVA: 1 detik sebelum jam_mulai
        Carbon::setTestNow(Carbon::parse('2026-11-25 07:59:59', 'Asia/Jakarta')); // Rabu
        $this->assertFalse($method->invoke($controller, $jadwal));

        // BVA: tepat jam_mulai (inklusif)
        Carbon::setTestNow(Carbon::parse('2026-11-25 08:00:00', 'Asia/Jakarta'));
        $this->assertTrue($method->invoke($controller, $jadwal));

        // Kondisi di tengah jam belajar
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:00:00', 'Asia/Jakarta'));
        $this->assertTrue($method->invoke($controller, $jadwal));

        // BVA: 1 detik sebelum jam_selesai
        Carbon::setTestNow(Carbon::parse('2026-11-25 09:59:59', 'Asia/Jakarta'));
        $this->assertTrue($method->invoke($controller, $jadwal));

        // BVA: tepat jam_selesai (eksklusif pada TutorAbsensiController: lessThan)
        Carbon::setTestNow(Carbon::parse('2026-11-25 10:00:00', 'Asia/Jakarta'));
        $this->assertFalse($method->invoke($controller, $jadwal));
    }
}

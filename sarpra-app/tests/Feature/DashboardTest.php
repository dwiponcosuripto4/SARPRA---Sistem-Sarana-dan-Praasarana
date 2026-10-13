<?php

namespace Tests\Feature;

use App\Models\JenisPengadaan;
use App\Models\Pengadaan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_administrator_sees_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'Administrator'])->id,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Administrator');
    }

    public function test_pengelola_sees_pengelola_dashboard(): void
    {
        $pengelola = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'Pengelola Sarana & Prasarana'])->id,
        ]);

        $this->actingAs($pengelola)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Konsol Operasional Pengelola');
    }

    public function test_pimpinan_sees_pimpinan_dashboard_and_can_approve(): void
    {
        $pimpinan = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'Pimpinan'])->id,
        ]);

        $this->actingAs($pimpinan)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Eksekutif');

        $pengadaan = Pengadaan::factory()->create([
            'status' => 'menunggu_persetujuan',
            'user_id' => $pimpinan->id,
            'jenis_pengadaan_id' => JenisPengadaan::create(['name' => 'Pembelian Langsung'])->id,
            'perencanaan_id' => null,
        ]);

        $this->actingAs($pimpinan)
            ->post(route('persetujuan.setujui', $pengadaan))
            ->assertRedirect(route('dashboard'));

        $this->assertEquals('disetujui', $pengadaan->refresh()->status);
        $this->assertDatabaseHas('persetujuan_pengadaan', [
            'pengadaan_id' => $pengadaan->id,
            'pimpinan_id' => $pimpinan->id,
            'status' => 'disetujui',
        ]);
    }

    public function test_non_pimpinan_cannot_decide_pengadaan(): void
    {
        $pengelola = User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => 'Pengelola Sarana & Prasarana'])->id,
        ]);

        $pengadaan = Pengadaan::factory()->create([
            'status' => 'menunggu_persetujuan',
            'user_id' => $pengelola->id,
            'jenis_pengadaan_id' => JenisPengadaan::create(['name' => 'Pembelian Langsung'])->id,
            'perencanaan_id' => null,
        ]);

        $this->actingAs($pengelola)
            ->post(route('persetujuan.setujui', $pengadaan))
            ->assertForbidden();
    }
}

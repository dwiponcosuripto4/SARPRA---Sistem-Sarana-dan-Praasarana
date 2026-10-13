<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuPagesTest extends TestCase
{
    use RefreshDatabase;

    private function userDenganRole(string $role): User
    {
        return User::factory()->create([
            'role_id' => Role::firstOrCreate(['name' => $role])->id,
        ]);
    }

    public function test_halaman_admin_hanya_untuk_administrator(): void
    {
        $admin = $this->userDenganRole('Administrator');

        foreach (['pengguna.index', 'master.index', 'aktivitas.index'] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }

        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        foreach (['pengguna.index', 'master.index', 'aktivitas.index'] as $route) {
            $this->actingAs($pengelola)->get(route($route))->assertForbidden();
        }
    }

    public function test_halaman_pengelola_hanya_untuk_pengelola(): void
    {
        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        foreach (['perencanaan.index', 'pengadaan.index', 'inventarisasi.index', 'pemeliharaan.index'] as $route) {
            $this->actingAs($pengelola)->get(route($route))->assertOk();
        }

        $pimpinan = $this->userDenganRole('Pimpinan');

        foreach (['perencanaan.index', 'pengadaan.index', 'inventarisasi.index', 'pemeliharaan.index'] as $route) {
            $this->actingAs($pimpinan)->get(route($route))->assertForbidden();
        }
    }

    public function test_halaman_pimpinan_hanya_untuk_pimpinan(): void
    {
        $pimpinan = $this->userDenganRole('Pimpinan');

        foreach (['persetujuan.index', 'monitoring.index', 'pelaporan.index'] as $route) {
            $this->actingAs($pimpinan)->get(route($route))->assertOk();
        }

        $admin = $this->userDenganRole('Administrator');

        foreach (['persetujuan.index', 'monitoring.index', 'pelaporan.index'] as $route) {
            $this->actingAs($admin)->get(route($route))->assertForbidden();
        }
    }

    public function test_admin_dapat_menonaktifkan_pengguna_lain(): void
    {
        $admin = $this->userDenganRole('Administrator');
        $target = User::factory()->create([
            'role_id' => Role::where('name', 'Pengelola Sarana & Prasarana')->value('id'),
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('pengguna.toggle', $target))
            ->assertRedirect(route('pengguna.index'));

        $this->assertFalse($target->refresh()->is_active);
    }

    public function test_admin_tidak_dapat_menonaktifkan_diri_sendiri(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'Administrator')->value('id'),
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('pengguna.toggle', $admin))
            ->assertForbidden();

        $this->assertTrue($admin->refresh()->is_active);
    }
}

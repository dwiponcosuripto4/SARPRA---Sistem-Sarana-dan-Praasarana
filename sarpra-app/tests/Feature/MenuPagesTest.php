<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Lokasi;
use App\Models\Role;
use App\Models\Satuan;
use App\Models\StatusBarang;
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

    public function test_admin_dapat_menambah_pengguna(): void
    {
        $admin = $this->userDenganRole('Administrator');
        $roleId = Role::where('name', 'Pengelola Sarana & Prasarana')->value('id');

        $this->actingAs($admin)
            ->post(route('pengguna.store'), [
                'name' => 'User Baru',
                'email' => 'baru@sarpra.go.id',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role_id' => $roleId,
                'is_active' => '1',
            ])
            ->assertRedirect(route('pengguna.index'));

        $this->assertDatabaseHas('user', [
            'email' => 'baru@sarpra.go.id',
            'role_id' => $roleId,
        ]);
    }

    public function test_admin_dapat_menonaktifkan_banyak_pengguna_sekaligus(): void
    {
        $admin = $this->userDenganRole('Administrator');
        $roleId = Role::where('name', 'Pengelola Sarana & Prasarana')->value('id');

        $targets = User::factory()->count(2)->create([
            'role_id' => $roleId,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('pengguna.batch'), [
                'ids' => $targets->pluck('id')->all(),
                'aksi' => 'nonaktifkan',
            ])
            ->assertRedirect(route('pengguna.index'));

        foreach ($targets as $target) {
            $this->assertFalse($target->refresh()->is_active);
        }
    }

    public function test_admin_dapat_mengekspor_pengguna(): void
    {
        $admin = $this->userDenganRole('Administrator');

        $this->actingAs($admin)
            ->get(route('pengguna.ekspor'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_dapat_crud_master(): void
    {
        $admin = $this->userDenganRole('Administrator');

        // Tambah.
        $this->actingAs($admin)
            ->post(route('master.store', ['entitas' => 'kondisi']), [
                'name' => 'Baik Sekali',
                'description' => 'Uji',
            ])
            ->assertRedirect(route('master.index', ['entitas' => 'kondisi']));

        $this->assertDatabaseHas('kondisi_barang', ['name' => 'Baik Sekali']);

        $row = KondisiBarang::where('name', 'Baik Sekali')->firstOrFail();

        // Ubah.
        $this->actingAs($admin)
            ->put(route('master.update', ['entitas' => 'kondisi', 'id' => $row->id]), [
                'name' => 'Baik Sekali (Revisi)',
                'description' => 'Uji revisi',
            ])
            ->assertRedirect(route('master.index', ['entitas' => 'kondisi']));

        $this->assertDatabaseHas('kondisi_barang', ['name' => 'Baik Sekali (Revisi)']);

        // Ekspor.
        $this->actingAs($admin)
            ->get(route('master.ekspor', ['entitas' => 'kondisi']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // Hapus.
        $this->actingAs($admin)
            ->delete(route('master.destroy', ['entitas' => 'kondisi', 'id' => $row->id]))
            ->assertRedirect(route('master.index', ['entitas' => 'kondisi']));

        $this->assertDatabaseMissing('kondisi_barang', ['name' => 'Baik Sekali (Revisi)']);
    }

    public function test_master_tidak_dapat_dihapus_jika_dipakai(): void
    {
        $admin = $this->userDenganRole('Administrator');

        $kondisi = KondisiBarang::create(['name' => 'Dipakai Uji']);
        Barang::factory()->create([
            'kategori_barang_id' => KategoriBarang::create(['name' => 'Kat Uji'])->id,
            'satuan_id' => Satuan::create(['name' => 'Unit Uji'])->id,
            'lokasi_id' => Lokasi::create(['name' => 'Lok Uji'])->id,
            'kondisi_barang_id' => $kondisi->id,
            'status_barang_id' => StatusBarang::create(['name' => 'Status Uji'])->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('master.destroy', ['entitas' => 'kondisi', 'id' => $kondisi->id]))
            ->assertRedirect(route('master.index', ['entitas' => 'kondisi']));

        $this->assertDatabaseHas('kondisi_barang', ['name' => 'Dipakai Uji']);
    }
}

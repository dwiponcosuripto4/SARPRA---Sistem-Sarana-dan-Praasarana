<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\JenisPemeliharaan;
use App\Models\JenisPengadaan;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Lokasi;
use App\Models\Pemeliharaan;
use App\Models\Pengadaan;
use App\Models\Perencanaan;
use App\Models\Periode;
use App\Models\Prioritas;
use App\Models\RiwayatBarang;
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

    public function test_pelaporan_mendukung_lima_jenis_dan_ekspor(): void
    {
        $pimpinan = $this->userDenganRole('Pimpinan');

        foreach (['inventaris', 'pengadaan', 'kebutuhan', 'pemeliharaan', 'kondisi'] as $jenis) {
            $this->actingAs($pimpinan)
                ->get(route('pelaporan.index', ['jenis' => $jenis]))
                ->assertOk()
                ->assertSee('Tabel Pratinjau');
        }

        $this->actingAs($pimpinan)
            ->get(route('pelaporan.ekspor', ['jenis' => 'inventaris']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
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

    public function test_pengelola_dapat_crud_perencanaan(): void
    {
        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        $kategori = KategoriBarang::create(['name' => 'Kat Uji Rencana']);
        $satuan = Satuan::create(['name' => 'Unit Uji Rencana']);
        $periode = Periode::create(['name' => 'TA Uji Rencana']);
        $prioritas = Prioritas::create(['name' => 'Tinggi Uji']);

        // Tambah (prioritas fallback ke Tinggi bila nama tak cocok? gunakan nama asli).
        $response = $this->actingAs($pengelola)->post(route('perencanaan.store'), [
            'nama_kebutuhan' => 'Laptop Uji',
            'kategori_barang_id' => $kategori->id,
            'periode_id' => $periode->id,
            'jumlah' => 2,
            'satuan_id' => $satuan->id,
            'prioritas' => 'Tinggi',
            'alasan' => 'Kebutuhan uji otomatis.',
        ]);
        $response->assertRedirect(route('perencanaan.index'));

        $rencana = Perencanaan::where('nama_kebutuhan', 'Laptop Uji')->firstOrFail();
        $this->assertEquals('draft', $rencana->status);

        // Ubah.
        $this->actingAs($pengelola)
            ->put(route('perencanaan.update', $rencana), [
                'nama_kebutuhan' => 'Laptop Uji Revisi',
                'kategori_barang_id' => $kategori->id,
                'periode_id' => $periode->id,
                'jumlah' => 3,
                'satuan_id' => $satuan->id,
                'prioritas' => 'Sedang',
                'alasan' => 'Revisi uji.',
            ])
            ->assertRedirect(route('perencanaan.index'));

        // Ajukan.
        $this->actingAs($pengelola)
            ->post(route('perencanaan.ajukan', $rencana))
            ->assertRedirect(route('perencanaan.index'));

        $this->assertEquals('diajukan', $rencana->refresh()->status);

        // Draft yang diajukan tak bisa dihapus.
        $this->actingAs($pengelola)
            ->delete(route('perencanaan.destroy', $rencana))
            ->assertForbidden();

        // Ekspor.
        $this->actingAs($pengelola)
            ->get(route('perencanaan.ekspor'))
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

    public function test_pengelola_dapat_crud_inventarisasi(): void
    {
        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        $kategori = KategoriBarang::create(['name' => 'Kat Uji Inv']);
        $satuan = Satuan::create(['name' => 'Unit Uji Inv']);
        $lokasi = Lokasi::create(['name' => 'Lok Uji Inv']);
        $kondisi = KondisiBarang::create(['name' => 'Baik Uji Inv']);
        $status = StatusBarang::create(['name' => 'Status Uji Inv']);

        // Tambah.
        $this->actingAs($pengelola)
            ->post(route('inventarisasi.store'), [
                'nama_barang' => 'Laptop Uji Inv',
                'kategori_barang_id' => $kategori->id,
                'lokasi_id' => $lokasi->id,
                'kondisi_barang_id' => $kondisi->id,
                'status_barang_id' => $status->id,
                'jumlah' => 2,
                'satuan_id' => $satuan->id,
            ])
            ->assertRedirect(route('inventarisasi.index'));

        $barang = Barang::where('nama_barang', 'Laptop Uji Inv')->firstOrFail();
        $this->assertNotNull($barang->kode_barang);
        $this->assertDatabaseHas('riwayat_barang', ['barang_id' => $barang->id]);

        // Ubah kondisi → catat riwayat.
        $kondisi2 = KondisiBarang::create(['name' => 'Rusak Uji Inv']);
        $this->actingAs($pengelola)
            ->put(route('inventarisasi.update', $barang), [
                'nama_barang' => 'Laptop Uji Inv',
                'kategori_barang_id' => $kategori->id,
                'lokasi_id' => $lokasi->id,
                'kondisi_barang_id' => $kondisi2->id,
                'status_barang_id' => $status->id,
                'jumlah' => 2,
                'satuan_id' => $satuan->id,
            ])
            ->assertRedirect(route('inventarisasi.index'));

        $this->assertEquals(2, RiwayatBarang::where('barang_id', $barang->id)->count());

        // Ekspor.
        $this->actingAs($pengelola)
            ->get(route('inventarisasi.ekspor'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        // Hapus.
        $this->actingAs($pengelola)
            ->delete(route('inventarisasi.destroy', $barang))
            ->assertRedirect(route('inventarisasi.index'));

        $this->assertDatabaseMissing('barang', ['id' => $barang->id]);
    }

    public function test_pengelola_dapat_buat_dan_kirim_pengadaan(): void
    {
        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        $jenis = JenisPengadaan::create(['name' => 'Pembelian Langsung Uji']);
        $satuan = Satuan::create(['name' => 'Unit Uji Niaga']);
        $kategori = KategoriBarang::create(['name' => 'Kategori Uji Niaga']);

        // Simpan draft + kirim langsung.
        $this->actingAs($pengelola)
            ->post(route('pengadaan.store'), [
                'jenis_pengadaan_id' => $jenis->id,
                'tanggal_pengajuan' => now()->format('Y-m-d'),
                'alasan' => 'Uji otomatis.',
                'aksi' => 'kirim',
                'detail' => [
                    [
                        'nama_barang' => 'Laptop Uji Niaga',
                        'spesifikasi' => 'Core i5',
                        'jumlah' => 2,
                        'satuan_id' => $satuan->id,
                        'kategori_barang_id' => $kategori->id,
                        'estimasi_harga' => 10000000,
                    ],
                ],
            ])
            ->assertRedirect(route('pengadaan.index'));

        $pengadaan = Pengadaan::whereHas('detail', fn ($q) => $q->where('nama_barang', 'Laptop Uji Niaga'))->firstOrFail();
        $this->assertEquals('menunggu_persetujuan', $pengadaan->status);
        $this->assertEquals(20000000, (int) $pengadaan->detail->sum('subtotal'));

        // Rincian JSON.
        $this->actingAs($pengelola)
            ->get(route('pengadaan.rincian', $pengadaan))
            ->assertOk()
            ->assertJsonPath('nomor', $pengadaan->nomor_pengadaan);

        // Draft lain: kirim lalu hapus ditolak.
        $draft = Pengadaan::factory()->create([
            'status' => 'draft',
            'user_id' => $pengelola->id,
            'jenis_pengadaan_id' => $jenis->id,
            'perencanaan_id' => null,
        ]);

        $this->actingAs($pengelola)
            ->post(route('pengadaan.kirim', $draft))
            ->assertRedirect(route('pengadaan.index'));

        $this->assertEquals('menunggu_persetujuan', $draft->refresh()->status);

        $draft2 = Pengadaan::factory()->create([
            'status' => 'draft',
            'user_id' => $pengelola->id,
            'jenis_pengadaan_id' => $jenis->id,
            'perencanaan_id' => null,
        ]);

        $this->actingAs($pengelola)
            ->delete(route('pengadaan.destroy', $draft2))
            ->assertRedirect(route('pengadaan.index'));

        $this->assertDatabaseMissing('pengadaan', ['id' => $draft2->id]);
    }

    public function test_pengelola_dapat_mengelola_tiket_pemeliharaan(): void
    {
        $pengelola = $this->userDenganRole('Pengelola Sarana & Prasarana');

        $barang = Barang::factory()->create([
            'kategori_barang_id' => KategoriBarang::create(['name' => 'Kat Uji Har'])->id,
            'satuan_id' => Satuan::create(['name' => 'Unit Uji Har'])->id,
            'lokasi_id' => Lokasi::create(['name' => 'Lok Uji Har'])->id,
            'kondisi_barang_id' => KondisiBarang::create(['name' => 'Baik Uji Har'])->id,
            'status_barang_id' => StatusBarang::create(['name' => 'Status Uji Har'])->id,
        ]);
        $jenis = JenisPemeliharaan::create(['name' => 'Perbaikan Uji Har']);
        Prioritas::create(['name' => 'Tinggi']);

        // Ajukan tiket.
        $this->actingAs($pengelola)
            ->post(route('pemeliharaan.store'), [
                'barang_id' => $barang->id,
                'jenis_pemeliharaan_id' => $jenis->id,
                'prioritas' => 'Tinggi',
                'kerusakan' => 'AC tidak dingin.',
                'estimasi_biaya' => 500000,
            ])
            ->assertRedirect(route('pemeliharaan.index'));

        $tiket = Pemeliharaan::where('barang_id', $barang->id)->firstOrFail();
        $this->assertEquals('diajukan', $tiket->status);
        $this->assertNotNull($tiket->nomor_pemeliharaan);

        // Proses.
        $this->actingAs($pengelola)
            ->post(route('pemeliharaan.proses', $tiket))
            ->assertRedirect(route('pemeliharaan.index'));

        $this->assertEquals('dalam_pemeliharaan', $tiket->refresh()->status);

        // Selesaikan.
        $this->actingAs($pengelola)
            ->post(route('pemeliharaan.selesai', $tiket), [
                'tindakan' => 'Isi freon dan cuci unit.',
                'biaya_aktual' => 450000,
                'pelaksana' => 'Teknisi Internal',
            ])
            ->assertRedirect(route('pemeliharaan.index'));

        $tiket->refresh();
        $this->assertEquals('selesai', $tiket->status);
        $this->assertDatabaseHas('riwayat_pemeliharaan', [
            'pemeliharaan_id' => $tiket->id,
            'status' => 'selesai',
        ]);

        // Rincian JSON.
        $this->actingAs($pengelola)
            ->get(route('pemeliharaan.rincian', $tiket))
            ->assertOk()
            ->assertJsonPath('nomor', $tiket->nomor_pemeliharaan);

        // Tiket selesai tak bisa diubah.
        $this->actingAs($pengelola)
            ->put(route('pemeliharaan.update', $tiket), [
                'jenis_pemeliharaan_id' => $jenis->id,
                'prioritas' => 'Rendah',
                'kerusakan' => 'Ubah.',
            ])
            ->assertForbidden();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\DetailPengadaan;
use App\Models\Pemeliharaan;
use App\Models\Pengadaan;
use App\Models\Perencanaan;
use App\Models\PersetujuanPengadaan;
use App\Models\RiwayatAktivitas;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Hanya data inti yang di-seed (11 baris tiap tabel).
     * Factory riwayat & mutasi tersedia khusus testing, tidak di-seed.
     * Notifikasi & dokumen dibuat oleh business process saat aplikasi berjalan.
     * Urutan mengikuti alur bisnis: Perencanaan → Pengadaan →
     * Inventarisasi → Pemeliharaan.
     */
    public function run(): void
    {
        // Perencanaan.
        Perencanaan::factory()->count(11)->create();

        // Pengadaan.
        Pengadaan::factory()->count(11)->create();
        DetailPengadaan::factory()->count(11)->create();

        // Satu keputusan pimpinan untuk setiap pengadaan.
        $pimpinanId = User::whereHas('role', fn ($q) => $q->where('name', 'Pimpinan'))
            ->inRandomOrder()->value('id')
            ?? User::inRandomOrder()->value('id');

        foreach (Pengadaan::all() as $pengadaan) {
            PersetujuanPengadaan::factory()->create([
                'pengadaan_id' => $pengadaan->id,
                'pimpinan_id' => $pimpinanId,
            ]);
        }

        // Inventarisasi.
        Barang::factory()->count(11)->create();

        // Pemeliharaan.
        Pemeliharaan::factory()->count(11)->create();

        // Riwayat aktivitas demo agar dashboard admin berisi data.
        // Riwayat lain tetap hanya via factory testing / business process.
        RiwayatAktivitas::factory()->count(11)->create();
    }
}

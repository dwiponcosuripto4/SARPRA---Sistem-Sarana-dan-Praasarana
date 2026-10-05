<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\MutasiBarang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MutasiBarang>
 */
class MutasiBarangFactory extends Factory
{
    protected $model = MutasiBarang::class;

    public function definition(): array
    {
        $asal = Lokasi::inRandomOrder()->value('id');
        $tujuan = Lokasi::where('id', '!=', $asal)->inRandomOrder()->value('id')
            ?? $asal;

        return [
            'barang_id' => Barang::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
            'lokasi_asal_id' => $asal,
            'lokasi_tujuan_id' => $tujuan,
            'tanggal_mutasi' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'alasan' => fake()->optional()->sentence(),
        ];
    }
}

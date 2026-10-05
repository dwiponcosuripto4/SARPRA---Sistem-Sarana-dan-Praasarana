<?php

namespace Database\Seeders;

use App\Models\KonfigurasiSistem;
use Illuminate\Database\Seeder;

class KonfigurasiSistemSeeder extends Seeder
{
    /**
     * Konfigurasi sistem deterministik (bukan factory).
     */
    public function run(): void
    {
        $items = [
            ['nama_aplikasi', 'SARPRA', 'Nama aplikasi yang tampil di judul dan kop.'],
            ['nama_instansi', 'Direktorat Sarana & Prasarana', 'Nama instansi pemilik aplikasi.'],
            ['alamat_instansi', 'Jl. Jenderal Sudirman No. 1, Jakarta Pusat', null],
            ['telepon_instansi', '(021) 5550123', null],
            ['email_instansi', 'info@sarpra.go.id', null],
            ['nama_pimpinan', 'Dr. H. Budi Hartono, M.Si.', 'Nama pimpinan penandatangan dokumen.'],
            ['nip_pimpinan', '197501012000031001', null],
            ['tahun_anggaran_aktif', (string) date('Y'), null],
            ['zona_waktu', 'Asia/Jakarta', null],
            ['bahasa_aplikasi', 'id', null],
            ['batas_maks_upload_mb', '10', 'Batas ukuran file dokumen pendukung.'],
            ['format_nomor_pengadaan', 'PDN-{YYYY}-{####}', 'Pola penomoran pengajuan pengadaan.'],
        ];

        foreach ($items as [$key, $value, $description]) {
            KonfigurasiSistem::updateOrCreate(['key' => $key], [
                'value' => $value, 'description' => $description,
            ]);
        }
    }
}

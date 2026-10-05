<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengadaan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengadaan';

    protected $fillable = [
        'pengadaan_id', 'kategori_barang_id', 'satuan_id', 'nama_barang',
        'spesifikasi', 'jumlah', 'estimasi_harga', 'subtotal',
    ];

    public function pengadaan(): BelongsTo
    {
        return $this->belongsTo(Pengadaan::class, 'pengadaan_id');
    }
}

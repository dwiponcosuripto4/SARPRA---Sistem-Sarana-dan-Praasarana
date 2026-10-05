<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiBarang extends Model
{
    use HasFactory;

    protected $table = 'mutasi_barang';

    public const UPDATED_AT = null;

    protected $fillable = [
        'barang_id', 'user_id', 'lokasi_asal_id', 'lokasi_tujuan_id',
        'tanggal_mutasi', 'alasan',
    ];

    protected function casts(): array
    {
        return ['tanggal_mutasi' => 'date'];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}

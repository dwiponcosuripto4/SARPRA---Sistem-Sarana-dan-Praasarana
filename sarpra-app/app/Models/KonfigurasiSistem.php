<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiSistem extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_sistem';

    protected $fillable = ['key', 'value', 'description'];
}

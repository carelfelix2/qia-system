<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'nama_alat',
        'tipe_alat',
        'merk',
        'part_number',
        'harga_retail',
        'harga_inaproc',
        'harga_sebelum_ppn',
    ];

    protected $casts = [
        'harga_retail' => 'decimal:2',
        'harga_inaproc' => 'decimal:2',
        'harga_sebelum_ppn' => 'decimal:2',
    ];
}

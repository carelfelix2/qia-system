<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'sales_person',
        'jenis_penawaran',
        'format_layout',
        'nama_customer',
        'alamat_customer',
        'nama_alat',
        'part_number',
        'kategori_harga',
        'harga',
        'ppn',
        'diskon',
        'pembayaran',
        'pembayaran_other',
        'stok',
        'stok_other',
        'keterangan_tambahan',
        'created_by',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'diskon' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

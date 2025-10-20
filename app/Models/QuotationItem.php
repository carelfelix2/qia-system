<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'nama_alat',
        'tipe_alat',
        'merk',
        'part_number',
        'kategori_harga',
        'harga',
        'ppn',
        'diskon',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'diskon' => 'string',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
}

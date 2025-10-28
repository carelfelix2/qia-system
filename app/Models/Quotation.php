<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [
        'sales_person',
        'jenis_penawaran',
        'format_layout',
        'nama_customer',
        'alamat_customer',
        'diskon',
        'pembayaran',
        'pembayaran_other',
        'stok',
        'stok_other',
        'keterangan_tambahan',
        'created_by',
        'status',
        'sap_number',
        'attachment_file',
    ];

    protected $casts = [
        //
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(QuotationRevision::class);
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function poFiles(): HasMany
    {
        return $this->hasMany(POFile::class);
    }
}

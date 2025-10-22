<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::statement("ALTER TABLE quotation_items MODIFY COLUMN kategori_harga ENUM('harga_retail', 'harga_inaproc', 'harga_sebelum_ppn', 'manual') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE quotation_items MODIFY COLUMN kategori_harga ENUM('HARGA INAPROC 2025', 'HARGA RETAIL 2025', 'NON E-KATALOG (CUSTOM)') NOT NULL");
    }
};

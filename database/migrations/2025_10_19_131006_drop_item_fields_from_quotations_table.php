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
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['nama_alat', 'part_number', 'kategori_harga', 'harga', 'ppn', 'diskon']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('nama_alat');
            $table->string('part_number');
            $table->enum('kategori_harga', ['HARGA INAPROC 2025', 'HARGA RETAIL 2025', 'NON E-KATALOG (CUSTOM)']);
            $table->decimal('harga', 15, 2);
            $table->enum('ppn', ['YA', 'Tidak']);
            $table->decimal('diskon', 5, 2)->nullable();
        });
    }
};

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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->string('nama_alat');
            $table->string('part_number');
            $table->enum('kategori_harga', ['HARGA INAPROC 2025', 'HARGA RETAIL 2025', 'NON E-KATALOG (CUSTOM)']);
            $table->decimal('harga', 15, 2);
            $table->enum('ppn', ['YA', 'Tidak']);
            $table->decimal('diskon', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};

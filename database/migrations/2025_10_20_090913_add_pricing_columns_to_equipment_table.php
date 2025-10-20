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
        Schema::table('equipment', function (Blueprint $table) {
            $table->decimal('harga_retail', 15, 2)->nullable()->after('harga');
            $table->decimal('harga_inaproc', 15, 2)->nullable()->after('harga_retail');
            $table->decimal('harga_sebelum_ppn', 15, 2)->nullable()->after('harga_inaproc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['harga_retail', 'harga_inaproc', 'harga_sebelum_ppn']);
        });
    }
};

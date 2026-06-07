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
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('foto_out')->nullable()->after('foto');
            $table->decimal('latitude_out', 10, 8)->nullable()->after('foto_out');
            $table->decimal('longitude_out', 11, 8)->nullable()->after('latitude_out');
            $table->string('nama_lokasi_out')->nullable()->after('longitude_out');
            $table->text('keterangan_out')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['foto_out', 'latitude_out', 'longitude_out', 'nama_lokasi_out', 'keterangan_out']);
        });
    }
};

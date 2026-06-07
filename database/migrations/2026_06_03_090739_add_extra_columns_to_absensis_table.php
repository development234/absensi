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
            $table->string('foto')->nullable()->after('jam_keluar');          // path foto
            $table->decimal('latitude', 10, 8)->nullable()->after('foto');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('nama_lokasi')->nullable()->after('longitude');
            $table->text('tugas')->nullable()->after('nama_lokasi');
            $table->string('status')->default('hadir')->change(); // sudah ada, pastikan
            $table->text('keterangan')->nullable()->after('tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
                $table->dropColumn(['foto', 'latitude', 'longitude', 'nama_lokasi', 'tugas', 'keterangan']);
        });
    }
};

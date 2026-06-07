<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status',
    'foto', 'latitude', 'longitude', 'nama_lokasi', 'tugas', 'keterangan',
    'foto_out', 'latitude_out', 'longitude_out', 'nama_lokasi_out', 'keterangan_out'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bulan', 'tahun', 'jabatan', 'gaji_pokok', 'uang_makan',
        'tunjangan', 'lembur', 'potongan', 'total_gaji', 'keterangan'
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'uang_makan' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'lembur' => 'decimal:2',
        'potongan' => 'decimal:2',
        'total_gaji' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk nama bulan
    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$this->bulan];
    }
}
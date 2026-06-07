<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payroll;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua user dengan role pekerja (bukan pengelola)
        $users = User::where('role', 'pekerja')->get();

        // Periode: bulan Mei (5) dan Juni (6) tahun 2026
        $periode = [
            ['bulan' => 5, 'tahun' => 2026, 'nama' => 'Mei'],
            ['bulan' => 6, 'tahun' => 2026, 'nama' => 'Juni'],
        ];

        foreach ($users as $user) {
            foreach ($periode as $p) {
                // Generate komponen gaji secara random namun realistis
                $gajiPokok = rand(3500000, 8000000);
                $uangMakan = rand(300000, 600000);
                $tunjangan = rand(500000, 1500000);
                $lembur = rand(0, 500000);
                $potongan = rand(0, 200000);
                $totalGaji = $gajiPokok + $uangMakan + $tunjangan + $lembur - $potongan;

                // Pilihan jabatan random
                $jabatanOptions = ['Staff', 'Senior Staff', 'Supervisor', 'Junior Staff', 'Administrasi'];
                $jabatan = $jabatanOptions[array_rand($jabatanOptions)];

                Payroll::create([
                    'user_id' => $user->id,
                    'bulan' => $p['bulan'],
                    'tahun' => $p['tahun'],
                    'jabatan' => $jabatan,
                    'gaji_pokok' => $gajiPokok,
                    'uang_makan' => $uangMakan,
                    'tunjangan' => $tunjangan,
                    'lembur' => $lembur,
                    'potongan' => $potongan,
                    'total_gaji' => $totalGaji,
                    'keterangan' => 'Slip gaji bulan ' . $p['nama'] . ' ' . $p['tahun'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Data dummy payroll untuk Mei dan Juni berhasil ditambahkan!');
    }
}

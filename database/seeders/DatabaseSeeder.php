<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'Budy Hartono', 'Sisi Al Fariz', 'Agus Salim', 'Dewi Sartika', 'Eko Prasetyo',
            'Fitri Handayani', 'Gunawan Wirawan', 'Haniyah Maulida', 'Irfan Hakim', 'Joko Widodo',
            'Karina Maharani', 'Lukman Hakim', 'Maya Sari', 'Nugroho Santoso', 'Oktavia Dewi',
            'Putri Ayu', 'Qori Fadillah', 'Rahmat Hidayat', 'Santi Amelia', 'Teguh Firmansyah'
        ];

        foreach ($names as $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@gmail.com';
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('user123'),
                'role' => 'pekerja',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
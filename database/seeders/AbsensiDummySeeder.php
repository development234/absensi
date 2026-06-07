<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiDummySeeder extends Seeder
{
    public function run()
    {
        // Lokasi kantor
        $lokasi = [
            'Kantor Jogja' => ['lat' => -7.7956, 'lng' => 110.3695],
            'Kantor Semarang' => ['lat' => -6.9667, 'lng' => 110.4167],
        ];

        // Daftar user (pekerja saja, bukan admin)
        $users = User::where('role', 'pekerja')->get();

        // Rentang tanggal: 1 April 2026 - 31 Mei 2026
        $startDate = Carbon::parse('2026-04-01');
        $endDate = Carbon::parse('2026-05-31');

        // Loop setiap tanggal dalam rentang
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Lewati Sabtu (6) dan Minggu (7)
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($users as $user) {
                // Random status: 80% hadir, 10% izin, 5% sakit, 5% alpha
                $rand = rand(1, 100);
                if ($rand <= 80) {
                    $status = 'hadir';
                } elseif ($rand <= 90) {
                    $status = 'izin';
                } elseif ($rand <= 95) {
                    $status = 'sakit';
                } else {
                    $status = 'alpha';
                }

                // Jika alpha, tidak ada jam masuk/keluar dan lokasi
                if ($status == 'alpha') {
                    Absensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $date->toDateString(),
                        'status' => 'alpha',
                        'jam_masuk' => null,
                        'jam_keluar' => null,
                        'foto' => null,
                        'foto_out' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'nama_lokasi' => null,
                        'latitude_out' => null,
                        'longitude_out' => null,
                        'nama_lokasi_out' => null,
                        'tugas' => null,
                        'keterangan' => null,
                        'keterangan_out' => null,
                    ]);
                    continue;
                }

                // Pilih lokasi random
                $lokasiKey = array_rand($lokasi);
                $namaLokasi = $lokasiKey;
                $lat = $lokasi[$lokasiKey]['lat'];
                $lng = $lokasi[$lokasiKey]['lng'];

                // Jam masuk: 08:00 - 08:30
                $jamMasuk = Carbon::createFromTime(8, rand(0, 30), 0);
                // Jam keluar: 16:00 - 17:00
                $jamKeluar = Carbon::createFromTime(rand(16, 17), rand(0, 59), 0);

                // Tugas random
                $tugasList = [
                    'Mengerjakan laporan bulanan',
                    'Meeting dengan klien',
                    'Mengupdate database karyawan',
                    'Review dokumen proyek',
                    'Menyusun jadwal kerja',
                ];
                $tugas = (rand(1, 100) <= 70) ? $tugasList[array_rand($tugasList)] : null;

                // Keterangan random
                $keteranganList = [
                    'Tepat waktu',
                    'Sedikit terlambat karena hujan',
                    'Tidak ada kendala',
                    'Kerja dari rumah (izin)',
                ];
                $keterangan = ($status == 'izin' || $status == 'sakit') ? ($keteranganList[array_rand($keteranganList)] . ' - ' . ($status == 'izin' ? 'Izin keluarga' : 'Sakit flu')) : null;

                // Untuk check-out, bisa lokasi sama atau berbeda (misal 30% berbeda)
                $lokasiOut = (rand(1, 100) <= 30) ? (array_rand($lokasi)) : $lokasiKey;
                $namaLokasiOut = $lokasiOut;
                $latOut = $lokasi[$lokasiOut]['lat'];
                $lngOut = $lokasi[$lokasiOut]['lng'];

                $keteranganOut = (rand(1, 100) <= 20) ? 'Lembur' : null;

                Absensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $date->toDateString(),
                    'jam_masuk' => $jamMasuk->format('H:i:s'),
                    'jam_keluar' => $jamKeluar->format('H:i:s'),
                    'status' => $status,
                    'foto' => 'dummy/foto_in_' . $user->id . '_' . $date->format('Ymd') . '.jpg',
                    'foto_out' => 'dummy/foto_out_' . $user->id . '_' . $date->format('Ymd') . '.jpg',
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'nama_lokasi' => $namaLokasi,
                    'latitude_out' => $latOut,
                    'longitude_out' => $lngOut,
                    'nama_lokasi_out' => $namaLokasiOut,
                    'tugas' => $tugas,
                    'keterangan' => $keterangan,
                    'keterangan_out' => $keteranganOut,
                ]);
            }
        }

        $this->command->info('Data absensi dummy berhasil ditambahkan!');
    }
}
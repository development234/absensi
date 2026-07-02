<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total pekerja
        $totalPekerja = User::where('role', 'pekerja')->count();

        // Data hari ini
        $tanggalHariIni = Carbon::today()->toDateString();
        $hadirHariIni = Absensi::where('tanggal', $tanggalHariIni)->where('status', 'hadir')->count();
        $izinSakitHariIni = Absensi::where('tanggal', $tanggalHariIni)->whereIn('status', ['izin', 'sakit'])->count();
        $alphaHariIni = Absensi::where('tanggal', $tanggalHariIni)->where('status', 'alpha')->count();

        // 5 absensi terbaru
        $absensiTerbaru = Absensi::with('user')->orderBy('tanggal', 'desc')->limit(5)->get();

        // Statistik bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $statistik = [
            'hadir' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->where('status', 'hadir')->count(),
            'izin' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->where('status', 'izin')->count(),
            'sakit' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->where('status', 'sakit')->count(),
            'alpha' => Absensi::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->where('status', 'alpha')->count(),
        ];
        $totalBulan = array_sum($statistik);
        $statistikBulanIni = [
            'hadir' => $statistik['hadir'],
            'izin' => $statistik['izin'],
            'sakit' => $statistik['sakit'],
            'alpha' => $statistik['alpha'],
            'persen_hadir' => $totalBulan > 0 ? round(($statistik['hadir'] / $totalBulan) * 100) : 0,
            'persen_izin' => $totalBulan > 0 ? round(($statistik['izin'] / $totalBulan) * 100) : 0,
            'persen_sakit' => $totalBulan > 0 ? round(($statistik['sakit'] / $totalBulan) * 100) : 0,
            'persen_alpha' => $totalBulan > 0 ? round(($statistik['alpha'] / $totalBulan) * 100) : 0,
        ];

        return view('admin.dashboard', compact('totalPekerja', 'hadirHariIni', 'izinSakitHariIni', 'alphaHariIni', 'absensiTerbaru', 'statistikBulanIni'));
    }

    public function users()
    {
        $users = User::where('role', 'pekerja')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function allAbsensi(Request $request)
    {
        $query = Absensi::with('user');

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Ambil data dengan pagination
        $absensis = $query->orderBy('tanggal', 'desc')->paginate(10);

        // ===== TAMBAHKAN STATISTIK TERBAIK =====
        
        // 1. Pegawai dengan jam masuk paling awal (tercepat) hari ini
        $pegawaiTercepat = Absensi::with('user')
            ->whereDate('tanggal', today())
            ->whereNotNull('jam_masuk')
            ->orderBy('jam_masuk', 'asc')
            ->first();

        // 2. Pegawai paling tepat waktu (hadir dengan jam masuk <= 08:15)
        $batasWaktu = '08:15:00';
        $pegawaiTepatWaktu = Absensi::with('user')
            ->whereDate('tanggal', today())
            ->where('status', 'hadir')
            ->whereNotNull('jam_masuk')
            ->where('jam_masuk', '<=', $batasWaktu)
            ->orderBy('jam_masuk', 'asc')
            ->first();


            // Daftar user untuk dropdown
            $users = User::where('role', 'pekerja')->orderBy('name')->get();

            // Daftar kolom yang tersedia
            $availableColumns = [
                'pegawai' => 'Pegawai',
                'tanggal' => 'Tanggal',
                'jam_masuk' => 'Jam Masuk',
                'jam_keluar' => 'Jam Keluar',
                'status' => 'Status',
                'lokasi_masuk' => 'Lokasi Masuk',
                'lokasi_pulang' => 'Lokasi Pulang',
                'foto' => 'Foto'
            ];

            // Kolom yang dipilih (default semua)
            $selectedColumns = $request->get('columns', array_keys($availableColumns));

            return view('admin.absensi', compact('absensis', 'users', 'selectedColumns', 'availableColumns', 'pegawaiTercepat', 'pegawaiTepatWaktu'));
        }

    public function createUserForm()
    {
        return view('admin.create_user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pekerja',
        ]);
        return redirect()->route('admin.users')->with('success', 'User created');
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'pengelola') {
            return back()->with('error', 'Tidak boleh menghapus admin');
        }
        $user->delete();
        return back()->with('success', 'User deleted');
    }

    public function deleteAbsensi($id)
    {
        $absen = Absensi::findOrFail($id);
        // Hapus file foto jika ada (opsional)
        if ($absen->foto && Storage::exists($absen->foto)) Storage::delete($absen->foto);
        if ($absen->foto_out && Storage::exists($absen->foto_out)) Storage::delete($absen->foto_out);
        $absen->delete();
        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil dihapus.');
    }

    public function updateAbsensi(Request $request, $id)
    {
        $absen = Absensi::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_out' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_lokasi' => 'nullable|string|max:255',
            'nama_lokasi_out' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'latitude_out' => 'nullable|numeric',
            'longitude_out' => 'nullable|numeric',
            'tugas' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'keterangan_out' => 'nullable|string',
        ]);

        // Update foto masuk jika ada file baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($absen->foto && Storage::disk('public')->exists($absen->foto)) {
                Storage::disk('public')->delete($absen->foto);
            }
            $fotoPath = $request->file('foto')->store('foto', 'public');
            $absen->foto = $fotoPath;
        }

        // Update foto pulang jika ada file baru
        if ($request->hasFile('foto_out')) {
            if ($absen->foto_out && Storage::disk('public')->exists($absen->foto_out)) {
                Storage::disk('public')->delete($absen->foto_out);
            }
            $fotoOutPath = $request->file('foto_out')->store('foto_out', 'public');
            $absen->foto_out = $fotoOutPath;
        }

        // Update field lainnya
        $absen->tanggal = $request->tanggal;
        $absen->jam_masuk = $request->jam_masuk;
        $absen->jam_keluar = $request->jam_keluar;
        $absen->status = $request->status;
        $absen->nama_lokasi = $request->nama_lokasi;
        $absen->latitude = $request->latitude;
        $absen->longitude = $request->longitude;
        $absen->nama_lokasi_out = $request->nama_lokasi_out;
        $absen->latitude_out = $request->latitude_out;
        $absen->longitude_out = $request->longitude_out;
        $absen->tugas = $request->tugas;
        $absen->keterangan = $request->keterangan;
        $absen->keterangan_out = $request->keterangan_out;

        $absen->save();

        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function rekapAbsensi(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $searchUser = $request->get('search_user');
        $perPage = $request->get('per_page', 10);

        // Query user dengan role pekerja
        $usersQuery = User::where('role', 'pekerja');

        if ($searchUser) {
            $usersQuery->where('name', 'like', "%{$searchUser}%")
                    ->orWhere('email', 'like', "%{$searchUser}%");
        }

        $users = $usersQuery->get();

        $data = [];
        foreach ($users as $user) {
            // Ambil absensi dalam bulan/tahun yang dipilih
            $absensis = Absensi::where('user_id', $user->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get();

            $totalHadir = 0;
            $totalJamKerja = 0; // dalam menit
            $totalTerlambat = 0; // menit
            $totalIzin = 0;
            $totalSakit = 0;
            $totalAlpha = 0;

            foreach ($absensis as $absen) {
                if ($absen->status == 'hadir') {
                    $totalHadir++;
                    if ($absen->jam_masuk && $absen->jam_keluar) {
                        $masuk = Carbon::parse($absen->jam_masuk);
                        $keluar = Carbon::parse($absen->jam_keluar);
                        $menitKerja = $masuk->diffInMinutes($keluar);
                        $totalJamKerja += $menitKerja;
                    }
                    if ($absen->jam_masuk) {
                        $jamMasuk = Carbon::parse($absen->jam_masuk);
                        $batas = Carbon::createFromTime(8, 15, 0);
                        if ($jamMasuk->gt($batas)) {
                            $totalTerlambat += $jamMasuk->diffInMinutes($batas);
                        }
                    }
                } elseif ($absen->status == 'izin') {
                    $totalIzin++;
                } elseif ($absen->status == 'sakit') {
                    $totalSakit++;
                } elseif ($absen->status == 'alpha') {
                    $totalAlpha++;
                }
            }

            $data[] = (object) [ // gunakan object agar konsisten
                'user' => $user,
                'totalHadir' => $totalHadir,
                'totalJamKerja' => $totalJamKerja,
                'totalJamKerjaFormatted' => floor($totalJamKerja / 60) . ' jam ' . ($totalJamKerja % 60) . ' menit',
                'totalTerlambat' => $totalTerlambat,
                'totalTerlambatFormatted' => floor($totalTerlambat / 60) . ' jam ' . ($totalTerlambat % 60) . ' menit',
                'totalIzin' => $totalIzin,
                'totalSakit' => $totalSakit,
                'totalAlpha' => $totalAlpha,
                'totalKehadiran' => $totalHadir + $totalIzin + $totalSakit,
            ];
        }

        // Daftar bulan untuk dropdown
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tahunList = range(Carbon::now()->year - 2, Carbon::now()->year);

        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = new Collection($data);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedData = new LengthAwarePaginator($currentPageItems, count($collection), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('admin.rekap', compact('paginatedData', 'bulan', 'tahun', 'searchUser', 'bulanList', 'tahunList'));
    }

}

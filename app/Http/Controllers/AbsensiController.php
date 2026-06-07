<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AbsensiController extends Controller
{
    public function index()
    {
        $today = Absensi::where('user_id', auth()->id())
                        ->where('tanggal', Carbon::today())
                        ->first();
        return view('absensi.index', compact('today'));
    }


    public function checkIn(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:2048',               // file upload
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'nama_lokasi' => 'required|string|max:255',
            'tugas' => 'nullable|string',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        // Cek apakah sudah absen hari ini
        $exists = Absensi::where('user_id', auth()->id())
                        ->where('tanggal', now()->toDateString())
                        ->first();
        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan absen hari ini.');
        }

        // Simpan foto
        $fotoPath = $request->file('foto')->store('foto', 'public');

        Absensi::create([
            'user_id' => auth()->id(),
            'tanggal' => now()->toDateString(),
            'jam_masuk' => now()->toTimeString(),
            'status' => $request->status,
            'foto' => $fotoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'nama_lokasi' => $request->nama_lokasi,
            'tugas' => $request->tugas,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('absensi.index')->with('success', 'Check-in berhasil disimpan.');
    }

    public function showCheckoutForm()
    {
        $today = Absensi::where('user_id', auth()->id())
                        ->where('tanggal', now()->toDateString())
                        ->first();

        if (!$today || $today->jam_keluar) {
            return redirect()->route('absensi.index')->with('error', 'Tidak dapat melakukan check-out.');
        }

        return view('absensi.checkout', compact('today'));
    }


    public function checkOut(Request $request)
    {
        $request->validate([
            'foto_out' => 'required|image|max:2048',
            'latitude_out' => 'required|numeric',
            'longitude_out' => 'required|numeric',
            'nama_lokasi_out' => 'required|string|max:255',
            'keterangan_out' => 'nullable|string',
        ]);

        $absensi = Absensi::where('user_id', auth()->id())
                        ->where('tanggal', now()->toDateString())
                        ->first();

        if (!$absensi || $absensi->jam_keluar) {
            return back()->with('error', 'Anda sudah check-out atau belum check-in.');
        }

        // Simpan foto out
        $fotoOutPath = $request->file('foto_out')->store('foto_out', 'public');

        $absensi->update([
            'jam_keluar' => now()->toTimeString(),
            'foto_out' => $fotoOutPath,
            'latitude_out' => $request->latitude_out,
            'longitude_out' => $request->longitude_out,
            'nama_lokasi_out' => $request->nama_lokasi_out,
            'keterangan_out' => $request->keterangan_out,
        ]);

        return redirect()->route('absensi.index')->with('success', 'Check-out berhasil disimpan.');
    }

    public function riwayat()
    {
        $absensis = Absensi::where('user_id', auth()->id())
                           ->orderBy('tanggal', 'desc')
                           ->paginate(10);
        return view('absensi.riwayat', compact('absensis'));
    }



}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $payrolls = Payroll::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('id')
            ->get();
        
        // Untuk form input cepat
        $users = User::where('role', 'pekerja')->orderBy('name')->get();
        
        $bulanList = $this->getBulanList();
        $tahunList = range(date('Y')-2, date('Y')+1);
        
        return view('payroll.index', compact('payrolls', 'bulan', 'tahun', 'users', 'bulanList', 'tahunList'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'gaji_pokok' => 'required|numeric|min:0',
            'uang_makan' => 'nullable|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);
        
        $total = $request->gaji_pokok + ($request->uang_makan ?? 0) + ($request->tunjangan ?? 0) + ($request->lembur ?? 0) - ($request->potongan ?? 0);
        
        Payroll::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ],
            [
                'jabatan' => $request->jabatan,
                'gaji_pokok' => $request->gaji_pokok,
                'uang_makan' => $request->uang_makan ?? 0,
                'tunjangan' => $request->tunjangan ?? 0,
                'lembur' => $request->lembur ?? 0,
                'potongan' => $request->potongan ?? 0,
                'total_gaji' => $total,
                'keterangan' => $request->keterangan,
            ]
        );
        
        return redirect()->route('admin.payroll.index', ['bulan' => $request->bulan, 'tahun' => $request->tahun])
            ->with('success', 'Data gaji berhasil disimpan.');
    }
    
    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        return response()->json($payroll);
    }
    
    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);
        
        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'uang_makan' => 'nullable|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
        ]);
        
        $total = $request->gaji_pokok + ($request->uang_makan ?? 0) + ($request->tunjangan ?? 0) + ($request->lembur ?? 0) - ($request->potongan ?? 0);
        
        $payroll->update([
            'jabatan' => $request->jabatan,
            'gaji_pokok' => $request->gaji_pokok,
            'uang_makan' => $request->uang_makan ?? 0,
            'tunjangan' => $request->tunjangan ?? 0,
            'lembur' => $request->lembur ?? 0,
            'potongan' => $request->potongan ?? 0,
            'total_gaji' => $total,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()->route('admin.payroll.index', ['bulan' => $payroll->bulan, 'tahun' => $payroll->tahun])
            ->with('success', 'Data gaji berhasil diupdate.');
    }
    
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return back()->with('success', 'Data gaji dihapus.');
    }
    
    public function print(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $payrolls = Payroll::with('user')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('id')
            ->get();
        
        $totalSemua = $payrolls->sum('total_gaji');
        $bulanNama = $this->getBulanList()[$bulan];
        
        return view('payroll.print', compact('payrolls', 'bulan', 'tahun', 'bulanNama', 'totalSemua'));
    }
    
    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }


    public function getDetail($id)
    {
        $payroll = Payroll::with('user')->find($id);
        if (!$payroll) {
            return response()->json(['success' => false]);
        }
        return response()->json([
            'success' => true,
            'user_name' => $payroll->user->name,
            'jabatan' => $payroll->jabatan,
            'nama_bulan' => $payroll->nama_bulan,
            'tahun' => $payroll->tahun,
            'gaji_pokok' => $payroll->gaji_pokok,
            'uang_makan' => $payroll->uang_makan,
            'tunjangan' => $payroll->tunjangan,
            'lembur' => $payroll->lembur,
            'potongan' => $payroll->potongan,
            'total_gaji' => $payroll->total_gaji,
            'keterangan' => $payroll->keterangan,
        ]);
    }

    // Menampilkan daftar slip gaji user yang login
    public function slipGaji(Request $request)
    {
        $user = auth()->user();
        
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        // Ambil payroll untuk user ini dengan filter bulan/tahun
        $payrolls = Payroll::where('user_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);
        
        // Untuk filter di dropdown
        $bulanList = $this->getBulanList();
        $tahunList = range(date('Y')-2, date('Y')+1);
        
        // Jika ada request spesifik bulan/tahun, ambil detail untuk ditampilkan di card
        $selectedPayroll = null;
        if ($bulan && $tahun) {
            $selectedPayroll = Payroll::where('user_id', $user->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->first();
        }
        
        return view('slip.index', compact('payrolls', 'selectedPayroll', 'bulan', 'tahun', 'bulanList', 'tahunList'));
    }

    // Cetak satu slip gaji (PDF friendly)
    public function cetakSlip($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        
        // Pastikan hanya user yang bersangkutan atau admin yang bisa cetak
        if (auth()->user()->role !== 'pengelola' && $payroll->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('slip.cetak', compact('payroll'));
    }

    // Untuk admin melihat slip karyawan
    public function adminSlip($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        
        // Pastikan hanya admin yang bisa mengakses
        if (auth()->user()->role !== 'pengelola') {
            abort(403);
        }
        
        return view('slip.cetak', compact('payroll')); // reuse view cetak yang sama
    }

}
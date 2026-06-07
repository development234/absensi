@extends('layouts.app')

@section('title', 'Slip Gaji Saya')

@section('content')
<div class="container-fluid">
    <div class="welcome-section mb-2">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="p-2">
                <i class="bi bi-wallet2 fs-2 text-absensi"></i>
            </div>
            <div>
                <h6 class="fw-semibold text-dark mb-0">Slip Gaji</h6>
                <p class="text-absensi mb-0 mt-0 py-0">Lihat dan cetak slip gaji per bulan</p>
            </div>
        </div>
   </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4 ">
        <div class="card-body">
            <form method="GET" action="{{ route('slip.gaji') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach($bulanList as $key => $nama)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
                @if($selectedPayroll)
                <div class="col-md-3 text-end">
                    <a href="{{ route('slip.cetak', $selectedPayroll->id) }}" class="btn btn-secondary w-100" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Slip
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Daftar Riwayat Payroll (sebagai alternatif pilih bulan) -->
    <div class="card border-0 shadow-sm mb-4 p-2">
        <div class="card-header bg-transparent">
            <h6 class="mb-0 fw-semibold">Riwayat Gaji</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end">Total Gaji</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $pay)
                        <tr>
                            <td>{{ $pay->getNamaBulanAttribute() }} {{ $pay->tahun }}</td>
                            <td class="text-end">{{ number_format($pay->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">{{ number_format($pay->total_gaji, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('slip.gaji', ['bulan' => $pay->bulan, 'tahun' => $pay->tahun]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <a href="{{ route('slip.cetak', $pay->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada data gaji.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 pt-3 pb-3 d-flex justify-content-center">
            {{ $payrolls->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <!-- Detail Slip Gaji yang Dipilih -->
    @if($selectedPayroll)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-semibold">Slip Gaji {{ $selectedPayroll->getNamaBulanAttribute() }} {{ $selectedPayroll->tahun }}</h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td style="width: 140px;">Nama Karyawan</td><td>: <strong>{{ $selectedPayroll->user->name }}</strong></td></tr>
                        <tr><td>Jabatan</td><td>: {{ $selectedPayroll->jabatan ?? '-' }}</td></tr>
                        <tr><td>Periode</td><td>: {{ $selectedPayroll->getNamaBulanAttribute() }} {{ $selectedPayroll->tahun }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><td style="width: 140px;">Tanggal Cetak</td><td>: {{ now()->format('d/m/Y H:i') }}</td></tr>
                        <tr><td>Status</td><td>: <span class="badge bg-success">Lunas</span></td></tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-semibold">Pendapatan</h6>
                    <table class="table table-sm">
                        <tr><td>Gaji Pokok</td><td class="text-end">{{ number_format($selectedPayroll->gaji_pokok, 0, ',', '.') }}</td></tr>
                        <tr><td>Uang Makan</td><td class="text-end">{{ number_format($selectedPayroll->uang_makan, 0, ',', '.') }}</td></tr>
                        <tr><td>Tunjangan</td><td class="text-end">{{ number_format($selectedPayroll->tunjangan, 0, ',', '.') }}</td></tr>
                        <tr><td>Lembur</td><td class="text-end">{{ number_format($selectedPayroll->lembur, 0, ',', '.') }}</td></tr>
                        <tr class="fw-bold"><td>Total Pendapatan</td><td class="text-end">{{ number_format($selectedPayroll->gaji_pokok + $selectedPayroll->uang_makan + $selectedPayroll->tunjangan + $selectedPayroll->lembur, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold">Potongan</h6>
                    <table class="table table-sm">
                        <tr><td>Potongan Lain</td><td class="text-end">{{ number_format($selectedPayroll->potongan, 0, ',', '.') }}</td></tr>
                        <tr class="fw-bold text-danger"><td>Total Potongan</td><td class="text-end">{{ number_format($selectedPayroll->potongan, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12 text-end">
                    <h4 class="fw-bold">Gaji Bersih: Rp {{ number_format($selectedPayroll->total_gaji, 0, ',', '.') }}</h4>
                </div>
            </div>

            @if($selectedPayroll->keterangan)
            <hr>
            <div class="alert alert-light mt-3">
                <i class="bi bi-info-circle"></i> Keterangan: {{ $selectedPayroll->keterangan }}
            </div>
            @endif
        </div>
        <div class="card-footer bg-transparent text-end">
            <a href="{{ route('slip.cetak', $selectedPayroll->id) }}" class="btn btn-secondary" target="_blank">
                <i class="bi bi-printer"></i> Cetak Slip
            </a>
        </div>
    </div>
    @elseif(request()->has('bulan') && request()->has('tahun'))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> Data gaji untuk periode {{ $bulanList[$bulan] }} {{ $tahun }} tidak ditemukan.
    </div>
    @endif
</div>
@endsection
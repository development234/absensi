@extends('layouts.app')

@section('title', 'Rekap Absensi Karyawan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <!-- Header selamat datang -->
        <div class="welcome-section">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2">
                    <i class="bi bi-person-workspace fs-2 text-absensi"></i>
                </div>
                <div>
                    <h6 class="fw-semibold text-dark mb-0">Rekap Absensi Karyawan</h6>
                    <p class="text-absensi mb-0">Total kehadiran dan jam kerja per pegawai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Filter -->
    <div class="card border-0 shadow-sm mb-2">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rekap') }}" class="row g-3 align-items-end mb-5">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach($bulanList as $key => $nama)
                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach($tahunList as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari Pegawai</label>
                    <input type="text" name="search_user" class="form-control" placeholder="Nama atau email" value="{{ $searchUser }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
            <!-- Tabel Rekap -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Pegawai</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Alpha</th>
                            <th class="text-center">Total Kehadiran</th>
                            <th class="text-center">Total Jam Kerja</th>
                            <th class="text-center">Total Terlambat</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($paginatedData as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start">
                                <span class="fw-semibold">{{ $d->user->name }}</span>
                                
                            </td>
                            <td class="text-center">{{ $d->totalHadir }}</td>
                            <td class="text-center">{{ $d->totalIzin }}</td>
                            <td class="text-center">{{ $d->totalSakit }}</td>
                            <td class="text-center">{{ $d->totalAlpha }}</td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill">
                                    {{ $d->totalKehadiran }}
                                </span>
                            </td>
                            <td>{{ $d->totalJamKerjaFormatted }}</td>
                            <td class="{{ $d->totalTerlambat > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ $d->totalTerlambatFormatted }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data untuk periode yang dipilih.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="card-footer bg-transparent border-0 pt-3 pb-3 d-flex justify-content-center">
                    {{ $paginatedData->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
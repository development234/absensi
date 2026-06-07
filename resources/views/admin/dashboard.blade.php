@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Header selamat datang -->
    <div class="welcome-section mb-2">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="p-2">
                <i class="bi bi-person-workspace fs-2 text-absensi"></i>
            </div>
            <div>
                <h6 class="fw-semibold text-dark mb-0">Assalamualaikum, {{ auth()->user()->name }}</h6>
                <p class="text-absensi mb-0">Selamat datang di panel pengelola. Berikut ringkasan data absensi hari ini.</p>
            </div>
        </div>
   </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">Total Pekerja</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalPekerja ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-absensi-light">
                            <i class="bi bi-people-fill fs-5 text-absensi"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">Hadir Hari Ini</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $hadirHariIni ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-success bg-opacity-10">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">Izin / Sakit</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $izinSakitHariIni ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-warning bg-opacity-10">
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">Alpha / Tidak Hadir</span>
                            <h3 class="mb-0 fw-bold text-dark">{{ $alphaHariIni ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-danger bg-opacity-10">
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik atau tabel ringkasan -->
    <div class="row g-3">
        <!-- 5 Absensi Terbaru -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i> Absensi Terbaru</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiTerbaru ?? [] as $absen)
                                <tr>
                                    <td class="small">{{ $absen->user->name }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                                    <td class="small">{{ $absen->jam_masuk ?? '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($absen->status == 'hadir') bg-success
                                            @elseif($absen->status == 'izin') bg-warning text-dark
                                            @elseif($absen->status == 'sakit') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($absen->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada data absensi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-end border-0">
                    <a href="{{ route('admin.absensi') }}" class="small text-decoration-none">Lihat semua <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Ringkasan Kehadiran per Bulan (chart sederhana) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-bar-chart-steps me-2"></i> Statistik Bulan Ini</h6>
                </div>
                <div class="card-body">
                    @if(isset($statistikBulanIni))
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Hadir</span>
                                <span>{{ $statistikBulanIni['hadir'] }} hari</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $statistikBulanIni['persen_hadir'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Izin</span>
                                <span>{{ $statistikBulanIni['izin'] }} hari</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $statistikBulanIni['persen_izin'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Sakit</span>
                                <span>{{ $statistikBulanIni['sakit'] }} hari</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: {{ $statistikBulanIni['persen_sakit'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Alpha</span>
                                <span>{{ $statistikBulanIni['alpha'] }} hari</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" style="width: {{ $statistikBulanIni['persen_alpha'] }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted small text-center py-3">Belum ada data untuk bulan ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
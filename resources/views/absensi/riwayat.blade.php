@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="container-fluid mt-1">
    <!-- Header selamat datang -->
    <div class="welcome-section mb-2">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="p-2">
                <i class="bi bi-clock-history fs-2 text-absensi"></i>
            </div>
            <div>
                <h6 class="fw-semibold text-dark mb-0">Daftar Riwayat , {{ auth()->user()->name }}</h6>
                <p class="text-absensi mb-0">Ringkasan data absensi</p>
            </div>
        </div>
   </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-2 border-0">
                <div class="card-header bg-gradient-primary text-absensi d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi</h6>
                </div>
                <div class="card-body">
                    @if($absensis->isEmpty())
                        <div class="alert alert-info">Belum ada data absensi.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensis as $index => $absen)
                                    <tr>
                                        <td>{{ $absensis->firstItem() + $index }}</td>
                                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $absen->jam_masuk ?? '-' }}</td>
                                        <td>{{ $absen->jam_keluar ?? '-' }}</td>
                                        <td>
                                            @php
                                                $badge = [
                                                    'hadir' => 'success',
                                                    'izin' => 'warning',
                                                    'sakit' => 'info',
                                                    'alpha' => 'danger'
                                                ];
                                                $label = ucfirst($absen->status);
                                            @endphp
                                            <span class="badge bg-{{ $badge[$absen->status] ?? 'secondary' }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="card-footer bg-transparent border-0 pt-3 pb-3 d-flex justify-content-center">
                            {{ $absensis->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
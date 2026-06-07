@extends('layouts.app')

@section('title', 'Data Absensi - Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="welcome-section mb-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-2 text-absensi"></i>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Data Absensi Karyawan</h6>
                    <p class="fs-8 mb-0 text-absensi">Seluruh riwayat absensi semua pegawai</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- Tabel Absensi -->
    <div class="card border-0 shadow-sm p-3">
        <div class="card-body p-2">
               <!-- Filter -->
            <div class="card border-0 shadow-sm mb-5 p-2">
                <form method="GET" action="{{ route('admin.absensi') }}" class="mb-2 row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Pegawai</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">Semua Pegawai</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 py-0"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('admin.absensi') }}" class="btn btn-secondary btn-sm rounded-pill px-3 py-0">Reset</a>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pegawai</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Lokasi Masuk</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $index => $absen)
                        <tr>
                            <td>{{ $absensis->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-semibold mb-0">{{ $absen->user->name }}</span>
                                <br class="mt-0"><small class="text-muted mt-0">{{ $absen->user->email }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ $absen->jam_masuk ?? '-' }}</td>
                            <td>{{ $absen->jam_keluar ?? '-' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'hadir' => 'bg-success',
                                        'izin' => 'bg-warning text-dark',
                                        'sakit' => 'bg-info',
                                        'alpha' => 'bg-secondary'
                                    ][$absen->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} px-2 py-1">{{ ucfirst($absen->status) }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" data-bs-toggle="tooltip" title="{{ $absen->nama_lokasi ?? '-' }}">
                                    <i class="bi bi-geo-alt-fill text-info"></i> Lihat
                                </button>
                                @if($absen->latitude && $absen->longitude)
                                    <br><small class="text-muted">{{ number_format($absen->latitude,4) }}, {{ number_format($absen->longitude,4) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($absen->foto)
                                    <a href="{{ Storage::url($absen->foto) }}" target="_blank" class="btn btn-sm btn-outline-secondary ">
                                        <i class="bi bi-camera-fill"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if($absen->foto_out)
                                    <a href="{{ Storage::url($absen->foto_out) }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-1" title="Foto Pulang">
                                        <i class="bi bi-box-arrow-left"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $absen->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Tombol Detail -->
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $absen->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <!-- Tombol Delete -->
                                <button type="button" class="btn btn-sm btn-outline-danger delete-absen" 
                                        data-id="{{ $absen->id }}" 
                                        data-pegawai="{{ $absen->user->name }}" 
                                        data-tanggal="{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="p-3 bg-white border-0 pt-3 pb-3 d-flex justify-content-center">
                {{ $absensis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit untuk setiap absensi -->
@foreach($absensis as $absen)
<div class="modal fade" id="editModal{{ $absen->id }}" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-transparent border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-pencil-square me-2 text-absensi"></i>Edit Absensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.absensi.update', $absen->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pegawai</label>
                            <input type="text" class="form-control bg-light" value="{{ $absen->user->name }}" disabled>
                            <input type="hidden" name="user_id" value="{{ $absen->user_id }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $absen->tanggal->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" value="{{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jam Keluar</label>
                            <input type="time" name="jam_keluar" class="form-control" value="{{ $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="hadir" {{ $absen->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="izin" {{ $absen->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="sakit" {{ $absen->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="alpha" {{ $absen->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Lokasi Masuk</label>
                            <input type="text" name="nama_lokasi" class="form-control" value="{{ $absen->nama_lokasi }}">
                            <div class="form-text text-muted small">
                                @if($absen->latitude && $absen->longitude)
                                    Koordinat: {{ $absen->latitude }}, {{ $absen->longitude }}
                                @endif
                            </div>
                            <input type="hidden" name="latitude" value="{{ $absen->latitude }}">
                            <input type="hidden" name="longitude" value="{{ $absen->longitude }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Lokasi Pulang</label>
                            <input type="text" name="nama_lokasi_out" class="form-control" value="{{ $absen->nama_lokasi_out }}">
                            <div class="form-text text-muted small">
                                @if($absen->latitude_out && $absen->longitude_out)
                                    Koordinat: {{ $absen->latitude_out }}, {{ $absen->longitude_out }}
                                @endif
                            </div>
                            <input type="hidden" name="latitude_out" value="{{ $absen->latitude_out }}">
                            <input type="hidden" name="longitude_out" value="{{ $absen->longitude_out }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Tugas</label>
                            <textarea name="tugas" class="form-control" rows="2">{{ $absen->tugas }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Keterangan Masuk</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ $absen->keterangan }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Keterangan Pulang</label>
                            <textarea name="keterangan_out" class="form-control" rows="2">{{ $absen->keterangan_out }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Foto Masuk</label>
                            @if($absen->foto)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($absen->foto) }}" class="img-thumbnail" width="80">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto_{{ $absen->id }}" value="1">
                                    <label class="form-check-label small" for="hapus_foto_{{ $absen->id }}">Hapus foto saat ini</label>
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Foto Pulang</label>
                            @if($absen->foto_out)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($absen->foto_out) }}" class="img-thumbnail" width="80">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_foto_out" id="hapus_foto_out_{{ $absen->id }}" value="1">
                                    <label class="form-check-label small" for="hapus_foto_out_{{ $absen->id }}">Hapus foto saat ini</label>
                                </div>
                            @endif
                            <input type="file" name="foto_out" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!-- Modal Konfirmasi Hapus Absensi -->
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-transparent border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <p>Apakah Anda yakin ingin menghapus data absensi <strong id="deleteInfo"></strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan dan akan menghapus foto terkait.</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <form method="POST" id="deleteForm" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Detail untuk setiap absensi -->
@foreach($absensis as $absen)
<div class="modal fade" id="detailModal{{ $absen->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-light border-0">
                <h6 class="modal-title fw-semibold">Detail Absensi - {{ $absen->user->name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light border-0 rounded-3 p-2 h-100">
                            <div class="card-body p-2">
                                <h6 class="text-primary"><i class="bi bi-check-circle"></i> Check-In</h6>
                                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}</p>
                                <p><strong>Jam Masuk:</strong> {{ $absen->jam_masuk ?? '-' }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($absen->status) }}</p>
                                <p><strong>Lokasi:</strong> {{ $absen->nama_lokasi ?? '-' }}</p>
                                <p><strong>Koordinat:</strong> {{ $absen->latitude ?? '-' }}, {{ $absen->longitude ?? '-' }}</p>
                                <p><strong>Tugas:</strong> {{ $absen->tugas ?? '-' }}</p>
                                <p><strong>Keterangan:</strong> {{ $absen->keterangan ?? '-' }}</p>
                                @if($absen->foto)
                                    <img src="{{ Storage::url($absen->foto) }}" class="img-fluid rounded-3" style="max-height:150px">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0 rounded-3 p-2 h-100">
                            <div class="card-body p-2">
                                <h6 class="text-warning"><i class="bi bi-box-arrow-left"></i> Check-Out</h6>
                                <p><strong>Jam Keluar:</strong> {{ $absen->jam_keluar ?? '-' }}</p>
                                <p><strong>Lokasi Pulang:</strong> {{ $absen->nama_lokasi_out ?? '-' }}</p>
                                <p><strong>Koordinat:</strong> {{ $absen->latitude_out ?? '-' }}, {{ $absen->longitude_out ?? '-' }}</p>
                                <p><strong>Keterangan Pulang:</strong> {{ $absen->keterangan_out ?? '-' }}</p>
                                @if($absen->foto_out)
                                    <img src="{{ Storage::url($absen->foto_out) }}" class="img-fluid rounded-3" style="max-height:150px">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    // Inisialisasi tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
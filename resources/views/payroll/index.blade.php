@extends('layouts.app')

@section('title', 'Payroll Gaji Karyawan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="welcome-section mb-2">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="p-2">
                    <i class="bi bi-wallet2 fs-2 text-absensi"></i>
                </div>
                <div>
                    <h6 class="fw-semibold text-dark mb-0">Payroll Gaji Karyawan</h6>
                    <p class="text-absensi mb-0 mt-0 py-0">Kelola gaji per bulan</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.payroll.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-secondary" target="_blank">
            <i class="bi bi-printer"></i> Print Laporan
        </a>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payroll.index') }}" class="row g-3 align-items-end">
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
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Tambah Gaji -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-plus-circle"></i> Tambah / Update Gaji</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.payroll.store') }}">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">Karyawan</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Staff">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" class="form-control" step="1000" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">Uang Makan</label>
                        <input type="number" name="uang_makan" class="form-control" step="1000" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">Tunjangan</label>
                        <input type="number" name="tunjangan" class="form-control" step="1000" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">Lembur</label>
                        <input type="number" name="lembur" class="form-control" step="1000" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">Potongan</label>
                        <input type="number" name="potongan" class="form-control" step="1000" value="0">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="keterangan" class="form-control" rows="1" placeholder="Keterangan"></textarea>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Payroll -->
    <div class="card border-0 shadow-sm p-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Karyawan</th>
                            <th>Jabatan</th>
                            <th>Gaji Pokok</th>
                            <th>Uang Makan</th>
                            <th>Tunjangan</th>
                            <th>Lembur</th>
                            <th>Potongan</th>
                            <th>Total Gaji</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->user->name }}<br><small class="text-muted">{{ $payroll->user->email }}</small></td>
                            <td>{{ $payroll->jabatan ?? '-' }}</td>
                            <td class="text-end">{{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($payroll->uang_makan, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($payroll->tunjangan, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($payroll->lembur, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($payroll->potongan, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">{{ number_format($payroll->total_gaji, 0, ',', '.') }}</td>
                            <td>{{ $payroll->keterangan ?? '-' }}</td>
                            <td>
                                {{-- Tombol untuk membuka modal --}}
                                <a href="{{ route('admin.payroll.slip', $payroll->id) }}" class="btn btn-sm btn-outline-info" target="_blank" title="Lihat Slip Gaji">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-primary edit-payroll" 
                                    data-id="{{ $payroll->id }}"
                                    data-jabatan="{{ $payroll->jabatan }}"
                                    data-gaji_pokok="{{ $payroll->gaji_pokok }}"
                                    data-uang_makan="{{ $payroll->uang_makan }}"
                                    data-tunjangan="{{ $payroll->tunjangan }}"
                                    data-lembur="{{ $payroll->lembur }}"
                                    data-potongan="{{ $payroll->potongan }}"
                                    data-keterangan="{{ $payroll->keterangan }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-payroll" 
                                    data-id="{{ $payroll->id }}"
                                    data-name="{{ $payroll->user->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">Belum ada data payroll untuk periode ini. Silakan tambah.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Sertakan Modal di sini --}}
@include('payroll.modal-detail')

<!-- Modal Edit -->
<div class="modal fade" id="editPayrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editPayrollForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="edit_gaji_pokok" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Uang Makan</label>
                        <input type="number" name="uang_makan" id="edit_uang_makan" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Tunjangan</label>
                        <input type="number" name="tunjangan" id="edit_tunjangan" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Lembur</label>
                        <input type="number" name="lembur" id="edit_lembur" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Potongan</label>
                        <input type="number" name="potongan" id="edit_potongan" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Delete -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Edit
    const editModal = new bootstrap.Modal(document.getElementById('editPayrollModal'));
    const editForm = document.getElementById('editPayrollForm');
    document.querySelectorAll('.edit-payroll').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('edit_jabatan').value = this.dataset.jabatan || '';
            document.getElementById('edit_gaji_pokok').value = this.dataset.gaji_pokok;
            document.getElementById('edit_uang_makan').value = this.dataset.uang_makan;
            document.getElementById('edit_tunjangan').value = this.dataset.tunjangan;
            document.getElementById('edit_lembur').value = this.dataset.lembur;
            document.getElementById('edit_potongan').value = this.dataset.potongan;
            document.getElementById('edit_keterangan').value = this.dataset.keterangan || '';
            editForm.action = `/admin/payroll/${id}`;
            editModal.show();
        });
    });
    // Delete
    const deleteFormElem = document.getElementById('deleteForm');
    document.querySelectorAll('.delete-payroll').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm(`Hapus gaji ${this.dataset.name}?`)) {
                deleteFormElem.action = `/admin/payroll/${this.dataset.id}`;
                deleteFormElem.submit();
            }
        });
    });
</script>
@endpush
@endsection
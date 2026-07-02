@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid px-0">
    <div class="welcome-section mb-2">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="p-2">
                <i class="bi bi-universal-access-circle fs-2 text-absensi"></i>
            </div>
            <div>
                <h6 class="fw-semibold text-dark mb-0">Kelola Pengguna</h6>
                <p class="text-absensi mb-0 mt-0 py-0">Daftar semua pekerja dan tambah pengguna baru</p>
            </div>
        </div>
   </div>

    <!-- Card Tabel Pengguna -->
    <div class="card border-0 shadow-sm p-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-3">{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'pengelola' ? 'danger' : 'success' }} bg-opacity-10 text-{{ $user->role === 'pengelola' ? 'danger' : 'success' }} px-3 py-1 rounded-pill">
                                    {{ ucfirst($user->role) }}
                                </span>
                             </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($user->role !== 'pengelola')
                                    <!-- Tombol View (Detail) -->
                                    <button class="btn btn-sm btn-outline-info rounded-circle view-user" 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-created="{{ $user->created_at->format('d M Y H:i') }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-sm btn-outline-primary rounded-circle edit-user" 
                                            data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <!-- Tombol Delete -->
                                    <button class="btn btn-sm btn-outline-danger rounded-circle delete-user" 
                                            data-id="{{ $user->id }}" 
                                            data-name="{{ $user->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Belum ada data pengguna</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 pt-3 pb-3 d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


<!-- Modal Detail Pengguna (View) -->
<div class="modal fade" id="viewUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-transparent border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-person-badge me-2 text-absensi"></i>Detail Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div class="text-center mb-3">
                    <div class="mb-1">
                        <i class="bi bi-person-circle text-absensi" style="font-size: 2rem"></i>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                            <div class="icon-circle-sm bg-primary bg-opacity-10">
                                <i class="bi bi-person-fill text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Lengkap</small>
                                <strong id="view_name" class="fs-6"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                            <div class="icon-circle-sm bg-info bg-opacity-10">
                                <i class="bi bi-envelope-fill text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat Email</small>
                                <strong id="view_email" class="fs-6"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                            <div class="icon-circle-sm bg-success bg-opacity-10">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Role</small>
                                <span id="view_role" class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                            <div class="icon-circle-sm bg-warning bg-opacity-10">
                                <i class="bi bi-calendar-plus-fill text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Terdaftar Sejak</small>
                                <strong id="view_created" class="fs-6"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-transparent border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-pencil-square me-2 text-absensi"></i>Edit Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
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

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="createUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-transparent border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold"><i class="bi bi-person-plus me-2 text-absensi"></i>Tambah Pekerja Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-0" placeholder="Masukkan nama" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-0" placeholder="email@example.com" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-0" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>
                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle"></i> Role akan otomatis sebagai <strong>Pekerja</strong>.
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-semibold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data absensi pengguna terkait.</p>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal View User
    const viewModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
    document.querySelectorAll('.view-user').forEach(btn => {
        btn.addEventListener('click', function() {
            const name = this.dataset.name;
            const email = this.dataset.email;
            const role = this.dataset.role;
            const created = this.dataset.created;
            
            document.getElementById('view_name').innerText = name;
            document.getElementById('view_email').innerText = email;
            const roleSpan = document.getElementById('view_role');
            roleSpan.innerText = role === 'pengelola' ? 'Pengelola (Admin)' : 'Pekerja';
            roleSpan.className = `badge ${role === 'pengelola' ? 'bg-danger' : 'bg-success'} bg-opacity-10 text-${role === 'pengelola' ? 'danger' : 'success'} px-3 py-1 rounded-pill`;
            document.getElementById('view_created').innerText = created;
            
            viewModal.show();
        });
    });

    // Modal edit user
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    const editForm = document.getElementById('editUserForm');
    const editName = document.getElementById('edit_name');
    const editEmail = document.getElementById('edit_email');

    document.querySelectorAll('.edit-user').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            editName.value = name;
            editEmail.value = email;
            editForm.action = `/admin/users/${userId}`;
            editModal.show();
        });
    });
</script>
@endpush
@push('scripts')
<script>
    // Delete user confirmation
    const deleteButtons = document.querySelectorAll('.delete-user');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const deleteUserNameSpan = document.getElementById('deleteUserName');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            deleteUserNameSpan.innerText = userName;
            deleteForm.action = `/admin/users/${userId}`;
            deleteModal.show();
        });
    });
</script>
@endpush
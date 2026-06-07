@auth
<aside class="sidebar">
<div class="sidebar-brand d-flex align-items-center gap-2">
    <i class="bi bi-check2-square text-absensi" style="font-size: 2rem"></i>
    <div>
        <span>
        <div class="fw-bold text-dark mb-0 py-0">Absensi</div>
        <div class="small text-muted py-0 mt-0" style="font-size: 0.75rem">Sistem Karyawan</div>
        </span>
    </div>
</div>
    <ul class="sidebar-menu">
        @if(auth()->user()->role === 'pekerja')
            <li>
                <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') && !request()->routeIs('absensi.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> <span>Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('absensi.riwayat') }}" class="{{ request()->routeIs('absensi.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> <span>Riwayat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('slip.gaji') }}" class="{{ request()->routeIs('slip.gaji') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> <span>Slip Gaji</span>
                </a>
            </li>
        @elseif(auth()->user()->role === 'pengelola')
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.absensi') }}" class="{{ request()->routeIs('admin.absensi') ? 'active' : '' }}">
                    <i class="bi bi-table"></i> <span>Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.rekap') }}" class="{{ request()->routeIs('admin.rekap') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-steps"></i> <span>Rekap Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.payroll.index') }}" class="{{ request()->routeIs('admin.payroll.*') ? 'active' : '' }}">
                    <i class="bi bi-calculator-fill"></i> <span>Payroll Gaji</span>
                </a>
            </li>

        @endif
        <li>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="bi bi-box-arrow-left"></i> <span>Logout</span>
                </a>
            </form>
        </li>
    </ul>
    <!-- Tombol collapse hanya untuk desktop -->
    <button class="sidebar-toggle-desktop">
        <i class="bi bi-chevron-left" id="collapseIcon"></i>
    </button>
</aside>

<script>
    // Ubah icon panah saat collapse
    const toggleBtn = document.querySelector('.sidebar-toggle-desktop');
    const collapseIcon = document.getElementById('collapseIcon');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isCollapsed = document.querySelector('.sidebar').classList.contains('collapsed');
            if (isCollapsed) {
                collapseIcon.classList.remove('bi-chevron-right');
                collapseIcon.classList.add('bi-chevron-left');
            } else {
                collapseIcon.classList.remove('bi-chevron-left');
                collapseIcon.classList.add('bi-chevron-right');
            }
        });
    }
</script>
@endauth
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Absensi')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS (opsional) -->
<style>
    /* Root variables - warna pastel lembut */
    :root {
        --primary-pastel: #e9c46a;   /* kuning pastel */
        --secondary-pastel: #f4a261; /* oranye pastel */
        --success-pastel: #90be6d;   /* hijau pastel */
        --info-pastel: #6c9ebf;      /* biru pastel */
        --warning-pastel: #e9c46a;   /* kuning */
        --danger-pastel: #e76f51;    /* merah pastel */
        --light-bg: #fef9e8;         /* putih tulang */
        --card-bg: #ffffff;
        --text-muted: #7f8c8d;
        --text-dark: #5c4b2c;
        --sidebar-width: 170px;
        --sidebar-collapsed-width: 65px;
    }

    body {
        background: #f5f0e6;  /* putih tulang solid, tidak gradien mencolok */
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        font-size: 0.9rem;   /* ukuran font dasar */
        font-weight: 500;
        color: var(--text-dark);
        min-height: 100vh;
    }

    /* ========== SIDEBAR MELAYANG (DESKTOP) ========== */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: rgba(255, 253, 245, 0.96);
        backdrop-filter: blur(8px);
        box-shadow: 2px 0 12px rgba(0,0,0,0.05);
        z-index: 1040;
        transition: width 0.3s ease;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid rgba(233, 196, 106, 0.3);
    }

    /* Sidebar collapsed (hanya ikon) */
    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar.collapsed .sidebar-brand span,
    .sidebar.collapsed .sidebar-menu a span {
        display: none;
    }

    .sidebar.collapsed .sidebar-brand i {
        margin-right: 0;
    }

    /* Brand */
    .sidebar-brand {
        /*display: flex;*/
        align-items: center;
        justify-content: center;
        gap: 8px;
        /*font-size: 1rem;*/
        font-weight: 600;
        padding: 1rem 0.5rem;
        border-bottom: 1px solid rgba(233, 196, 106, 0.4);
        margin-bottom: 1rem;
        color: var(--secondary-pastel);
        white-space: nowrap;
    }

    .sidebar-brand i {
        font-size: 1.4rem;
    }

    /* Menu */
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 0.15rem;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0.45rem 0.7rem;
        color: #6b5a3e;
        text-decoration: none;
        transition: all 0.2s;
        border-radius: 0 16px 16px 0;
        margin-right: 0.30rem;
        white-space: nowrap;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .sidebar-menu a i {
        width: 24px;
        font-size: 1.1rem;
        min-width: 24px;
        color: var(--secondary-pastel);
    }

    .sidebar-menu a:hover {
        background: rgba(244, 162, 97, 0.15);
        color: var(--secondary-pastel);
    }

    .sidebar-menu a.active {
        background: var(--primary-pastel);
        color: #fff;
    }
    .sidebar-menu a.active i {
        color: #fff;
    }

    /* Tombol collapse desktop */
    .sidebar-toggle-desktop {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(244, 162, 97, 0.7);
        border: none;
        border-radius: 30px;
        padding: 4px 12px;
        color: white;
        cursor: pointer;
        font-size: 0.8rem;
        z-index: 1050;
        transition: all 0.2s;
    }
    .sidebar-toggle-desktop:hover {
        background: var(--secondary-pastel);
    }

    /* Konten utama */
    .main-content {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.2s ease;
        padding: 14px 19px;
    }

    /* Navbar top (ringan) */
    .navbar-top {
        /*background: var(--card-bg);*/
        /*border-radius: 16px;*/
        padding: 0.3rem 0.6rem;
        margin-bottom: 0.5rem;
        /*box-shadow: 0 1px 8px rgba(0,0,0,0.03);*/
        display: flex;
        justify-content: space-between;
        align-items: center;
        /*border: 1px solid rgba(233, 196, 106, 0.3);*/
    }

    .burger-btn {
        display: none;
        background: none;
        border: none;
        font-size: 1.6rem;
        color: var(--secondary-pastel);
        cursor: pointer;
    }

    /* Button styles pastel */
    .btn-primary {
        background-color: var(--secondary-pastel);
        border-color: var(--secondary-pastel);
        color: #fff;
        font-size: 0.9rem;
        padding: 0.4rem 1rem;
        border-radius: 30px;
    }
    .btn-primary:hover {
        background-color: #e2852e;
        border-color: #e2852e;
    }
    .btn-success {
        background-color: var(--success-pastel);
        border-color: var(--success-pastel);
        font-size: 0.9rem;
        border-radius: 30px;
    }
    .btn-warning {
        background-color: var(--primary-pastel);
        border-color: var(--primary-pastel);
        color: #5c4b2c;
        font-size: 0.9rem;
        border-radius: 30px;
    }

    /* Card styling */
    .card {
        background-color: var(--card-bg);
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: all 0.2s;
    }
    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(233, 196, 106, 0.3);
        font-weight: 600;
    }
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* Ikon lingkaran pastel */
    .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background-color: rgba(244, 162, 97, 0.15);
        border: 1px solid rgba(244, 162, 97, 0.4);
        border-radius: 50%;
        color: var(--secondary-pastel);
        transition: all 0.2s ease;
    }

    /* Responsive Mobile */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            /*width: 260px;*/
            transition: transform 0.3s ease;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0 !important;
            padding: 15px;
        }
        .burger-btn {
            display: block;
        }
        .sidebar-toggle-desktop {
            display: none;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            z-index: 1035;
        }
        .overlay.show {
            display: block;
        }
        .navbar-top {
            flex-direction: row;
        }
    }

    /* Utility */
    .text-absensi {
        color: var(--secondary-pastel);
    }
    .bg-absensi-light {
        background-color: rgba(244, 162, 97, 0.1);
    }
    hr {
        background-color: rgba(233, 196, 106, 0.3);
    }
</style>
    @stack('styles')
</head>
<body>
    @auth
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Overlay untuk mobile -->
        <div class="overlay" id="sidebarOverlay"></div>
    @endauth

    <div class="main-content" id="mainContent">
        @auth
            <div class="navbar-top">
                <button class="burger-btn" id="burgerBtn">
                    <i class="bi bi-list"></i>
                </button>
                <!--<div>
                    <span class="badge  badge-warning py-1 text-success">{{ ucfirst(auth()->user()->role) }}</span>
                </div>-->
            </div>
        @endauth

        <!-- Flash messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @auth
        // Sidebar desktop collapse
        const sidebar = document.querySelector('.sidebar');
        const toggleDesktop = document.querySelector('.sidebar-toggle-desktop');
        if (toggleDesktop) {
            toggleDesktop.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                document.getElementById('mainContent').style.marginLeft = sidebar.classList.contains('collapsed') ? '72px' : '260px';
            });
        }

        // Mobile burger & overlay
        const burgerBtn = document.getElementById('burgerBtn');
        const overlay = document.getElementById('sidebarOverlay');
        if (burgerBtn) {
            burgerBtn.addEventListener('click', () => {
                sidebar.classList.add('open');
                overlay.classList.add('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        }
        @endauth
    </script>
    @stack('scripts')
</body>
</html>
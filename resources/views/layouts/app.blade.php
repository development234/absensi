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
    <style>
        /* Root variables - Modern Blue Sky Theme 2026 */
        :root {
            /* Primary Colors - Blue Sky */
            --primary-pastel: #4DB8D6;      /* sky blue utama */
            --secondary-pastel: #2E9FCC;    /* deeper blue */
            
            /* Status Colors - Modern & Vibrant */
            --success-pastel: #5CB8A4;      /* mint green - fresh */
            --info-pastel: #6B9BC4;         /* soft denim blue */
            --warning-pastel: #F0C27A;      /* warm amber */
            --danger-pastel: #E88A7A;       /* coral pink */
            
            /* Background & Cards */
            --light-bg: #f2fcfd;            /* very soft sky blue */
            --card-bg: #FFFFFF;             /* pure white for cards */
            
            /* Text Colors - No black, all blue/navy tones */
            --text-muted: #6A8FA8;          /* muted steel blue */
            --text-dark: #02456b;           /* deep navy blue (not black) */
            --text-menu: #2C6B8F;           /* medium blue for menu */
            --text-primary: #1A5A7A;        /* rich blue */
            
            /* Sidebar */
            --sidebar-width: 170px;
            --sidebar-collapsed-width: 65px;
            
            /* Additional Modern Accents 2026 */
            --accent-glow: #4DB8D6;
            --shadow-color: rgba(45, 140, 180, 0.12);
            --border-color: rgba(77, 184, 214, 0.2);
            --glow-primary: rgba(77, 184, 214, 0.25);
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(45, 140, 180, 0.08);
            --shadow-md: 0 4px 16px rgba(45, 140, 180, 0.10);
            --shadow-lg: 0 8px 32px rgba(45, 140, 180, 0.14);
            
            /* Background hover */
            --bg-card-hover: #F0F8FC;
            --bg-hover-light: rgba(77, 184, 214, 0.08);

            /*pagenation style*/
            --bs-pagination-color:#2378a3;
        }
        li, a, .page-item, .page-link{
                color:#2673a0;
        }

        /*==============Table Stell==================*/
        .table-center th {
            text-align: center !important;
            vertical-align: middle !important;
        }
        .table-center td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        body {
            background: var(--light-bg);
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            font-size: 0.9rem;
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: var(--shadow-md);
            z-index: 1040;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            border-right: 1px solid var(--border-color);
        }

        /* ========== SIDEBAR HOVER EFFECT ========== */
        .sidebar:hover {
            box-shadow: var(--shadow-lg);
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

        /* ========== SCROLLBAR SIDEBAR ========== */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-pastel);
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-pastel);
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 700;
            padding: 1.2rem 0.8rem;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 1.2rem;
            color: var(--text-primary);
            white-space: nowrap;
            font-size: 1.05rem;
            letter-spacing: -0.3px;
        }

        .sidebar-brand i {
            font-size: 1.6rem;
            color: var(--primary-pastel);
            background: var(--bg-hover-light);
            padding: 8px;
            border-radius: 12px;
        }

        /* Menu */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.1rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.55rem 0.9rem;
            margin: 0.15rem 0.7rem;
            color: var(--text-menu);
            text-decoration: none;
            transition: all 0.25s ease;
            border-radius: 12px;
            white-space: nowrap;
            font-size: 0.85rem;
            font-weight: 500;
            position: relative;
        }

        .sidebar-menu a i {
            width: 24px;
            font-size: 1.15rem;
            min-width: 24px;
            color: var(--text-muted);
            transition: all 0.25s ease;
        }

        .sidebar-menu a:hover {
            background: var(--bg-hover-light);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .sidebar-menu a:hover i {
            color: var(--primary-pastel);
        }

        .sidebar-menu a.active {
            background: var(--primary-pastel);
            color: #FFFFFF;
            box-shadow: 0 4px 12px var(--glow-primary);
        }

        .sidebar-menu a.active i {
            color: #FFFFFF;
        }

        /* Tombol collapse desktop */
        .sidebar-toggle-desktop {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--text-primary);
            border: none;
            border-radius: 30px;
            padding: 6px 16px;
            color: white;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1050;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }
        
        .sidebar-toggle-desktop:hover {
            background: var(--secondary-pastel);
            transform: translateX(-50%) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        /* Konten utama */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            padding: 16px 22px;
        }

        /* Navbar top (ringan) */
        .navbar-top {
            padding: 0.4rem 0.8rem;
            margin-bottom: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
        }

        .burger-btn {
            display: none;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 8px 14px;
            font-size: 1.4rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .burger-btn:hover {
            background: var(--bg-hover-light);
            transform: scale(1.02);
        }

        /* Button styles - Modern Blue */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pastel), var(--secondary-pastel));
            border: none;
            color: #fff;
            font-size: 0.9rem;
            padding: 0.5rem 1.4rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(77, 184, 214, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(77, 184, 214, 0.4);
        }
        
        .btn-success {
            background-color: var(--success-pastel);
            border: none;
            color: #fff;
            font-size: 0.9rem;
            padding: 0.5rem 1.4rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(92, 184, 164, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(92, 184, 164, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-pastel), #E8B060);
            border: none;
            color: #1A4A66;
            font-size: 0.9rem;
            padding: 0.5rem 1.4rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(240, 194, 122, 0.3);
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240, 194, 122, 0.4);
        }

        /* Card styling - lebih cerah dari background */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
            /*transform: translateY(-2px);*/
        }
        
        .card-header {
            background: transparent;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
        }
        
        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
        
        .badge-primary {
            background: var(--bg-hover-light);
            color: var(--text-primary);
        }
        
        .badge-success {
            background: rgba(92, 184, 164, 0.15);
            color: var(--success-pastel);
        }
        
        .badge-warning {
            background: rgba(240, 194, 122, 0.2);
            color: #B8860B;
        }
        
        .badge-danger {
            background: rgba(232, 138, 122, 0.15);
            color: var(--danger-pastel);
        }

        /* Ikon lingkaran - Blue theme */
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: var(--bg-hover-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--primary-pastel);
            transition: all 0.3s ease;
        }
        
        .icon-circle:hover {
            background: var(--primary-pastel);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px var(--glow-primary);
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                background: rgba(255, 255, 255, 0.98);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                padding: 12px 15px;
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
                background: rgba(26, 74, 102, 0.4);
                backdrop-filter: blur(4px);
                z-index: 1035;
            }
            .overlay.show {
                display: block;
            }
            .navbar-top {
                flex-direction: row;
                padding: 0.3rem 0;
            }
        }

        /* Utility */
        .text-absensi {
            color: var(--text-primary);
        }
        .bg-absensi-light {
            background: var(--bg-hover-light);
        }
        
        hr {
            background: var(--border-color);
            opacity: 0.5;
        }
        
        /* Tambahan untuk konsistensi */
        .text-primary {
            color: var(--text-primary) !important;
        }
        
        .text-secondary {
            color: var(--text-muted) !important;
        }
        
        .text-dark {
            color: var(--text-dark) !important;
        }
        
        .bg-light {
            background: var(--light-bg) !important;
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <style>
        /* ========================================
           ROOT VARIABLES - Modern Blue Sky 2026
           ======================================== */
        :root {
            /* Primary Colors - Blue Sky */
            --primary-pastel: #4DB8D6;
            --secondary-pastel: #2E9FCC;
            
            /* Status Colors */
            --success-pastel: #5CB8A4;
            --info-pastel: #6B9BC4;
            --warning-pastel: #F0C27A;
            --danger-pastel: #E88A7A;
            
            /* Background & Cards */
            --light-bg: #f2fcfd;
            --card-bg: #FFFFFF;
            
            /* Text Colors - No black */
            --text-muted: #6A8FA8;
            --text-dark: #02456b;
            --text-menu: #2C6B8F;
            --text-primary: #1A5A7A;
            
            /* Shadows & Effects */
            --shadow-color: rgba(45, 140, 180, 0.12);
            --border-color: rgba(77, 184, 214, 0.2);
            --glow-primary: rgba(77, 184, 214, 0.25);
            --shadow-sm: 0 2px 8px rgba(45, 140, 180, 0.08);
            --shadow-md: 0 4px 16px rgba(45, 140, 180, 0.10);
            --shadow-lg: 0 8px 32px rgba(45, 140, 180, 0.14);
            
            /* Hover */
            --bg-card-hover: #F0F8FC;
            --bg-hover-light: rgba(77, 184, 214, 0.08);
            
            /* Overlay Opacity */
            --overlay-opacity: 0.75;
        }

        /* ========================================
           BODY STYLES WITH BACKGROUND IMAGE
           ======================================== */
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-weight: 500;
            color: var(--text-dark);
            font-display: swap;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            overflow: hidden;
            
            /* Background - City & Construction Theme */
            background-image: 
                linear-gradient(rgba(238, 243, 247, 0.9), rgba(238, 243, 247, 0.9)),
                url('/assets/images/city-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

        }

        /* Overlay untuk efek soft - meningkatkan readability */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /*background: linear-gradient(135deg, 
                rgba(60, 106, 110, 0.92) 0%,
                rgba(42, 81, 85, 0.85) 50%,
                rgba(242, 252, 253, 0.92) 100%
            );*/
            z-index: 0;
        }

        /* Background Decoration Bubbles - Modern 2026 */
        body::after {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 500px;
            height: 500px;
            /*background: radial-gradient(circle, rgba(77, 184, 214, 0.06) 0%, transparent 90%);*/
            border-radius: 50%;
            z-index: 1;
            animation: floatGlow 8s ease-in-out infinite;
        }

        @keyframes floatGlow {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* ========================================
           DECORATIVE ELEMENTS - Office Theme
           ======================================== */
        .bg-decoration {
            position: absolute;
            z-index: 1;
            opacity: 0.06;
            pointer-events: none;
        }

        .bg-decoration .building {
            position: absolute;
            border: 2px solid var(--primary-pastel);
            border-radius: 4px;
        }

        .bg-decoration .building-1 {
            width: 60px;
            height: 120px;
            top: 10%;
            left: 5%;
        }

        .bg-decoration .building-1 .window {
            width: 12px;
            height: 16px;
            background: var(--primary-pastel);
            border-radius: 2px;
            margin: 4px;
            display: inline-block;
            opacity: 0.3;
        }

        .bg-decoration .building-2 {
            width: 45px;
            height: 90px;
            top: 20%;
            right: 8%;
            border-color: var(--secondary-pastel);
        }

        .bg-decoration .construction-icon {
            position: absolute;
            font-size: 3rem;
            color: var(--primary-pastel);
            opacity: 0.08;
        }

        .bg-decoration .construction-icon-1 {
            bottom: 15%;
            left: 10%;
            transform: rotate(-15deg);
        }

        .bg-decoration .construction-icon-2 {
            top: 25%;
            right: 15%;
            transform: rotate(20deg);
            font-size: 4rem;
        }

        /* ========================================
           AUTH CARD - Modern Glassmorphism
           ======================================== */
        .auth-card {
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* Card Glow Accent */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-pastel), var(--secondary-pastel), var(--primary-pastel));
            background-size: 200% 100%;
            animation: gradientMove 3s ease-in-out infinite;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .auth-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(45, 140, 180, 0.18);
        }

        /* ========================================
           AUTH HEADER
           ======================================== */
        .auth-header {
            background: transparent;
            border-bottom: none;
            text-align: center;
            padding: 2rem 2rem 0.5rem 2rem;
        }

        .auth-header .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-pastel), var(--secondary-pastel));
            border-radius: 20px;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 24px var(--glow-primary);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .auth-header .brand-icon::after {
            content: '';
            position: absolute;
            font-size: 0.8rem;
            bottom: -8px;
            right: -8px;
            background: white;
            border-radius: 50%;
            padding: 2px;
            box-shadow: var(--shadow-sm);
        }

        .auth-header .brand-icon:hover {
            transform: scale(1.05) rotate(-5deg);
            box-shadow: 0 12px 32px rgba(77, 184, 214, 0.35);
        }

        .auth-header .brand-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }

        .auth-header .brand-title span {
            color: var(--primary-pastel);
        }

        .auth-header .brand-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 0.25rem;
            font-weight: 400;
        }

        .auth-header .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-pastel), var(--secondary-pastel));
            border-radius: 10px;
            margin: 0.75rem auto;
        }

        /* ========================================
           AUTH BODY
           ======================================== */
        .auth-body {
            padding: 1.5rem 2rem 2rem 2rem;
        }

        /* ========================================
           AUTH FOOTER
           ======================================== */
        .auth-footer {
            background: transparent;
            border-top: 1px solid var(--border-color);
            text-align: center;
            padding: 1rem 2rem 1.5rem 2rem;
        }

        .auth-footer small {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.8rem;
        }

        .auth-footer .brand-name {
            color: var(--primary-pastel);
            font-weight: 600;
        }

        /* ========================================
           FORM ELEMENTS - Modern Style
           ======================================== */
        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--primary-pastel);
            box-shadow: 0 0 0 4px var(--glow-primary);
            background: var(--card-bg);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
            opacity: 0.7;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .input-group-text {
            background: var(--bg-hover-light);
            border: 2px solid var(--border-color);
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--text-muted);
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .input-group .form-control:focus {
            border-left: none;
        }

        /* ========================================
           BUTTONS - Gradient Modern
           ======================================== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pastel), var(--secondary-pastel));
            border: none;
            color: #fff;
            font-size: 0.95rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px var(--glow-primary);
            width: 100%;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(77, 184, 214, 0.4);
            color: #fff;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-pastel);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-pastel);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ========================================
           ALERT STYLES
           ======================================== */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 0.85rem 1.25rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(92, 184, 164, 0.12);
            color: #2d8a7a;
            border-left: 4px solid var(--success-pastel);
        }

        .alert-danger {
            background: rgba(232, 138, 122, 0.12);
            color: #c9766a;
            border-left: 4px solid var(--danger-pastel);
        }

        .alert ul {
            padding-left: 1.2rem;
            margin-bottom: 0;
        }

        .alert ul li {
            list-style: disc;
        }

        /* ========================================
           LINKS
           ======================================== */
        .auth-link {
            color: var(--text-primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 2px solid transparent;
        }

        .auth-link:hover {
            color: var(--primary-pastel);
            border-bottom-color: var(--primary-pastel);
        }

        /* ========================================
           ICON CIRCLE
           ======================================== */
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: var(--bg-hover-light);
            border: 2px solid var(--border-color);
            border-radius: 14px;
            color: var(--primary-pastel);
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .icon-circle:hover {
            background: var(--primary-pastel);
            color: white;
            border-color: var(--primary-pastel);
            transform: scale(1.05) rotate(-5deg);
            box-shadow: var(--shadow-md);
        }

        /* ========================================
           RESPONSIVE - Mobile First
           ======================================== */
        @media (max-width: 768px) {
            .auth-card {
                border-radius: 20px;
                margin: 0 12px;
            }

            .auth-header {
                padding: 1.5rem 1.25rem 0.5rem 1.25rem;
            }

            .auth-header .brand-icon {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
                border-radius: 16px;
            }

            .auth-header .brand-title {
                font-size: 1.5rem;
            }

            .auth-body {
                padding: 1.25rem 1.25rem 1.5rem 1.25rem;
            }

            .auth-footer {
                padding: 0.75rem 1.25rem 1.25rem 1.25rem;
            }

            .form-control {
                font-size: 0.85rem;
                padding: 0.65rem 0.9rem;
            }

            .btn-primary {
                font-size: 0.9rem;
                padding: 0.65rem 1.25rem;
            }

            .bg-decoration {
                display: none;
            }

            body::before {
                background: rgba(242, 252, 253, 0.95);
            }

            body::after {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .auth-header .brand-title {
                font-size: 1.3rem;
            }

            .auth-header .brand-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }

            .auth-body {
                padding: 1rem 1rem 1.25rem 1rem;
            }

            .auth-footer small {
                font-size: 0.7rem;
            }
        }

        /* ========================================
           ANIMATIONS
           ======================================== */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* ========================================
           CUSTOM CHECKBOX / RADIO
           ======================================== */
        .form-check-input {
            border-radius: 6px;
            border: 2px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-pastel);
            border-color: var(--primary-pastel);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px var(--glow-primary);
            border-color: var(--primary-pastel);
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.85rem;
        }

        /* ========================================
           CONSTRUCTION THEME DECORATIONS
           ======================================== */
        .construction-pattern {
            position: absolute;
            z-index: 0;
            opacity: 0.03;
            pointer-events: none;
            font-size: 8rem;
            color: var(--primary-pastel);
        }

        .construction-pattern-1 {
            top: 5%;
            right: 5%;
            transform: rotate(15deg);
        }

        .construction-pattern-2 {
            bottom: 5%;
            left: 5%;
            transform: rotate(-10deg);
            font-size: 6rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Background Decorations -->
    <div class="bg-decoration">
        <!-- Building silhouettes -->
        <div class="building building-1">
            <span class="window"></span><span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span><span class="window"></span>
        </div>
        <div class="building building-2">
            <span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span><br>
            <span class="window"></span><span class="window"></span>
        </div>
        
        <!-- Construction icons -->
        <i class="bi bi-building construction-icon construction-icon-1"></i>
        <i class="bi bi-tools construction-icon construction-icon-2"></i>
    </div>

    <!-- Construction Pattern Decorations -->
    <div class="construction-pattern construction-pattern-1">
        <i class="bi bi-home"></i>
    </div>
    <div class="construction-pattern construction-pattern-2">
        <i class="bi bi-wrench"></i>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card auth-card border-0">
                    <!-- Header -->
                    <div class="card-header auth-header">
                        <div class="brand-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h1 class="brand-title">
                            <span>Absensi</span>SELFI
                        </h1>
                        <div class="divider"></div>
                        <p class="brand-subtitle">
                            <i class="bi bi-shield-check me-1"></i>
                            @yield('subtitle', 'Silakan login untuk melanjutkan')
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="card-body auth-body">
                        <!-- Alert Messages -->
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Content -->
                        @yield('content')
                    </div>

                    <!-- Footer -->
                    <div class="card-footer auth-footer mt-0">
                        <small>
                            &copy; {{ date('Y') }} 
                            <span class="brand-name">AbsensiSELFI</span> 
                            <span class="mx-1">•</span> 
                            By <strong>KinBlackid</strong>
                            <span class="mx-1">@ 2026</span>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
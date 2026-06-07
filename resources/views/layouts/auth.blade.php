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
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <style>
        .btn-primary{
            background: #fc9208;
        }
        .text-absensi{
            color:#693901;
        }
        .btn-absensi{
            background: linear-gradient(180deg, #f5c3af 0%, #f3984e 100%);
            color: #f8f5f4;
            padding: 10px 20px;
            border: 0px;
            border-radius: 8px;
        }
        body {
            /*background: linear-gradient(180deg, #f3a034 0%, #472101 100%);*/
            background:#f8f5f4;
            min-height: 100vh;
            display: flex;
            color:#4b2c03;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-weight: 500;        /* tegas tapi tidak terlalu bold */
            font-display: swap;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .auth-card {
            border-radius: 1rem;
            box-shadow: 0 1rem 2rem rgba(121, 119, 110, 0.1);
            backdrop-filter: blur(10px);
            background-color: rgba(253, 249, 249, 0.95);
        }
        .auth-header {
            background: transparent;
            border-bottom: none;
            text-align: center;
            padding: 2rem 2rem 0 2rem;
        }
        .auth-body {
            padding: 2rem;
        }
        .auth-footer {
            background: transparent;
            border-top: none;
            text-align: center;
            padding: 0 2rem 2rem 2rem;
        }

        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: #702b03;      /* Warna background */
            border: 2px solid #f87a03;      /* Border warna biru, bisa disesuaikan */
            border-radius: 50%;              /* Membuat lingkaran */
            color: #ac4602;                  /* Warna ikon */
            transition: all 0.2s ease;
        }

        /* Efek hover opsional */
        .icon-circle:hover {
            background-color: #ffa927;
            color: white;
            border-color: #f75105;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card border-0">
                    <div class="card-header auth-header">
                        <h3 class="mb-2 text-absensi fw-bold">
                            <span class="icon-circle" style="border-color: currentColor; background-color: rgba(108, 99, 255, 0.1);">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </span>

                        </h3>
                        <h4 class="mb-0"> <span class="text-danger fw-bold">Absensi</span>SELFI</h4>
                        <p class="text-muted mt-0 border-top fst-itali">Silakan login untuk melanjutkan</p>
                    </div>
                    <div class="card-body auth-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                    <div class="card-footer auth-footer text-muted">
                        <small>&copy; {{ date('Y') }} <span class="text-danger">Absensi</span>SELFI By: KinBlackid</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
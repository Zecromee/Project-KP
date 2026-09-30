<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - WPCL Admin Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --kai-blue: #002B66;
            --kai-orange: #FF7300;
            --kai-light-blue: #E8F1FC;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .navbar-kai {
            background: linear-gradient(135deg, var(--kai-blue) 0%, #001a40 100%);
            box-shadow: 0 4px 12px rgba(0, 43, 102, 0.15);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .card-header-kai {
            background-color: #fff;
            border-bottom: 2px solid var(--kai-light-blue);
            font-weight: 600;
            color: var(--kai-blue);
        }
        .btn-kai-primary {
            background-color: var(--kai-blue);
            color: #fff;
            border: none;
        }
        .btn-kai-primary:hover {
            background-color: #001f4d;
            color: #fff;
        }
        .btn-kai-orange {
            background-color: var(--kai-orange);
            color: #fff;
            border: none;
        }
        .btn-kai-orange:hover {
            background-color: #e66700;
            color: #fff;
        }
        .badge-status-baik {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            font-weight: 600;
        }
        .badge-status-rusak {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            font-weight: 600;
        }
        .badge-status-tiada {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
            font-weight: 600;
        }
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            color: #6c757d;
            font-size: 0.875rem;
            text-align: center;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-kai sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('web.dashboard') }}">
                <img src="{{ asset('images/logo_kai.png') }}" alt="KAI Logo" style="height: 28px; width: auto; object-fit: contain; filter: brightness(0) invert(1);">
                <span class="ms-1">WPCL <span class="badge bg-warning text-dark fs-6">Admin Portal</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('web.dashboard') ? 'active' : '' }}" href="{{ route('web.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('web.pemeriksaan.*') ? 'active' : '' }}" href="{{ route('web.pemeriksaan.index') }}">
                            <i class="bi bi-clipboard-check me-1"></i> Monitoring Pemeriksaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('web.stamformasi.*') ? 'active' : '' }}" href="{{ route('web.stamformasi.index') }}">
                            <i class="bi bi-train-freight-front me-1"></i> Sarana & Formasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('web.pejabat.*') ? 'active' : '' }}" href="{{ route('web.pejabat.index') }}">
                            <i class="bi bi-person-badge me-1"></i> Pejabat
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">© 2026 <strong>WPCL (Workstation Passenger Check List)</strong> - Depo Kereta Yogyakarta / PT Kereta Api Indonesia (Persero).</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

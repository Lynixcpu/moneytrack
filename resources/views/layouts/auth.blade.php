<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Autentikasi') - MoneyTrack</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom MoneyTrack CSS -->
    <link href="{{ asset('css/moneytrack.css') }}" rel="stylesheet">
</head>
<body class="auth-body">

    <div class="container d-flex justify-content-center">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo-box">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h2 class="fw-bold text-navy mb-1" style="color: #172B4D; letter-spacing: -0.02em;">MoneyTrack</h2>
                <p class="text-muted small mb-0">"Kelola Uangmu, Atur Masa Depanmu."</p>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

            <div class="text-center mt-4 pt-2 border-top">
                <small class="text-muted">MoneyTrack &copy; {{ date('Y') }} &bull; Solusi Keuangan Pelajar & Mahasiswa</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- MoneyTrack JS -->
    <script src="{{ asset('js/moneytrack.js') }}"></script>
    @stack('scripts')
</body>
</html>


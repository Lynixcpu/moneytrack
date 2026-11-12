<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - MoneyTrack</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- MoneyTrack Theme CSS -->
    <link href="{{ asset('css/moneytrack.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <div class="sidebar-overlay"></div>

    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon-box">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="brand-text">
                    <h1>MoneyTrack</h1>
                    <p>Financial Tracker</p>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-section-title">Menu Utama</li>
                
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Transaksi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('transactions.history') }}" class="nav-link {{ request()->routeIs('transactions.history') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                        <i class="bi bi-pie-chart"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>

                <li class="nav-section-title">Pengaturan Akun</li>

                <li class="nav-item">
                    <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Profil Pengguna</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="submit" class="btn-sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main-wrapper">
            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-sm btn-light d-lg-none" id="sidebarToggleBtn" aria-label="Toggle Navigation">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="mb-0 fw-bold" style="color: #172B4D;">@yield('page_title', 'Dashboard')</h5>
                        <small class="text-muted d-none d-sm-inline-block">
                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Quick Add Button -->
                    <button type="button" class="btn btn-mt-primary btn-sm d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGlobalAddTransaction">
                        <i class="bi bi-plus-lg"></i>
                        <span class="d-none d-sm-inline">Catat Transaksi</span>
                    </button>

                    <!-- User Info & Avatar -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-block text-start">
                                <span class="d-block fw-semibold text-truncate" style="max-width: 140px; font-size: 0.88rem; color: #172B4D;">
                                    {{ Auth::user()->name }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Pelajar/Mahasiswa</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenuDropdown">
                            <li class="px-3 py-2 border-bottom">
                                <span class="d-block fw-bold text-dark small">{{ Auth::user()->name }}</span>
                                <span class="d-block text-muted small text-truncate" style="max-width: 200px;">{{ Auth::user()->email }}</span>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 small" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person me-2 text-primary"></i> Pengaturan Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 small" href="{{ route('reports.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph me-2 text-success"></i> Laporan Keuangan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 small text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Body Content -->
            <main class="content-body">
                {{-- Flash Notifications --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                            <strong>Terdapat kesalahan pada input Anda:</strong>
                        </div>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-top py-3 px-4 text-center text-sm-start text-muted small mt-auto no-print">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>
                        <strong>MoneyTrack</strong> &copy; {{ date('Y') }} &bull; "Kelola Uangmu, Atur Masa Depanmu."
                    </div>
                    <div>
                        Dirancang untuk Pelajar & Mahasiswa
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Quick Global Add Transaction Modal -->
    <div class="modal fade" id="modalGlobalAddTransaction" tabindex="-1" aria-labelledby="modalGlobalAddTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom pb-3" style="background-color: #F8F9FD;">
                    <h5 class="modal-title fw-bold" id="modalGlobalAddTitle" style="color: #172B4D;">
                        <i class="bi bi-plus-circle me-1" style="color: #16A36A;"></i> Catat Transaksi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transactions.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="type" id="global_type_income" value="income" autocomplete="off" checked>
                                <label class="btn btn-outline-success flex-fill py-2" for="global_type_income">
                                    <i class="bi bi-arrow-down-left-circle me-1"></i> Pemasukan
                                </label>

                                <input type="radio" class="btn-check" name="type" id="global_type_expense" value="expense" autocomplete="off">
                                <label class="btn btn-outline-danger flex-fill py-2" for="global_type_expense">
                                    <i class="bi bi-arrow-up-right-circle me-1"></i> Pengeluaran
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="global_title" class="form-label">Judul Transaksi <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="global_title" class="form-control" placeholder="Contoh: Kiriman Bulanan, Makan Siang" required>
                        </div>

                        <div class="mb-3">
                            <label for="global_category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="global_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="global_amount" class="form-label">Nominal (Rupiah) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                <input type="text" name="amount" id="global_amount" class="form-control" placeholder="50.000" onkeyup="formatRupiahInput(this)" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="global_transaction_date" class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" id="global_transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-2">
                            <label for="global_description" class="form-label">Catatan / Deskripsi (Opsional)</label>
                            <textarea name="description" id="global_description" rows="2" class="form-control" placeholder="Tambahkan catatan singkat mengenai transaksi ini..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top pt-3 bg-light">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-mt-primary">
                            <i class="bi bi-save me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js 4.4.2 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <!-- MoneyTrack Core JS -->
    <script src="{{ asset('js/moneytrack.js') }}"></script>

    @php
        $globalIncomeCategories = \App\Models\Category::availableFor(Auth::id())->income()->orderBy('name')->get(['id', 'name']);
        $globalExpenseCategories = \App\Models\Category::availableFor(Auth::id())->expense()->orderBy('name')->get(['id', 'name']);
    @endphp

    <script>
        // Global Categories for Quick Add Modal
        const globalIncomeCats = @json($globalIncomeCategories);
        const globalExpenseCats = @json($globalExpenseCategories);

        function updateGlobalCategories(type) {
            const select = document.getElementById('global_category_id');
            if (!select) return;
            select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            const list = type === 'income' ? globalIncomeCats : globalExpenseCats;
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                select.appendChild(opt);
            });
        }

        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateGlobalCategories(this.value);
            });
        });

        // Initialize default
        updateGlobalCategories('income');
    </script>

    @stack('scripts')
</body>
</html>


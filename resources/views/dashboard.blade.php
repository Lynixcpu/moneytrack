@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Keuangan')

@section('content')
<!-- Welcome Banner -->
<div class="mt-card p-4 mb-4" style="background: linear-gradient(135deg, #172B4D 0%, #263445 100%); color: white; border: none;">
    <div class="row align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <span class="badge bg-success bg-opacity-25 text-white mb-2 px-3 py-1 text-uppercase fw-semibold" style="letter-spacing: 0.05em; font-size: 0.75rem;">
                <i class="bi bi-stars me-1 text-warning"></i> Keuangan Pelajar & Mahasiswa
            </span>
            <h3 class="fw-bold mb-1">{{ $greeting }}, {{ $user->name }}!</h3>
            <p class="text-white-50 mb-0">
                Pantau arus kas harianmu, raih kebebasan finansial sejak masa kuliah.
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-mt-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalGlobalAddTransaction">
                <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
            </button>
        </div>
    </div>
</div>

<!-- 4 Summary Metric Cards -->
<div class="row g-3 mb-4">
    <!-- 1. Total Saldo -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Saldo Tersedia</span>
                <div class="metric-icon-bubble icon-bubble-balance">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <h3 class="metric-value {{ $balance < 0 ? 'text-danger' : 'text-navy' }}">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext">
                <i class="bi bi-calculator me-1"></i> Pemasukan &minus; Pengeluaran
            </p>
        </div>
    </div>

    <!-- 2. Total Pemasukan -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Total Pemasukan</span>
                <div class="metric-icon-bubble icon-bubble-income">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <h3 class="metric-value text-success">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-success">
                <i class="bi bi-check-circle me-1"></i> Arus kas masuk kumulatif
            </p>
        </div>
    </div>

    <!-- 3. Total Pengeluaran -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Total Pengeluaran</span>
                <div class="metric-icon-bubble icon-bubble-expense">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <h3 class="metric-value text-danger">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-danger">
                <i class="bi bi-clock-history me-1"></i> Arus kas keluar kumulatif
            </p>
        </div>
    </div>

    <!-- 4. Total Transaksi -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Total Transaksi</span>
                <div class="metric-icon-bubble icon-bubble-count">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <h3 class="metric-value" style="color: #4f46e5;">
                {{ number_format($transactionCount, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-muted">
                <i class="bi bi-check2-all me-1"></i> Catatan tersimpan di sistem
            </p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Income vs Expense Bar Chart -->
    <div class="col-12 col-lg-8">
        <div class="mt-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #172B4D;">Arus Kas 6 Bulan Terakhir</h5>
                    <small class="text-muted">Perbandingan pemasukan vs pengeluaran per bulan</small>
                </div>
                <span class="badge bg-light text-dark border">
                    <i class="bi bi-bar-chart-fill text-success me-1"></i> Interaktif
                </span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Expense Donut Chart -->
    <div class="col-12 col-lg-4">
        <div class="mt-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #172B4D;">Kategori Pengeluaran</h5>
                    <small class="text-muted">Proporsi pos belanja Anda</small>
                </div>
                <span class="badge bg-light text-dark border">
                    <i class="bi bi-pie-chart-fill text-danger me-1"></i> Distribusi
                </span>
            </div>
            @if(count($categoryLabels) > 0)
                <div style="height: 250px; position: relative;" class="my-auto">
                    <canvas id="categoryDonutChart"></canvas>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-pie-chart fs-1 text-muted opacity-50 d-block mb-2"></i>
                    <p class="small mb-0">Belum ada data transaksi pengeluaran.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Transactions Card -->
<div class="mt-card mb-0">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold mb-0" style="color: #172B4D;">Transaksi Terbaru</h5>
            <small class="text-muted">5 aktivitas keuangan yang terakhir dicatat</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                Kelola Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    @if($recentTransactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-moneytrack align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Transaksi</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $item)
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">
                                <i class="bi bi-calendar-event me-1"></i> {{ $item->formatted_date }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->title }}</div>
                                @if($item->description)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;">
                                        {{ $item->description }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-category">
                                    {{ $item->category->name }}
                                </span>
                            </td>
                            <td>
                                @if($item->category->type === 'income')
                                    <span class="badge badge-income">
                                        <i class="bi bi-arrow-down-left me-1"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="badge badge-expense">
                                        <i class="bi bi-arrow-up-right me-1"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $item->category->type === 'income' ? 'text-amount-income' : 'text-amount-expense' }}">
                                {{ $item->category->type === 'income' ? '+' : '-' }} {{ $item->formatted_amount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
            </div>
            <h6 class="fw-bold text-dark">Belum ada transaksi yang dicatat</h6>
            <p class="text-muted small mb-3">Mulai catat pemasukan atau pengeluaran pertamamu sekarang juga.</p>
            <button type="button" class="btn btn-mt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalGlobalAddTransaction">
                <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi Pertama
            </button>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Monthly Trend Bar Chart
        const trendCtx = document.getElementById('monthlyTrendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: @json($monthlyIncome),
                            backgroundColor: '#16A36A',
                            borderRadius: 6,
                            maxBarThickness: 32,
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: @json($monthlyExpense),
                            backgroundColor: '#E85D5D',
                            borderRadius: 6,
                            maxBarThickness: 32,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                font: { family: 'Inter', size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + ' Jt';
                                    if (value >= 1000) return (value / 1000) + ' Rb';
                                    return value;
                                },
                                font: { family: 'Inter' }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            ticks: { font: { family: 'Inter' } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Expense Category Donut Chart
        const donutCtx = document.getElementById('categoryDonutChart');
        if (donutCtx && @json(count($categoryLabels)) > 0) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        data: @json($categoryTotals),
                        backgroundColor: [
                            '#E85D5D', '#F59E0B', '#3B82F6', '#8B5CF6', 
                            '#EC4899', '#14B8A6', '#64748B', '#06B6D4'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { family: 'Inter', size: 11 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let val = context.raw || 0;
                                    return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(val);
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }
    });
</script>
@endpush


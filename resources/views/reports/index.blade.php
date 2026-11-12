@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page_title', 'Laporan & Analisis Keuangan')

@section('content')
<!-- Header & Period Filter Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #172B4D;">Laporan Finansial: {{ $periodTitle }}</h4>
        <p class="text-muted small mb-0">Evaluasi kesehatan arus kas dan kebiasaan pengeluaran Anda.</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap no-print">
        <!-- Quick Period Buttons -->
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('reports.index', ['period' => 'this_month']) }}" class="btn {{ $period === 'this_month' ? 'btn-mt-navy' : 'btn-outline-secondary' }}">
                Bulan Ini
            </a>
            <a href="{{ route('reports.index', ['period' => 'last_month']) }}" class="btn {{ $period === 'last_month' ? 'btn-mt-navy' : 'btn-outline-secondary' }}">
                Bulan Lalu
            </a>
            <a href="{{ route('reports.index', ['period' => 'this_year']) }}" class="btn {{ $period === 'this_year' ? 'btn-mt-navy' : 'btn-outline-secondary' }}">
                Tahun Ini
            </a>
            <a href="{{ route('reports.index', ['period' => 'all']) }}" class="btn {{ $period === 'all' ? 'btn-mt-navy' : 'btn-outline-secondary' }}">
                Semua
            </a>
        </div>

        <!-- Print Button -->
        <button type="button" class="btn btn-outline-dark btn-sm d-flex align-items-center gap-1" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- Custom Date Filter Modal / Collapse (no-print) -->
<div class="mt-card p-3 mb-4 no-print">
    <form action="{{ route('reports.index') }}" method="GET" class="row g-2 align-items-center">
        <input type="hidden" name="period" value="custom">
        <div class="col-12 col-sm-auto">
            <span class="small fw-semibold text-muted"><i class="bi bi-calendar-range me-1"></i> Rentang Kustom:</span>
        </div>
        <div class="col-6 col-sm-auto">
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" required>
        </div>
        <div class="col-auto text-muted small">s/d</div>
        <div class="col-6 col-sm-auto">
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" required>
        </div>
        <div class="col-12 col-sm-auto">
            <button type="submit" class="btn btn-mt-navy btn-sm">Tampilkan</button>
        </div>
    </form>
</div>

<!-- Summary Metric Cards for Report -->
<div class="row g-3 mb-4">
    <!-- Pemasukan Periode -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Pemasukan Periode</span>
                <div class="metric-icon-bubble icon-bubble-income">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <h3 class="metric-value text-success">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-muted">
                {{ $incomeByCategory->sum('count') }} transaksi masuk
            </p>
        </div>
    </div>

    <!-- Pengeluaran Periode -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Pengeluaran Periode</span>
                <div class="metric-icon-bubble icon-bubble-expense">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <h3 class="metric-value text-danger">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-muted">
                {{ $expenseByCategory->sum('count') }} transaksi keluar
            </p>
        </div>
    </div>

    <!-- Saldo Bersih Periode -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Saldo Bersih</span>
                <div class="metric-icon-bubble icon-bubble-balance">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <h3 class="metric-value {{ $netBalance >= 0 ? 'text-primary' : 'text-danger' }}">
                Rp {{ number_format($netBalance, 0, ',', '.') }}
            </h3>
            <p class="metric-subtext text-muted">
                Pemasukan &minus; Pengeluaran
            </p>
        </div>
    </div>

    <!-- Rasio Tabungan / Efisiensi -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="mt-card metric-card h-100">
            <div class="metric-header">
                <span class="metric-title">Rasio Tabungan</span>
                <div class="metric-icon-bubble" style="background-color: #ecfdf5; color: #059669;">
                    <i class="bi bi-piggy-bank"></i>
                </div>
            </div>
            <h3 class="metric-value" style="color: #059669;">
                {{ $savingsRate }}%
            </h3>
            <p class="metric-subtext text-muted">
                Persentase dana tersisa
            </p>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Trend Chart -->
    <div class="col-12 col-lg-8">
        <div class="mt-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #172B4D;">Grafik Arus Kas ({{ $periodTitle }})</h5>
                    <small class="text-muted">Pergerakan pemasukan dan pengeluaran pada rentang waktu ini</small>
                </div>
            </div>
            <div style="height: 310px; position: relative;">
                <canvas id="reportTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Expense Donut Chart -->
    <div class="col-12 col-lg-4">
        <div class="mt-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0" style="color: #172B4D;">Sebaran Beban Pengeluaran</h5>
                    <small class="text-muted">Porsi biaya per kategori belanja</small>
                </div>
            </div>
            @if($expenseByCategory->count() > 0)
                <div style="height: 250px; position: relative;" class="my-auto">
                    <canvas id="reportCategoryDonut"></canvas>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-pie-chart fs-1 text-muted opacity-50 d-block mb-2"></i>
                    <p class="small mb-0">Tidak ada pengeluaran tercatat pada periode ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Category Breakdown Table -->
<div class="mt-card mb-4">
    <h5 class="fw-bold mb-1" style="color: #172B4D;">Rincian Pengeluaran per Kategori</h5>
    <p class="text-muted small mb-3">Alokasi anggaran berdasarkan kategori kebutuhan Anda.</p>

    @if($expenseByCategory->count() > 0)
        <div class="table-responsive">
            <table class="table table-moneytrack align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kategori Pengeluaran</th>
                        <th>Jumlah Transaksi</th>
                        <th style="width: 35%;">Persentase Anggaran</th>
                        <th class="text-end">Total Biaya (IDR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenseByCategory as $cat)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <span class="badge badge-category me-2">{{ $cat['name'] }}</span>
                            </td>
                            <td>{{ $cat['count'] }} kali</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $cat['percentage'] }}%;" aria-valuenow="{{ $cat['percentage'] }}" aria-valuemin="0" aria-valuemmax="100"></div>
                                    </div>
                                    <span class="small fw-semibold text-muted" style="width: 45px;">{{ $cat['percentage'] }}%</span>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-danger">
                                Rp {{ number_format($cat['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted small mb-0">Belum ada pengeluaran untuk ditampilkan.</p>
    @endif
</div>

<!-- Itemized Transactions in this Period -->
<div class="mt-card mb-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0" style="color: #172B4D;">Semua Transaksi dalam Periode Ini</h5>
            <small class="text-muted">Total {{ $transactions->count() }} transaksi tercatat</small>
        </div>
    </div>

    @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-moneytrack align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Transaksi</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Catatan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">
                                {{ $trx->formatted_date }}
                            </td>
                            <td class="fw-semibold text-dark">{{ $trx->title }}</td>
                            <td>
                                <span class="badge badge-category">{{ $trx->category->name }}</span>
                            </td>
                            <td>
                                @if($trx->category->type === 'income')
                                    <span class="badge badge-income">Pemasukan</span>
                                @else
                                    <span class="badge badge-expense">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $trx->description ?: '-' }}</td>
                            <td class="text-end fw-bold {{ $trx->category->type === 'income' ? 'text-amount-income' : 'text-amount-expense' }}">
                                {{ $trx->category->type === 'income' ? '+' : '-' }} {{ $trx->formatted_amount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4 text-muted small">
            Tidak ada transaksi pada periode yang dipilih.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Report Trend Chart (Line / Bar)
        const trendCtx = document.getElementById('reportTrendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: @json($chartIncome),
                            backgroundColor: '#16A36A',
                            borderRadius: 6,
                            maxBarThickness: 32,
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: @json($chartExpense),
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
                            ticks: { font: { family: 'Inter', size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Report Category Donut Chart
        const donutCtx = document.getElementById('reportCategoryDonut');
        if (donutCtx && @json($expenseByCategory->count()) > 0) {
            const expLabels = @json($expenseByCategory->pluck('name'));
            const expTotals = @json($expenseByCategory->pluck('total'));

            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: expLabels,
                    datasets: [{
                        data: expTotals,
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


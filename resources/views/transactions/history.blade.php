@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Seluruh Transaksi')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #172B4D;">Arsip & Riwayat Transaksi</h4>
        <p class="text-muted small mb-0">Telusuri seluruh catatan finansial dengan filter multi-kriteria yang fleksibel.</p>
    </div>
    <div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Buka Laporan Lengkap
        </a>
    </div>
</div>

<!-- Comprehensive Filter Panel -->
<div class="mt-card p-4 mb-4">
    <form action="{{ route('transactions.history') }}" method="GET" autocomplete="off">
        <div class="row g-3">
            <!-- Search Keyword -->
            <div class="col-12 col-md-4">
                <label for="search" class="form-label">Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Cari judul atau catatan..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Transaction Type -->
            <div class="col-6 col-md-2">
                <label for="type" class="form-label">Jenis</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <!-- Category -->
            <div class="col-6 col-md-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    <optgroup label="Pemasukan">
                        @foreach($incomeCategories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Pengeluaran">
                        @foreach($expenseCategories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Sort By -->
            <div class="col-12 col-md-3">
                <label for="sort" class="form-label">Urutan Waktu</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>Tanggal Terbaru (Desc)</option>
                    <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Tanggal Terlama (Asc)</option>
                </select>
            </div>

            <!-- Date Range: Start Date -->
            <div class="col-6 col-md-3">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <!-- Date Range: End Date -->
            <div class="col-6 col-md-3">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <!-- Action Buttons -->
            <div class="col-12 col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-mt-navy flex-fill py-2">
                    <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('transactions.history') }}" class="btn btn-light border py-2 px-3 text-muted" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Filter Summary Strip -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="mt-card p-3 mb-0 text-center">
            <span class="text-muted small d-block mb-1">Hasil Ditemukan</span>
            <h5 class="fw-bold mb-0 text-dark">{{ $totalCount }} Transaksi</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mt-card p-3 mb-0 text-center">
            <span class="text-muted small d-block mb-1">Total Pemasukan Filter</span>
            <h5 class="fw-bold mb-0 text-success">+ Rp {{ number_format($filteredIncome, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mt-card p-3 mb-0 text-center">
            <span class="text-muted small d-block mb-1">Total Pengeluaran Filter</span>
            <h5 class="fw-bold mb-0 text-danger">- Rp {{ number_format($filteredExpense, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="mt-card p-3 mb-0 text-center">
            <span class="text-muted small d-block mb-1">Selisih Filter</span>
            <h5 class="fw-bold mb-0 {{ $filteredNet >= 0 ? 'text-primary' : 'text-danger' }}">
                Rp {{ number_format($filteredNet, 0, ',', '.') }}
            </h5>
        </div>
    </div>
</div>

<!-- History Transactions Table -->
<div class="mt-card p-0 overflow-hidden mb-4">
    @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-moneytrack align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 140px;">Tanggal</th>
                        <th>Judul Transaksi</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Catatan / Keterangan</th>
                        <th class="text-end" style="width: 180px;">Nominal (IDR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $trx->formatted_date }}
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $trx->title }}</span>
                            </td>
                            <td>
                                <span class="badge badge-category">
                                    {{ $trx->category->name }}
                                </span>
                            </td>
                            <td>
                                @if($trx->category->type === 'income')
                                    <span class="badge badge-income">
                                        <i class="bi bi-arrow-down-left me-1"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="badge badge-expense">
                                        <i class="bi bi-arrow-up-right me-1"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $trx->description ?: '-' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold {{ $trx->category->type === 'income' ? 'text-amount-income' : 'text-amount-expense' }}">
                                {{ $trx->category->type === 'income' ? '+' : '-' }} {{ $trx->formatted_amount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $transactions->firstItem() }} s/d {{ $transactions->lastItem() }} dari {{ $transactions->total() }} data riwayat
                </small>
                <div>
                    {{ $transactions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            <h6 class="fw-bold text-dark mt-2">Tidak ditemukan data transaksi yang sesuai</h6>
            <p class="text-muted small mb-3">Cobalah melonggarkan filter atau memilih rentang tanggal yang lebih luas.</p>
            <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Bersihkan Filter
            </a>
        </div>
    @endif
</div>
@endsection


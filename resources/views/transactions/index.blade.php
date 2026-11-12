@extends('layouts.app')

@section('title', 'Manajemen Transaksi')
@section('page_title', 'Kelola Transaksi')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #172B4D;">Daftar Transaksi</h4>
        <p class="text-muted small mb-0">Kelola dan atur setiap catatan pemasukan dan pengeluaran Anda.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-mt-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddTransaction">
            <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
        </button>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="mt-card p-3 mb-4">
    <form action="{{ route('transactions.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Search query -->
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul transaksi atau catatan..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter by Type -->
        <div class="col-6 col-md-3">
            <select name="type" class="form-select">
                <option value="">Semua Jenis Transaksi</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        <!-- Filter by Category -->
        <div class="col-6 col-md-2">
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->type === 'income' ? 'Masuk' : 'Keluar' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Buttons -->
        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-mt-navy flex-fill">
                <i class="bi bi-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'type', 'category_id']))
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Transactions Table Card -->
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
                        <th class="text-end" style="width: 170px;">Nominal (IDR)</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">
                                <i class="bi bi-calendar-event me-1"></i> {{ $trx->formatted_date }}
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $trx->title }}</div>
                                @if($trx->description)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                        {{ $trx->description }}
                                    </small>
                                @endif
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
                            <td class="text-end fw-bold {{ $trx->category->type === 'income' ? 'text-amount-income' : 'text-amount-expense' }}">
                                {{ $trx->category->type === 'income' ? '+' : '-' }} {{ $trx->formatted_amount }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-edit-transaction" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditTransaction"
                                            data-id="{{ $trx->id }}"
                                            data-title="{{ $trx->title }}"
                                            data-type="{{ $trx->category->type }}"
                                            data-category-id="{{ $trx->category_id }}"
                                            data-amount="{{ $trx->amount }}"
                                            data-date="{{ $trx->transaction_date->format('Y-m-d') }}"
                                            data-desc="{{ $trx->description }}"
                                            title="Ubah Transaksi">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete-transaction" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDeleteTransaction"
                                            data-id="{{ $trx->id }}"
                                            data-title="{{ $trx->title }}"
                                            title="Hapus Transaksi">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
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
                    Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </small>
                <div>
                    {{ $transactions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <h6 class="fw-bold text-dark mt-2">Tidak ada transaksi yang cocok</h6>
            <p class="text-muted small mb-3">Coba ubah kata kunci pencarian atau filter yang dipilih.</p>
            <button type="button" class="btn btn-mt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddTransaction">
                <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi Sekarang
            </button>
        </div>
    @endif
</div>

<!-- ==========================================================================
     MODAL: Tambah Transaksi
     ========================================================================== -->
<div class="modal fade" id="modalAddTransaction" tabindex="-1" aria-labelledby="modalAddTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom pb-3" style="background-color: #F8F9FD;">
                <h5 class="modal-title fw-bold" id="modalAddTitle" style="color: #172B4D;">
                    <i class="bi bi-plus-circle me-1" style="color: #16A36A;"></i> Tambah Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="type" id="add_type_income" value="income" autocomplete="off" checked>
                            <label class="btn btn-outline-success flex-fill py-2" for="add_type_income">
                                <i class="bi bi-arrow-down-left-circle me-1"></i> Pemasukan
                            </label>

                            <input type="radio" class="btn-check" name="type" id="add_type_expense" value="expense" autocomplete="off">
                            <label class="btn btn-outline-danger flex-fill py-2" for="add_type_expense">
                                <i class="bi bi-arrow-up-right-circle me-1"></i> Pengeluaran
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_title" class="form-label">Judul Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="add_title" class="form-control" placeholder="Contoh: Kiriman Bulanan, Uang Buku" required>
                    </div>

                    <div class="mb-3">
                        <label for="add_category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="add_category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="add_amount" class="form-label">Nominal (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="text" name="amount" id="add_amount" class="form-control" placeholder="100.000" onkeyup="formatRupiahInput(this)" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="add_transaction_date" class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" id="add_transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-2">
                        <label for="add_description" class="form-label">Catatan / Deskripsi (Opsional)</label>
                        <textarea name="description" id="add_description" rows="2" class="form-control" placeholder="Tambahkan catatan singkat (misal: warteg dekat kos, beasiswa semester)..."></textarea>
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

<!-- ==========================================================================
     MODAL: Edit Transaksi
     ========================================================================== -->
<div class="modal fade" id="modalEditTransaction" tabindex="-1" aria-labelledby="modalEditTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom pb-3" style="background-color: #F8F9FD;">
                <h5 class="modal-title fw-bold" id="modalEditTitle" style="color: #172B4D;">
                    <i class="bi bi-pencil-square me-1 text-primary"></i> Edit Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditTransaction" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select name="type" id="edit_type" class="form-select" required>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Judul Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="edit_category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">Nominal (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="text" name="amount" id="edit_amount" class="form-control" onkeyup="formatRupiahInput(this)" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_transaction_date" class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" id="edit_transaction_date" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label for="edit_description" class="form-label">Catatan / Deskripsi (Opsional)</label>
                        <textarea name="description" id="edit_description" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3 bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mt-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Perbarui Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL: Konfirmasi Hapus Transaksi
     ========================================================================== -->
<div class="modal fade" id="modalDeleteTransaction" tabindex="-1" aria-labelledby="modalDeleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Hapus Transaksi?</h5>
                <p class="text-muted small mb-3">
                    Apakah Anda yakin ingin menghapus transaksi <span id="deleteItemTitle" class="fw-bold text-dark"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="formDeleteTransaction" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-mt-danger flex-fill">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const incomeCats = @json($incomeCategories);
        const expenseCats = @json($expenseCategories);

        // Setup Add Modal categories
        function updateAddCategories(type) {
            const select = document.getElementById('add_category_id');
            if (!select) return;
            select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            const list = type === 'income' ? incomeCats : expenseCats;
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                select.appendChild(opt);
            });
        }

        document.querySelectorAll('#modalAddTransaction input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function () {
                updateAddCategories(this.value);
            });
        });

        // Initialize Add Modal
        updateAddCategories('income');

        // Setup Edit Modal category switcher
        window.editCategorySwitcher = function(type, selectedCatId) {
            const select = document.getElementById('edit_category_id');
            if (!select) return;
            select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            const list = type === 'income' ? incomeCats : expenseCats;
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                if (String(item.id) === String(selectedCatId)) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        };

        const editTypeEl = document.getElementById('edit_type');
        if (editTypeEl) {
            editTypeEl.addEventListener('change', function () {
                window.editCategorySwitcher(this.value, null);
            });
        }
    });
</script>
@endpush


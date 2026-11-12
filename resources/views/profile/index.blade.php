@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="row g-4">
    <!-- Left Column: User Summary Card -->
    <div class="col-12 col-lg-4">
        <div class="mt-card text-center p-4">
            <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1" style="color: #172B4D;">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->email }}</p>
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small">
                <i class="bi bi-mortarboard me-1"></i> Pelajar / Mahasiswa
            </span>

            <hr class="my-4">

            <div class="text-start">
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Bergabung Sejak:</span>
                    <span class="fw-semibold text-dark">{{ $memberSince }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Total Transaksi:</span>
                    <span class="fw-semibold text-dark">{{ $totalTransactions }} Catatan</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Status Akun:</span>
                    <span class="badge bg-success text-white">Aktif</span>
                </div>
            </div>
        </div>

        <div class="mt-card p-4">
            <h6 class="fw-bold mb-2" style="color: #172B4D;">
                <i class="bi bi-shield-lock me-1 text-primary"></i> Keamanan Akun
            </h6>
            <p class="small text-muted mb-0">
                Data keuangan Anda dienkripsi dan diproteksi. Jangan pernah membagikan kata sandi akun Anda kepada siapapun.
            </p>
        </div>
    </div>

    <!-- Right Column: Edit Profile & Password Forms -->
    <div class="col-12 col-lg-8">
        <!-- 1. Edit Profile Info -->
        <div class="mt-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-person-lines-fill fs-5 text-primary"></i>
                <h5 class="fw-bold mb-0" style="color: #172B4D;">Perbarui Informasi Profil</h5>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Email ini digunakan untuk login ke sistem MoneyTrack.</small>
                </div>

                <button type="submit" class="btn btn-mt-primary">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
                </button>
            </form>
        </div>

        <!-- 2. Change Password -->
        <div class="mt-card p-4">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-key-fill fs-5 text-warning"></i>
                <h5 class="fw-bold mb-0" style="color: #172B4D;">Perbarui Kata Sandi</h5>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan kata sandi lama Anda" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang kata sandi baru" required>
                </div>

                <button type="submit" class="btn btn-mt-navy">
                    <i class="bi bi-shield-check me-1"></i> Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


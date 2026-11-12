@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<h4 class="fw-bold mb-1" style="color: #172B4D;">Daftar Akun Baru</h4>
<p class="text-muted small mb-4">Mulai perjalanan mencatat keuanganmu hari ini.</p>

<form action="{{ route('register') }}" method="POST" autocomplete="off">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-person"></i>
            </span>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control border-start-0 @error('name') is-invalid @enderror" 
                   placeholder="Contoh: Ahmad Rizki" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus>
        </div>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email" 
                   name="email" 
                   id="email" 
                   class="form-control border-start-0 @error('email') is-invalid @enderror" 
                   placeholder="nama@email.com" 
                   value="{{ old('email') }}" 
                   required>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control border-start-0 @error('password') is-invalid @enderror" 
                   placeholder="Minimal 8 karakter" 
                   required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-shield-check"></i>
            </span>
            <input type="password" 
                   name="password_confirmation" 
                   id="password_confirmation" 
                   class="form-control border-start-0" 
                   placeholder="Ketik ulang kata sandi" 
                   required>
        </div>
    </div>

    <button type="submit" class="btn btn-mt-primary w-100 py-2 fs-6 mb-3">
        <i class="bi bi-person-plus me-1"></i> Buat Akun MoneyTrack
    </button>
</form>

<div class="text-center mt-3">
    <p class="small text-muted mb-0">Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: #16A36A;">Masuk di sini</a>
    </p>
</div>
@endsection


@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
<h4 class="fw-bold mb-1" style="color: #172B4D;">Masuk ke Akun</h4>
<p class="text-muted small mb-4">Silakan masukkan email dan kata sandi Anda.</p>

<form action="{{ route('login') }}" method="POST" autocomplete="off">
    @csrf

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
                   required 
                   autofocus>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
        </div>
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

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label small text-muted" for="remember">Ingat sesi saya di perangkat ini</label>
    </div>

    <button type="submit" class="btn btn-mt-primary w-100 py-2 fs-6 mb-3">
        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
    </button>
</form>

<div class="text-center mt-3">
    <p class="small text-muted mb-0">Belum memiliki akun? 
        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color: #16A36A;">Daftar Akun Baru</a>
    </p>
</div>

{{-- Demo Account Quick Helper for ease of testing --}}
<div class="card bg-light border-0 rounded-3 mt-4 p-3 text-start">
    <div class="d-flex align-items-center justify-content-between">
        <span class="badge bg-secondary">Akun Demo Mahasiswa</span>
        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;" onclick="fillDemo()">Gunakan</button>
    </div>
    <div class="small text-muted mt-2">
        <div><strong>Email:</strong> <code>mahasiswa@moneytrack.test</code></div>
        <div><strong>Password:</strong> <code>password123</code></div>
    </div>
</div>

@push('scripts')
<script>
    function fillDemo() {
        document.getElementById('email').value = 'mahasiswa@moneytrack.test';
        document.getElementById('password').value = 'password123';
    }
</script>
@endpush
@endsection


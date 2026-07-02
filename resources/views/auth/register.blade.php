@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-2">
        <label for="name" class="form-label mb-1">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" required>
        </div>
        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="mb-2">
        <label for="email" class="form-label">Alamat Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" required>
        </div>
        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="mb-2">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text p-0"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                   id="password" name="password" required>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <div class="mb-1">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-2"></i> Daftar
        </button>
        
    </div>
</form>

<div class="mt-1 text-center">
    <small>Sudah punya akun? <a href="{{ route('login') }}">Login</a></small>
</div>
@endsection
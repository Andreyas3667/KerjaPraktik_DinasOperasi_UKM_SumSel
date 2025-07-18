@extends('layout.auth')

@section('title', 'Login')

@section('content')
<div class="card shadow">
    <div class="card-header text-center bg-primary text-white">
        <h4>Login</h4>
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100">Log in</button>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}">Belum punya akun? Register</a>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ url('/') }}" class="btn btn-link">← Kembali ke Halaman Utama</a>
        </div>
        <div class="mt-3">
            <a href="{{ route('auth.google') }}" class="btn btn-danger" style="width:100%">
                <img src="https://developers.google.com/identity/images/g-logo.png" style="width:20px; margin-right:8px;">
                Login/Daftar dengan Google
            </a>
        </div>
    </div>
</div>
@endsection

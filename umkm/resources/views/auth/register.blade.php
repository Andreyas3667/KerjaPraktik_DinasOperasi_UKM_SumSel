@extends('layout.auth')

@section('title', 'Register')

@section('content')
<div class="card shadow">
    <div class="card-header text-center bg-primary text-white">
        <h4>Register</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- Error feedback -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input id="nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required autofocus>
            </div>
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>
            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>
            <!-- Telepon -->
            <div class="mb-3">
                <label for="telepon" class="form-label">Nomor Telepon</label>
                <input id="telepon" type="text" class="form-control" name="telepon" value="{{ old('telepon') }}" required>
            </div>
            <!-- Alamat -->
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input id="alamat" type="text" class="form-control" name="alamat" value="{{ old('alamat') }}" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="btn btn-link">← Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection

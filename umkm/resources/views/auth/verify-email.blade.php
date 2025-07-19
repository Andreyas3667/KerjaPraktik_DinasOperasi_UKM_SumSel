@extends('layout.auth') {{-- atau layout.main, sesuaikan dengan projectmu --}}

@section('title', 'Email Verifikasi')

@section('content')
<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width:400px;">
        <div class="card-body text-center">
            <h4 class="mb-3">Verifikasi Email</h4>
            <p>Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi.</p>
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
            </form>
        </div>
    </div>
</div>
@endsection

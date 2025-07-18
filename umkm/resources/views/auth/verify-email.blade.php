<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi.') }}
    </div>

    @if (session('message'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Kirim Ulang Email Verifikasi</button>
    </form>
</x-guest-layout>

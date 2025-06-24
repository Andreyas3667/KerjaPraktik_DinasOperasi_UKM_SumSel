<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; }
        .navbar-brand { font-weight: bold; }
        .table th, .table td { vertical-align: middle !important; }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white">UMKM</div>
        <div class="list-group list-group-flush">
            <a href="{{ route('umkm.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('umkm.produk') }}" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-box"></i> Stok Barang</a>
            <a href="{{ route('umkm.laporan') }}" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-chart-bar"></i> Laporan</a>
            <a href="{{ route('umkm.profile') }}" class="list-group-item list-group-item-action bg-dark text-white"><i class="fas fa-user"></i> Profile</a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <span class="navbar-brand">UMKM Dashboard</span>
            <div class="ml-auto">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm" type="submit">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @endauth
            </div>
        </nav>
        <div class="container-fluid mt-4">
            @yield('content')
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('js')
</body>
</html>
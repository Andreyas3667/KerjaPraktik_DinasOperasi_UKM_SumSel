<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title')</title>

    <!-- Favicon -->
    <link href="{{ asset('template/img/favicon.png') }}" rel="icon">

    <!-- CSS Files -->
    <link href="{{ asset('template/assets/vendor/bootstrap/css/bootstrap-grid.') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="{{ url('/') }}">UMKM Kopi</a></h1>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto" href="{{ url('/') }}">Home</a></li>
                    <li><a class="nav-link scrollto" href="{{ url('/maps') }}">Maps</a></li>
                    <li><a class="nav-link scrollto" href="{{ url('/profile') }}">Profile</a></li>
                    <li><a class="nav-link scrollto" href="{{ url('/news') }}">News</a></li>
                    @auth
                        <li><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link btn btn-link text-danger">Logout</button>
                            </form>
                        </li>
                    @else
                        <li><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                    @endauth
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; 2025 UMKM Kopi. All Rights Reserved
            </div>
        </div>
    </footer>

    <!-- JS Files -->
    <script src="{{ asset('template/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/main.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'UMKM Kopi')</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Vendor CSS Files (Tetap, jika file lokal tersedia) -->
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('umkm/assets/css/main.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Kopi Sriwijaya</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-link p-0" href="{{ route('login') }}" style="display:inline;cursor:pointer;">Login</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link p-0" style="display:inline;cursor:pointer;">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <main class="main">
        @yield('content')
    </main>

    <!-- Footer (Tetap, jangan dihapus) -->
    <footer id="footer" class="footer">
      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="d-flex align-items-center">
              <a href="https://maps.app.goo.gl/qugRpZk6HjU3aEAz8"><span class="sitename">Kopi Sriwijaya</span></a>
            </a>
            <div class="footer-contact pt-3">
              <p>Jl. Jend. Sudirman No.565, 20 Ilir D. III, Kec. Ilir Tim. I, Kota Palembang, Sumatera Selatan 30129</p>
              <p class="mt-3"><strong>Phone:</strong> <span>081360494195</span></p>
            </div>
          </div>
          <div class="col-lg-4 col-md-12">
            <h4>Follow Us</h4>
            <p>Follow social media for updates and further information</p>
            <div class="social-links d-flex">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/kopi.sriwijaya?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>
          <div class="col-lg-4 col-md-18">
            <a href="https://diskopukm.sumselprov.go.id/"><span class="sitename"><h3>Tentang Kami</h3> </span></a>
            <p>
                UMKM Kopi adalah platform yang membantu petani dan pelaku usaha kopi di Sumatera Selatan untuk terhubung dengan pasar yang lebih luas.
            </p>
            <ul>
                <li><i class="bi bi-check-circle"></i> Peta interaktif untuk menemukan UMKM.</li>
                <li><i class="bi bi-check-circle"></i> Informasi lengkap tentang setiap UMKM.</li>
                <li><i class="bi bi-check-circle"></i> Dukungan penjualan melalui WhatsApp.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">Andreyas & Fanny Krisvyanti</strong> <span>All Rights Reserved</span></p>
      </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <!-- Bootstrap JS & jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vendor JS Files (Tetap, jika file lokal tersedia) -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('umkm/assets/js/main.js') }}"></script>
    <script>
        // Hilangkan preloader setelah halaman selesai dimuat
        window.addEventListener('load', function() {
            var preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        });
    </script>
</body>
</html>

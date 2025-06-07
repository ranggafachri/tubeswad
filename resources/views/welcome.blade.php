<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TJMart - Minimarket Asrama Putra Telkom University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <style>
        :root {
            --primary-color: #800000;    /* Maroon */
            --secondary-color: #4b0000;   /* Dark Maroon */
            --accent-color: #b22222;      /* Red */
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }

        /* Navbar Custom Styles */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .hero-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 2rem;
            padding: 4rem 2rem;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            padding: 2rem;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            font-size: 28px;
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 40px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .about-section {
            background-color: white;
            padding: 4rem 0;
        }

        .stats-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--dark-color);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem 2rem;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        footer {
            background: white;
            padding: 2rem 0;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shop"></i>
                TJMart
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('products.catalog') }}" class="nav-link">
                                <i class="bi bi-basket"></i>Katalog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}" class="nav-link">
                                <i class="bi bi-person"></i>Profil
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="bi bi-box-arrow-in-right"></i>Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="bi bi-person-plus"></i>Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Hero Section -->
        <div class="hero-section text-center">
            <h1 class="display-4 fw-bold mb-4">Selamat Datang di TJMart</h1>
            <p class="lead mb-4">Minimarket Terlengkap di Lingkungan Asrama Putra Telkom University</p>
            @auth
                <a href="{{ route('products.catalog') }}" class="btn btn-primary">
                    <i class="bi bi-cart me-2"></i>Mulai Berbelanja
                </a>
            @else
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('login') }}" class="btn btn-primary me-md-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Daftar
                    </a>
                </div>
            @endauth
        </div>

        <!-- Features Section -->
        <div class="row mt-5 g-4">
            <div class="col-12">
                <h2 class="section-title">Mengapa TJMart?</h2>
            </div>
            
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h4 class="text-center mb-3">Lokasi Strategis</h4>
                    <p class="text-center text-muted">Terletak di lingkungan Asrama Putra Telkom University, mudah dijangkau oleh mahasiswa</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h4 class="text-center mb-3">Produk Lengkap</h4>
                    <p class="text-center text-muted">Menyediakan berbagai kebutuhan mahasiswa dari makanan, minuman, hingga kebutuhan sehari-hari</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h4 class="text-center mb-3">Kualitas Terjamin</h4>
                    <p class="text-center text-muted">Produk berkualitas dengan harga yang terjangkau untuk mahasiswa</p>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="about-section mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">Tentang TJMart</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <p class="lead mb-4">TJMart merupakan salah satu minimarket terlengkap yang terletak di lingkungan Asrama Putra Telkom University. Minimarket ini menjadi tempat yang sangat penting bagi mahasiswa untuk memenuhi berbagai kebutuhan sehari-hari mereka.</p>
                        <p class="mb-4">Kami menyediakan berbagai macam produk:</p>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Makanan berat (nasi goreng, mie, rice bowl)</li>
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Gorengan dari UMKM lokal</li>
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Berbagai jenis roti dan cemilan</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Minuman dingin berbagai merek</li>
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Kebutuhan alat tulis</li>
                                    <li><i class="bi bi-check2-circle text-primary me-2"></i>Perlengkapan asrama</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-4">
                            <div class="col-6 col-lg-12">
                                <div class="stats-card">
                                    <div class="stats-number">500+</div>
                                    <div class="stats-label">Produk Tersedia</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-12">
                                <div class="stats-card">
                                    <div class="stats-number">1000+</div>
                                    <div class="stats-label">Pelanggan Puas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center mt-5">
            <h2 class="mb-4">Siap untuk Berbelanja?</h2>
            <p class="lead mb-4">Temukan semua kebutuhan Anda dengan mudah dan cepat</p>
            @auth
                <a href="{{ route('products.catalog') }}" class="btn btn-primary">
                    <i class="bi bi-cart me-2"></i>Mulai Berbelanja
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-top">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; 2024 TJMart Asrama Putra Telkom University. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

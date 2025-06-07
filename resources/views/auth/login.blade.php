<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - TJMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <style>
        :root {
            --primary-color: #800000;       /* maroon */
            --secondary-color: #4d0000;     /* dark maroon */
            --accent-color: #a52a2a;        /* brownish red */
            --light-color: #fdf6f6;         /* very light pinkish */
            --dark-color: #3b1f1f;          /* dark brownish */
        }

        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
        }

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

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 3rem;
        }

        .login-title {
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .forgot-password-link {
            display: block;
            text-align: right;
            text-decoration: none;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .form-control {
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.15);
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--dark-color);
            text-decoration: none;
        }

        .register-link:hover {
            color: var(--primary-color);
        }

        .btn-danger {
            background-color: #800000;
            border-color: #800000;
        }
        .btn-danger:hover {
            background-color: #600000;
            border-color: #600000;
        }
        .text-danger {
            color: #800000 !important;
        }
        .text-danger:hover {
            color: #600000 !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-shop"></i> TJMart
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Login
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h1 class="h3">
                                <i class="bi bi-shop"></i> TJMart
                            </h1>
                            <p class="text-muted">Masuk ke Akun Anda</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Masukkan email Anda">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required autocomplete="current-password"
                                    placeholder="Masukkan kata sandi">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                Ingat Saya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="{{ route('password.request') }}" class="text-decoration-none text-danger">
                                            Lupa Kata Sandi?
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger py-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-decoration-none text-danger">
                                        Daftar sekarang
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

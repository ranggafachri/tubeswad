@extends('layouts.app')

@section('content')
<style>
    :root {
        --maroon-primary: #800000;
        --maroon-secondary: #4b0000;
        --maroon-accent: #b22222;
        --maroon-light: #f8f9fa;
        --maroon-dark-text: #3a0000;
    }

    .card-header.bg-primary {
        background-color: var(--maroon-primary) !important;
        color: #fff !important;
    }

    .btn-success {
        background-color: var(--maroon-accent);
        border-color: var(--maroon-accent);
        color: #fff;
    }

    .btn-success:hover,
    .btn-success:focus {
        background-color: var(--maroon-secondary);
        border-color: var(--maroon-secondary);
        color: #fff;
    }

    .btn-outline-secondary {
        border-color: var(--maroon-primary);
        color: var(--maroon-primary);
    }

    .btn-outline-secondary:hover {
        background-color: var(--maroon-primary);
        color: #fff;
    }

    .form-control:focus {
        border-color: var(--maroon-primary);
        box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.25);
    }
</style>

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center bg-light py-5">
    <div class="card shadow-sm" style="max-width: 600px; width: 100%;">
        <div class="card-header bg-primary text-white text-center fs-4 fw-semibold">
            Edit Profil
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama</label>
                    <input type="text" class="form-control rounded-pill" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control rounded-pill" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Telepon</label>
                    <input type="text" class="form-control rounded-pill" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" class="form-control rounded-pill" id="password" name="password">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" class="form-control rounded-pill" id="password_confirmation" name="password_confirmation">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

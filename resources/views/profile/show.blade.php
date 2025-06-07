@extends('layouts.app')

@section('content')
<style>
    :root {
        --maroon-primary: #800000;
        --maroon-secondary: #4b0000;
        --maroon-accent: #b22222;
        --maroon-light: #f8f9fa;
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
</style>

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center bg-light py-5">
    <div class="card shadow-sm" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-primary text-white text-center fs-4 fw-semibold">
            Profil Pengguna
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Nama:</span>
                    <span>{{ $user->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Email:</span>
                    <span>{{ $user->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Telepon:</span>
                    <span>{{ $user->phone }}</span>
                </li>
            </ul>

            <div class="d-grid gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-success btn-lg rounded-pill shadow-sm">
                    <i class="bi bi-pencil-square me-2"></i> Edit Profil
                </a>
                <a href="{{ route('products.catalog') }}" class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

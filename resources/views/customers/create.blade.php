@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-bold fs-5">
                    <i class="bi bi-person-plus me-2"></i> Tambah Pelanggan Baru
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('customers.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control rounded-pill" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control rounded-pill" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control rounded-pill" id="password" name="password" required>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control rounded-pill" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-check-circle me-2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

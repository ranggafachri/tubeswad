@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height: 80vh; background-color: #fdf6f6;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header text-white text-center fs-4 fw-semibold rounded-top-4" style="background-color: #800000;">
                    Edit Data Pelanggan
                </div>
                <div class="card-body px-4 py-5">
                    <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-dark">Nama</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ $customer->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ $customer->email }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold text-dark">Telepon</label>
                            <input type="text" class="form-control form-control-lg" id="phone" name="phone" value="{{ $customer->phone }}" required>
                        </div>
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-lg fw-bold text-white"
                                style="background: linear-gradient(90deg, #800000 0%, #4d0000 100%); border: none; box-shadow: 0 4px 15px rgba(128, 0, 0, 0.5);">
                                Perbarui Data
                            </button>
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-danger btn-lg rounded-pill shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

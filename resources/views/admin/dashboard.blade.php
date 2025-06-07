@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Admin Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <!-- Products Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-box-seam me-2"></i>
                        Manajemen Produk
                    </h5>
                    <p class="card-text">Kelola daftar produk yang tersedia di toko.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="bi bi-list me-2"></i>Lihat Semua Produk
                        </a>
                        <a href="{{ route('products.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Produk Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people me-2"></i>
                        Manajemen User
                    </h5>
                    <p class="card-text">Kelola data User yang terdaftar.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-primary">
                            <i class="bi bi-list me-2"></i>Lihat Semua User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-receipt me-2"></i>
                        Manajemen Transaksi
                    </h5>
                    <p class="card-text">Kelola dan pantau transaksi pelanggan.</p>
                    <div class="d-grid">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Statistik Cepat</h5>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h3 class="text-primary">{{ App\Models\Product::count() }}</h3>
                            <p class="text-muted">Total Produk</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">{{ App\Models\User::where('is_admin', false)->count() }}</h3>
                            <p class="text-muted">Total Pelanggan</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-info">{{ App\Models\Transaction::count() }}</h3>
                            <p class="text-muted">Total Transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
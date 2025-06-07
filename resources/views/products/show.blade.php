@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-box me-2"></i>Detail Produk
        </h2>
        <div>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded mb-3" 
                             style="max-height: 300px; object-fit: contain;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="height: 300px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Status Badge -->
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                @if($product->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div class="col-12">
                            <h3 class="mb-0">{{ $product->name }}</h3>
                        </div>

                        <!-- Barcode -->
                        @if($product->barcode)
                        <div class="col-12">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-barcode me-2"></i>
                                <span>{{ $product->barcode }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="fas fa-tag me-2"></i>Harga
                                    </h6>
                                    <h4 class="card-title mb-0">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="fas fa-boxes-stacked me-2"></i>Stok
                                    </h6>
                                    <h4 class="card-title mb-0">
                                        {{ number_format($product->stock, 0, ',', '.') }} unit
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <i class="fas fa-align-left me-2"></i>Deskripsi
                                    </h6>
                                    <p class="card-text">
                                        {{ $product->description ?: 'Tidak ada deskripsi' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between text-muted small">
                                <span>
                                    <i class="fas fa-clock me-1"></i>
                                    Dibuat: {{ $product->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span>
                                    <i class="fas fa-edit me-1"></i>
                                    Diperbarui: {{ $product->updated_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
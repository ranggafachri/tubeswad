@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter</h5>
                    
                    <form action="{{ route('products.catalog') }}" method="GET">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <div class="list-group">
                                <a href="{{ route('products.catalog', request()->except('category')) }}" 
                                   class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                    Semua Kategori
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.catalog', array_merge(request()->except('category'), ['category' => $category->id])) }}" 
                                       class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                        {{ $category->name }}
                                        <span class="badge bg-secondary float-end">
                                            {{ $category->products->where('is_active', true)->where('stock', '>', 0)->count() }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Katalog Produk</h2>
                <form action="{{ route('products.catalog') }}" method="GET" class="d-flex gap-2">
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="search" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari produk..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h3>Tidak Ada Produk</h3>
                    <p class="text-muted">Tidak ada produk yang tersedia saat ini.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($products as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $product->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    @if($product->category)
                                        <span class="badge bg-info mb-2">{{ $product->category->name }}</span>
                                    @endif
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-5 fw-bold text-primary">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-muted">
                                            Stok: {{ $product->stock }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <form action="{{ route('cart.add-item') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-cart-plus me-1"></i>Tambah ke Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.product-card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.quantity-input {
    width: 60px !important;
}
.add-to-cart-btn {
    transition: all 0.3s;
}
.add-to-cart-btn:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity buttons
    document.querySelectorAll('.decrease-qty').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    document.querySelectorAll('.increase-qty').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('max'));
            if (currentValue < maxValue) {
                input.value = currentValue + 1;
            }
        });
    });

    // Handle form submission with animation
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.add-to-cart-btn');
            btn.innerHTML = '<i class="bi bi-check2"></i> Ditambahkan!';
            btn.classList.add('btn-success');
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-cart-plus"></i> Tambah ke Keranjang';
                btn.classList.remove('btn-success');
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection 
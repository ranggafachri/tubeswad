@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cari Produk dari Open Food Facts') }}</div>

                <div class="card-body">
                    <form method="GET" action="{{ route('products.search-api') }}" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ $search }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>

                    @if($search)
                        <p>Hasil pencarian untuk: <strong>{{ $search }}</strong></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(count($products) > 0)
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if(!empty($product['image_url']))
                    <img src="{{ $product['image_url'] }}" class="card-img-top" alt="{{ $product['product_name'] ?? 'Product Image' }}" style="height: 200px; object-fit: contain;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $product['product_name'] ?? 'Unnamed Product' }}</h5>
                    <p class="card-text">
                        <small class="text-muted">Barcode: {{ $product['code'] ?? 'N/A' }}</small>
                    </p>
                    <p class="card-text">{{ Str::limit($product['ingredients_text'] ?? 'No description available', 100) }}</p>
                </div>

                <div class="card-footer">
                    <form action="{{ route('products.import-api') }}" method="POST">
                        @csrf
                        <input type="hidden" name="barcode" value="{{ $product['code'] }}">
                        
                        <div class="mb-3">
                            <label for="price_{{ $product['code'] }}" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="price_{{ $product['code'] }}" name="price" step="0.01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock_{{ $product['code'] }}" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stock_{{ $product['code'] }}" name="stock" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_{{ $product['code'] }}" class="form-label">Kategori</label>
                            <select class="form-select" id="category_{{ $product['code'] }}" name="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Tambah ke Katalog
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @elseif($search)
        <div class="alert alert-info">
            Tidak ada produk yang ditemukan untuk pencarian "{{ $search }}".
        </div>
    @endif
</div>
@endsection 
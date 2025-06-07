@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Produk
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row mb-3">
                            <label for="barcode" class="col-md-4 col-form-label text-md-right">{{ __('Barcode') }}</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="barcode" type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" value="{{ old('barcode', $product->barcode) }}" autocomplete="barcode">
                                    <button type="button" class="btn btn-outline-secondary" id="fetchProduct">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>

                                @error('barcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Produk') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" required autocomplete="name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Deskripsi') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $product->description) }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Harga') }}</label>

                            <div class="col-md-6">
                                <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" required>

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="stock" class="col-md-4 col-form-label text-md-right">{{ __('Stok') }}</label>

                            <div class="col-md-6">
                                <input id="stock" type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $product->stock) }}" required>

                                @error('stock')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="category_id" class="col-md-4 col-form-label text-md-right">{{ __('Kategori') }}</label>

                            <div class="col-md-6">
                                <select id="category_id" class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Gambar') }}</label>

                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                                
                                @if($product->image)
                                <div id="current-image" class="mt-2">
                                    <img src="{{ Storage::url($product->image) }}" alt="Current Image" style="max-width: 200px;">
                                </div>
                                @endif

                                <div id="preview-container" class="mt-2" style="display: none;">
                                    <img id="preview-image" src="" alt="Preview" style="max-width: 200px;">
                                </div>

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Simpan Perubahan') }}
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    {{ __('Batal') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fetchButton = document.getElementById('fetchProduct');
    const barcodeInput = document.getElementById('barcode');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const currentImage = document.getElementById('current-image');

    fetchButton.addEventListener('click', function() {
        const barcode = barcodeInput.value.trim();
        if (!barcode) {
            alert('Silakan masukkan barcode terlebih dahulu');
            return;
        }

        fetch(`/products/fetch-data?barcode=${barcode}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!nameInput.value) {
                        nameInput.value = data.name || '';
                    }
                    if (!descriptionInput.value) {
                        descriptionInput.value = data.description || '';
                    }
                    if (data.image) {
                        previewImage.src = data.image;
                        previewContainer.style.display = 'block';
                        if (currentImage) {
                            currentImage.style.display = 'none';
                        }
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data produk');
            });
    });

    // Preview uploaded image
    document.getElementById('image').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
                if (currentImage) {
                    currentImage.style.display = 'none';
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>
@endpush
@endsection 
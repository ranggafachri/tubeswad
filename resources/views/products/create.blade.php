@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>Tambah Produk Baru
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <!-- Barcode Field -->
                        <div class="mb-3">
                            <label for="barcode" class="form-label">
                                <i class="fas fa-barcode me-1"></i>Barcode
                            </label>
                            <div class="input-group">
                                <input id="barcode" type="text" 
                                       class="form-control @error('barcode') is-invalid @enderror" 
                                       name="barcode" 
                                       value="{{ old('barcode') }}" 
                                       placeholder="Masukkan barcode produk">
                                <button type="button" class="btn btn-outline-secondary" id="fetchProduct">
                                    <i class="fas fa-magnifying-glass"></i>
                                </button>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Opsional - Untuk mengambil data dari Open Food Facts</small>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nama Produk
                            </label>
                            <input id="name" type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="Masukkan nama produk">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Deskripsi
                            </label>
                            <textarea id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Price Field -->
                        <div class="mb-3">
                            <label for="price" class="form-label">
                                <i class="fas fa-tag me-1"></i>Harga
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input id="price" type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       name="price" 
                                       value="{{ old('price') }}" 
                                       required 
                                       min="0" 
                                       step="100"
                                       placeholder="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stock Field -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">
                                <i class="fas fa-boxes-stacked me-1"></i>Stok
                            </label>
                            <input id="stock" type="number" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   name="stock" 
                                   value="{{ old('stock') }}" 
                                   required 
                                   min="0"
                                   placeholder="0">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Field -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                <i class="fas fa-image me-1"></i>Gambar Produk
                            </label>
                            <input id="image" type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   name="image" 
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="preview-container" class="mt-2" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Category Field -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">
                                <i class="fas fa-tags me-1"></i>Kategori
                            </label>
                            <select id="category_id" 
                                   class="form-select @error('category_id') is-invalid @enderror" 
                                   name="category_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-toggle-on me-1"></i>Produk Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Produk
                    </button>
                </div>
            </form>
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

    fetchButton.addEventListener('click', function() {
        const barcode = barcodeInput.value.trim();
        if (!barcode) {
            alert('Silakan masukkan barcode terlebih dahulu');
            return;
        }

        fetchButton.disabled = true;
        fetchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data produk');
            })
            .finally(() => {
                fetchButton.disabled = false;
                fetchButton.innerHTML = '<i class="fas fa-magnifying-glass"></i>';
            });
    });

    // Preview uploaded image
    document.getElementById('image').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>
@endpush
@endsection 
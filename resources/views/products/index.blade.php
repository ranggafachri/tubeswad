@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-boxes me-2"></i>Katalog Produk
        </h2>
        <div>
            <a href="{{ route('products.search-api') }}" class="btn btn-info me-2">
                <i class="fas fa-magnifying-glass me-1"></i> Cari dari Open Food Facts
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4" style="width: 80px">No</th>
                            <th class="py-3 px-4" style="width: 100px">Gambar</th>
                            <th class="py-3 px-4">Nama Produk</th>
                            <th class="py-3 px-4" style="width: 150px">Barcode</th>
                            <th class="py-3 px-4" style="width: 150px">Harga</th>
                            <th class="py-3 px-4" style="width: 100px">Stok</th>
                            <th class="py-3 px-4" style="width: 100px">Status</th>
                            <th class="py-3 px-4 text-center" style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr>
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td class="px-4">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="rounded"
                                             style="height: 50px; width: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="height: 50px; width: 50px;">
                                            <i class="fas fa-image text-secondary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </div>
                                </td>
                                <td class="px-4">
                                    @if($product->barcode)
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-barcode me-2"></i>
                                            {{ $product->barcode }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4">
                                    <span class="fw-bold">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-4">
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-icon btn-outline-warning"
                                           data-bs-toggle="tooltip"
                                           title="Edit Produk">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-icon btn-outline-danger" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $product->name }}?')"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus Produk">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-center">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada produk</h5>
                                        <p class="text-muted mb-0">Mulai tambahkan produk ke katalog Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table > :not(caption) > * > * {
    padding: 1rem 0.5rem;
}
.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s ease;
}
.btn-icon:hover {
    transform: translateY(-1px);
}
.btn-icon i {
    font-size: 14px;
}
.table > tbody > tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
})
</script>
@endpush
@endsection 
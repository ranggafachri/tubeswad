@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Transaksi #{{ $transaction->id }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.transactions.index') }}" class="text-decoration-none">
                            <i class="bi bi-receipt me-1"></i>Daftar Transaksi
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail Transaksi</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.transactions.edit', $transaction) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Edit Transaksi
            </a>
            <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Daftar Produk</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <i class="bi bi-box me-1"></i>{{ $item->product->name }}
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-tag me-1"></i>Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-123 me-1"></i>{{ $item->quantity }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <i class="bi bi-currency-dollar me-1"></i>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-light">
                                    <td colspan="3" class="text-end ps-4">
                                        <strong>Total:</strong>
                                    </td>
                                    <td class="text-end pe-4">
                                        <strong>
                                            <i class="bi bi-currency-dollar me-1"></i>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-warning',
                                'processing' => 'bg-info',
                                'shipped' => 'bg-primary',
                                'delivered' => 'bg-success',
                                'cancelled' => 'bg-danger'
                            ];
                            $statusIcons = [
                                'pending' => 'bi-hourglass',
                                'processing' => 'bi-gear',
                                'shipped' => 'bi-truck',
                                'delivered' => 'bi-check2-circle',
                                'cancelled' => 'bi-x-circle'
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="badge {{ $statusClasses[$transaction->status] }}">
                            <i class="bi {{ $statusIcons[$transaction->status] }} me-1"></i>
                            {{ $statusLabels[$transaction->status] }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar2 me-2"></i>
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Pelanggan</small>
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-person me-2"></i>
                            {{ $transaction->user->name }}
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-envelope me-2"></i>
                            {{ $transaction->user->email }}
                        </div>
                        @if($transaction->user->phone)
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone me-2"></i>
                            {{ $transaction->user->phone }}
                        </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Metode Pembayaran</small>
                        @if($transaction->payment_method === 'cash')
                            <span class="badge bg-success">
                                <i class="bi bi-cash me-1"></i>Tunai
                            </span>
                            <div class="mt-2">
                                <small class="text-muted d-block">Jumlah Bayar</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-currency-dollar me-2"></i>
                                    Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}
                                </div>
                                <small class="text-muted d-block mt-2">Kembalian</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-cash-stack me-2"></i>
                                    Rp {{ number_format($transaction->payment_amount - $transaction->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        @else
                            <span class="badge bg-primary">
                                <i class="bi bi-qr-code me-1"></i>QRIS
                            </span>
                            <div class="mt-2">
                                <small class="text-muted d-block">Kode QRIS</small>
                                <div class="bg-light p-2 rounded">
                                    <small class="text-break">{{ $transaction->qr_code }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat</small>
                        {{ $transaction->shipping_address }}
                    </div>
                    @if($transaction->notes)
                    <div>
                        <small class="text-muted d-block">Catatan</small>
                        {{ $transaction->notes }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .breadcrumb-item a {
        color: var(--primary-color);
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }
</style>
@endpush
@endsection 
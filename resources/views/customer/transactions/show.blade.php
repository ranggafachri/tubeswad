@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Transaksi #{{ $transaction->id }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('customer.transactions') }}" class="text-decoration-none">
                            <i class="bi bi-clock-history me-1"></i>Riwayat Transaksi
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail Transaksi</li>
                </ol>
            </nav>
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
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Produk</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Transaksi</h5>
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
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="badge {{ $statusClasses[$transaction->status] }}">
                            {{ $statusLabels[$transaction->status] }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal</small>
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Metode Pembayaran</small>
                        @if($transaction->payment_method === 'cash')
                            <span class="badge bg-success">
                                <i class="bi bi-cash me-1"></i>Tunai
                            </span>
                            <div class="mt-2">
                                <small class="text-muted d-block">Jumlah Bayar</small>
                                Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}
                                <small class="text-muted d-block mt-2">Kembalian</small>
                                Rp {{ number_format($transaction->payment_amount - $transaction->total_amount, 0, ',', '.') }}
                            </div>
                        @else
                            <span class="badge bg-primary">
                                <i class="bi bi-qr-code me-1"></i>QRIS
                            </span>
                            @if($transaction->status === 'pending')
                                <div class="mt-3 text-center">
                                    <small class="text-muted d-block mb-2">Scan QR Code untuk Pembayaran</small>
                                    <div class="qr-code-container bg-white p-3 rounded shadow-sm mb-3">
                                        <img src="{{ asset('images/qr code dummy.png') }}" alt="QRIS Payment" class="img-fluid" style="max-width: 200px;">
                                    </div>
                                    <div class="alert alert-info" role="alert">
                                        <small>
                                            <i class="bi bi-info-circle me-1"></i>
                                            Total Pembayaran: <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                                        </small>
                                    </div>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Pembayaran Aman via QRIS
                                    </small>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pengiriman</h5>
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

    .table td {
        vertical-align: middle;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }

    .breadcrumb-item a {
        color: var(--bs-primary);
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    .qr-code-container {
        background: #fff;
        border: 1px solid #e5e5e5;
    }
</style>
@endpush
@endsection 
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>Riwayat Transaksi
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('products.catalog') }}" class="btn btn-primary">
                <i class="bi bi-cart-plus me-1"></i>Belanja Lagi
            </a>
        </div>
    </div>

    @if($transactions->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>Anda belum memiliki riwayat transaksi.
            <a href="{{ route('products.catalog') }}" class="alert-link">Mulai belanja sekarang!</a>
        </div>
    @else
        <div class="row">
            @foreach($transactions as $transaction)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Transaksi #{{ $transaction->id }}</h5>
                            <span class="badge bg-{{ 
                                $transaction->status === 'pending' ? 'warning' :
                                ($transaction->status === 'processing' ? 'info' :
                                ($transaction->status === 'shipped' ? 'primary' :
                                ($transaction->status === 'delivered' ? 'success' : 'danger')))
                            }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Tanggal Transaksi:</small>
                                <div>{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Total:</small>
                                <div class="fw-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Metode Pembayaran:</small>
                                <div>
                                    @if($transaction->payment_method === 'cash')
                                        <span class="badge bg-success">
                                            <i class="bi bi-cash me-1"></i>Tunai
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="bi bi-qr-code me-1"></i>QRIS
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction->items as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('customer.transactions.show', $transaction) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
</style>
@endpush
@endsection 
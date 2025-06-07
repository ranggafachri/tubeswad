@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Status Transaksi #{{ $transaction->id }}</h5>
                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Detail Pelanggan</h6>
                            <p class="mb-1"><strong>{{ $transaction->user->name }}</strong></p>
                            <p class="mb-1">{{ $transaction->user->email }}</p>
                            @if($transaction->user->phone)
                            <p class="mb-0">{{ $transaction->user->phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Detail Pembayaran</h6>
                            @if($transaction->payment_method === 'cash')
                                <p class="mb-1">
                                    <span class="badge bg-success">
                                        <i class="bi bi-cash me-1"></i>Tunai
                                    </span>
                                </p>
                                <p class="mb-1">Jumlah Bayar: Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</p>
                                <p class="mb-0">Kembalian: Rp {{ number_format($transaction->payment_amount - $transaction->total_amount, 0, ',', '.') }}</p>
                            @else
                                <p class="mb-1">
                                    <span class="badge bg-primary">
                                        <i class="bi bi-qr-code me-1"></i>QRIS
                                    </span>
                                </p>
                                <p class="mb-0">Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-2">Alamat Pengiriman</h6>
                            <p class="mb-0">{{ $transaction->shipping_address }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status Transaksi</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ $transaction->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ $transaction->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="delivered" {{ $transaction->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
</style>
@endpush
@endsection 
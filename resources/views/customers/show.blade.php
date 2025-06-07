@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <div class="d-flex align-items-center">
                    <div class="avatar-circle me-3">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    {{ $customer->name }}
                </div>
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none">
                            <i class="bi bi-people me-1"></i>Data Pelanggan
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail Pelanggan</li>
                </ol>
            </nav>
        </div>
        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Hapus Pelanggan
            </button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pelanggan</h5>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-muted me-2"></i>
                            {{ $customer->email }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">No. Telepon</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone text-muted me-2"></i>
                            {{ $customer->phone }}
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block">Terdaftar</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar text-muted me-2"></i>
                            {{ $customer->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Statistik Transaksi</h5>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-cart-check text-primary me-2"></i>
                                    <h6 class="mb-0">Total Transaksi</h6>
                                </div>
                                <h3 class="mb-0">{{ $customer->transactions_count }}</h3>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-currency-dollar text-primary me-2"></i>
                                    <h6 class="mb-0">Total Pembelian</h6>
                                </div>
                                <h3 class="mb-0">Rp {{ number_format($customer->transactions_sum_total_amount, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Riwayat Transaksi</h5>
        </div>
        <div class="card-body p-0">
            @if($customer->transactions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->transactions->sortByDesc('created_at') as $transaction)
                        <tr>
                            <td class="ps-4">#{{ $transaction->id }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            <td>
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
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted display-4"></i>
                <p class="mt-3 text-muted">Pelanggan belum memiliki transaksi</p>
            </div>
            @endif
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

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
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

    .avatar-circle {
        width: 45px;
        height: 45px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    h3 {
        color: var(--primary-color);
        font-weight: 600;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded {
        border-radius: 10px !important;
    }
</style>
@endpush
@endsection 
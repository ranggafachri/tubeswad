@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-receipt me-2"></i>Daftar Transaksi
        </h2>
    </div>

    @if($transactions->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-receipt-cutoff display-1 text-muted"></i>
            <p class="lead mt-3 text-muted">Belum ada transaksi</p>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td class="ps-4">
                                    <strong>#{{ $transaction->id }}</strong>
                                </td>
                                <td>
                                    <i class="bi bi-calendar2 me-1"></i>
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <div>
                                        <i class="bi bi-person me-1"></i>
                                        {{ $transaction->user->name }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i>
                                        {{ $transaction->user->email }}
                                    </small>
                                </td>
                                <td>
                                    <i class="bi bi-currency-dollar me-1"></i>
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi {{ $transaction->payment_method === 'cash' ? 'bi-cash' : 'bi-qr-code' }} me-1"></i>
                                        {{ $transaction->payment_method === 'cash' ? 'Tunai' : 'QRIS' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.transactions.edit', $transaction) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Edit Transaksi">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.transactions.destroy', $transaction) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus Transaksi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
        padding: 0.4rem;
        line-height: 1;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }

    .btn-group .btn i {
        font-size: 1.1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
@endpush
@endsection 
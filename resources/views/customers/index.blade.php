@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-people me-2"></i>Data User
        </h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th class="text-center">Total Transaksi</th>
                            <th>Terdaftar</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div>{{ $customer->name }}</div>
                                        <small class="text-muted">ID: #{{ $customer->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">
                                    {{ $customer->transactions_count }}
                                </span>
                            </td>
                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-6"></i>
                                    <p class="mt-2">Belum ada pelanggan</p>
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

    .avatar-circle {
        width: 35px;
        height: 35px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
</style>
@endpush
@endsection

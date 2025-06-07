@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
        </h2>
        <a href="{{ route('products.catalog') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Lanjut Belanja
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Keranjang Belanja Kosong</h3>
            <p class="text-muted">Anda belum menambahkan produk apapun ke keranjang.</p>
            <a href="{{ route('products.catalog') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-1"></i>Mulai Belanja
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 100px">Produk</th>
                                        <th>Nama</th>
                                        <th class="text-center" style="width: 150px">Jumlah</th>
                                        <th class="text-end" style="width: 150px">Harga</th>
                                        <th class="text-end" style="width: 150px">Subtotal</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail"
                                                         style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                         style="width: 80px; height: 80px;">
                                                        <i class="fas fa-image fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-boxes-stacked me-1"></i>Stok: {{ $item->product->stock }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <form action="{{ route('cart.update-item', $item->id) }}" 
                                                          method="POST" 
                                                          class="d-flex align-items-center">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary decrease-qty"
                                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" 
                                                               name="quantity" 
                                                               class="form-control form-control-sm text-center mx-2 quantity-input" 
                                                               value="{{ $item->quantity }}" 
                                                               min="1" 
                                                               max="{{ $item->product->stock }}"
                                                               style="width: 60px">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary increase-qty"
                                                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <form action="{{ route('cart.remove-item', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Item</span>
                            <span>{{ $cartItems->sum('quantity') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Harga</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Alamat Pengiriman</label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <div class="d-flex gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                                        <label class="form-check-label" for="payment_cash">
                                            Tunai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_qris" value="qris">
                                        <label class="form-check-label" for="payment_qris">
                                            QRIS
                                        </label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="cashPaymentField" class="mb-3">
                                <label for="payment_amount" class="form-label">Jumlah Pembayaran</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="payment_amount" id="payment_amount" class="form-control @error('payment_amount') is-invalid @enderror" value="{{ old('payment_amount', $total) }}" min="{{ $total }}">
                                </div>
                                @error('payment_amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-bag me-1"></i>Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity updates
    document.querySelectorAll('.decrease-qty, .increase-qty').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            const input = form.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('max'));
            
            if (this.classList.contains('decrease-qty') && currentValue > 1) {
                input.value = currentValue - 1;
            } else if (this.classList.contains('increase-qty') && currentValue < maxValue) {
                input.value = currentValue + 1;
            }
            
            // Submit the form
            form.submit();
        });
    });

    // Handle quantity input changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Handle payment method change
    const cashPaymentField = document.getElementById('cashPaymentField');
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');

    function toggleCashPaymentField() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        cashPaymentField.style.display = selectedMethod === 'cash' ? 'block' : 'none';
    }

    paymentMethodInputs.forEach(input => {
        input.addEventListener('change', toggleCashPaymentField);
    });

    // Initial toggle
    toggleCashPaymentField();
});
</script>
@endpush
@endsection
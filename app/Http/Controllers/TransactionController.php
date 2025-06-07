<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions (Admin only)
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'items.product'])
            ->latest()
            ->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display customer's transactions
     */
    public function customerTransactions()
    {
        $user = auth()->user();

        if (!$user->isCustomer()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $transactions = Transaction::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('customer.transactions.index', compact('transactions'));
    }

    /**
     * Display transaction detail for customer
     */
    public function customerTransactionDetail(Transaction $transaction)
    {
        $user = auth()->user();

        if (!$user->isCustomer() || $transaction->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('customer.transactions.show', compact('transaction'));
    }

    /**
     * Display transaction detail for admin
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction (Admin only)
     */
    public function edit(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        return view('admin.transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified transaction (Admin only)
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    /**
     * Remove the specified transaction (Admin only)
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaksi berhasil dihapus!');
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
            'payment_amount' => $request->payment_method === 'cash' ? $request->payment_amount : $totalAmount,
            'payment_status' => 'pending',
            'qr_code' => $request->payment_method === 'qris' ? 'QRISTJMART' . now()->format('YmdHis') . str_pad($totalAmount, 12, '0', STR_PAD_LEFT) : null,
        ]);

        // Create transaction items and update stock
        foreach ($cart->items as $item) {
            $transaction->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Update stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear the cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('customer.transactions.show', $transaction)
                       ->with('success', 'Pesanan berhasil dibuat! ' . 
                           ($request->payment_method === 'qris' ? 'Silakan scan QR Code untuk melakukan pembayaran.' : ''));
    }
}

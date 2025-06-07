<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::whereHas('cart', function($query) {
            $query->where('user_id', auth()->id())
                  ->where('status', 'active');
        })->with(['cart', 'product'])->get();

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validate stock
        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active'],
            ['user_id' => auth()->id(), 'status' => 'active']
        );

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Check if new total quantity exceeds stock
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Total jumlah melebihi stok yang tersedia!');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price // Update price in case it changed
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate stock
        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jumlah berhasil diperbarui!'
            ]);
        }

        return back()->with('success', 'Jumlah berhasil diperbarui!');
    }

    public function removeItem(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris',
            'payment_amount' => 'required_if:payment_method,cash|numeric|min:0'
        ]);

        $cart = Cart::where('user_id', auth()->id())
                   ->where('status', 'active')
                   ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Keranjang belanja Anda kosong!');
        }

        // Hitung total
        $total = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Validate stock for all items
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')
                               ->with('error', "Stok {$item->product->name} tidak mencukupi!");
            }
        }

        // Validate payment amount for cash payment
        if ($request->payment_method === 'cash' && $request->payment_amount < $total) {
            return redirect()->route('cart.index')
                           ->with('error', 'Jumlah pembayaran kurang dari total belanja!');
        }

        // Generate QR Code for QRIS payment
        $qrCode = null;
        if ($request->payment_method === 'qris') {
            // Generate a unique QRIS code that includes:
            // - Merchant ID (in this case we use TJMart)
            // - Transaction ID (timestamp + user ID)
            // - Amount (padded to 12 digits)
            $merchantId = 'TJMART';
            $transactionId = time() . str_pad(auth()->id(), 5, '0', STR_PAD_LEFT);
            $amount = str_pad(number_format($total, 2, '', ''), 12, '0', STR_PAD_LEFT);
            
            // Format: QRIS{merchantId}{transactionId}{amount}
            $qrCode = "QRIS{$merchantId}{$transactionId}{$amount}";
        }

        // Create transaction
        $user = auth()->user();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
            'payment_amount' => $request->payment_method === 'cash' ? $request->payment_amount : $total,
            'payment_status' => 'pending',
            'qr_code' => $qrCode
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

        // Mark cart as completed
        $cart->update(['status' => 'completed']);

        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('customer.transactions.show', $transaction)
            ->with('success', 'Pesanan berhasil dibuat!');
    }
}

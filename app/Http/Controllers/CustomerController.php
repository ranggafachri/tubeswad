<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', '=', 'customer')
            ->withCount('transactions')
            ->latest()
            ->get();

        return view('customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->loadCount('transactions')
            ->loadSum('transactions', 'total_amount');

        return view('customers.show', compact('customer'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        if (!$user->isCustomer()) {
            abort(404);
        }

        return view('customers.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!$user->isCustomer()) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:15',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(User $customer)
    {
        if (!$customer->isCustomer()) {
            abort(404);
        }

        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}